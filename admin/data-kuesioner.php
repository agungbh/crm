<?php
include '../config.php';

// Proteksi Hak Akses Login
if (!isset($_SESSION['role'])) { 
    header("Location: ../login.php"); 
    exit; 
}

// =========================================================================
// SEKSI PEMROSESAN BACKUP DATA UTAH (EKSPOR .SQL LANGSUNG DIKOMPRES KE .ZIP)
// =========================================================================
if (isset($_GET['action']) && $_GET['action'] == 'backup') {
    if ($_SESSION['role'] == 'admin') {
        
        // 1. Generate Konten Struktur & Data SQL di dalam Buffer Memory
        $sql_content = "-- --------------------------------------------------------\n";
        $sql_content .= "-- Master Data Backup ZIP: tabel_quisoner\n";
        $sql_content .= "-- Perguruan Tinggi: Universitas Bina Sarana Informatika\n";
        $sql_content .= "-- Waktu Backup: " . date('Y-m-d H:i:s') . "\n";
        $sql_content .= "-- --------------------------------------------------------\n\n";
        
        $sql_content .= "DROP TABLE IF EXISTS `tabel_quisoner`;\n";
        
        // Membaca struktur asli tabel
        $create_table_q = mysqli_query($conn, "SHOW CREATE TABLE `tabel_quisoner`");
        $create_table_row = mysqli_fetch_row($create_table_q);
        $sql_content .= $create_table_row[1] . ";\n\n";

        // Mengambil seluruh data rekaman (Tanpa filter agar cadangan data utuh)
        $data_backup = mysqli_query($conn, "SELECT * FROM `tabel_quisoner` ORDER BY `id` ASC");
        
        if (mysqli_num_rows($data_backup) > 0) {
            $sql_content .= "-- Dump Data Record --\n";
            while ($row = mysqli_fetch_assoc($data_backup)) {
                $fields = array_keys($row);
                $values = array_values($row);
                
                $escaped_values = array_map(function($val) use ($conn) {
                    if ($val === null) return "NULL";
                    return "'" . mysqli_real_escape_string($conn, $val) . "'";
                }, $values);

                $sql_content .= "INSERT INTO `tabel_quisoner` (`" . implode("`, `", $fields) . "`) VALUES (" . implode(", ", $escaped_values) . ");\n";
            }
        }

        // 2. Proses Kompresi Menggunakan ZipArchive ke File Sementara (Temporary File)
        $zip = new ZipArchive();
        $zip_filename = "backup_quisoner_" . date('Y-m-d_H-i-s') . ".zip";
        $tmp_file = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($tmp_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Memasukkan file SQL ke dalam ZIP berkas
            $zip->addFromString('tabel_quisoner.sql', $sql_content);
            $zip->close();

            // 3. Mengirimkan File ZIP ke Browser untuk Auto-Download
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
            header('Content-Length: ' . filesize($tmp_file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            readfile($tmp_file);
            unlink($tmp_file); // Hapus berkas temporary di server setelah diunduh
            exit;
        } else {
            echo "<script>alert('Gagal mengompresi file backup ke format ZIP.'); window.location='data-kuesioner.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Akses Ditolak! Akun Operator tidak memiliki hak akses mencadangkan database.'); window.location='data-kuesioner.php';</script>";
        exit;
    }
}

// MENANGKAPI PARAMETER FILTER DINAMIS
$f_tinggal   = isset($_GET['f_tinggal']) ? mysqli_real_escape_string($conn, $_GET['f_tinggal']) : '';
$f_sekolah   = isset($_GET['f_sekolah']) ? mysqli_real_escape_string($conn, $_GET['f_sekolah']) : '';
$f_beasiswa  = isset($_GET['f_beasiswa']) ? mysqli_real_escape_string($conn, $_GET['f_beasiswa']) : '';
$f_jenis_b   = isset($_GET['f_jenis_b']) ? mysqli_real_escape_string($conn, $_GET['f_jenis_b']) : '';
$f_memilih   = isset($_GET['f_memilih']) ? mysqli_real_escape_string($conn, $_GET['f_memilih']) : '';
$f_mengetahui= isset($_GET['f_mengetahui']) ? mysqli_real_escape_string($conn, $_GET['f_mengetahui']) : '';
$f_minat     = isset($_GET['f_minat']) ? mysqli_real_escape_string($conn, $_GET['f_minat']) : '';
$f_organisasi= isset($_GET['f_organisasi']) ? mysqli_real_escape_string($conn, $_GET['f_organisasi']) : '';

$tab = isset($_GET['tab']) ? mysqli_real_escape_string($conn, $_GET['tab']) : 'crud';

// MEMBANGUN KONDISI IF FILTER
$conditions = [];
if ($f_tinggal != '')    { $conditions[] = "t1.tempat_tinggal = '$f_tinggal'"; }
if ($f_sekolah != '')    { $conditions[] = "t1.sekolah LIKE '%$f_sekolah%'"; }
if ($f_beasiswa != '')   { $conditions[] = "t1.beasiswa = '$f_beasiswa'"; }
if ($f_jenis_b != '')    { $conditions[] = "t1.jenis_beasiswa = '$f_jenis_b'"; }
if ($f_memilih != '')    { $conditions[] = "t1.memilih_ubsi = '$f_memilih'"; }
if ($f_mengetahui != '') { $conditions[] = "t1.mengetahui_ubsi = '$f_mengetahui'"; }
if ($f_minat != '')      { $conditions[] = "t1.minat_kompetensi = '$f_minat'"; }
if ($f_organisasi != '') { $conditions[] = "t1.aktivitas_organisasi = '$f_organisasi'"; }

// FILTER QUERY DENGAN JAMINAN GARANSI ANTI-DUPLIKAT NIM TERKUAT
if (count($conditions) > 0) {
    $query_master = "SELECT t1.* FROM tabel_quisoner t1
                     WHERE t1.id = (SELECT MAX(t2.id) FROM tabel_quisoner t2 WHERE t2.nim = t1.nim)
                     AND " . implode(' AND ', $conditions) . "
                     ORDER BY t1.id DESC";
} else {
    $query_master = "SELECT t1.* FROM tabel_quisoner t1
                     WHERE t1.id = (SELECT MAX(t2.id) FROM tabel_quisoner t2 WHERE t2.nim = t1.nim)
                     ORDER BY t1.id DESC";
}
$result = mysqli_query($conn, $query_master);
$total_rows = mysqli_num_rows($result);

// --- AGREGASI RESOURCE DATA UNTUK VARIABEL BAGAN MARKETING (FIXED SUM SPASI GANDA) ---
function getChartData($conn, $field, $conditions, $additional_where = "", $limit = "") {
    $where = "WHERE t1.id = (SELECT MAX(t2.id) FROM tabel_quisoner t2 WHERE t2.nim = t1.nim)";
    if (count($conditions) > 0) { $where .= " AND " . implode(' AND ', $conditions); }
    if ($additional_where != "") { $where .= " AND " . $additional_where; }
    
    // FIX: Jika field adalah sekolah, lebur variasi spasi ganda ketikan maba menjadi single space
    if ($field === 'sekolah') {
        $select_field = "TRIM(REPLACE(REPLACE(REPLACE(t1.sekolah, '    ', ' '), '   ', ' '), '  ', ' '))";
    } else {
        $select_field = "t1.$field";
    }
    
    $query = "SELECT $select_field as label, COUNT(*) as jumlah 
              FROM tabel_quisoner t1 
              $where 
              GROUP BY label 
              ORDER BY jumlah DESC $limit";
    return mysqli_query($conn, $query);
}

$chart_tinggal    = getChartData($conn, "tempat_tinggal", $conditions);
$chart_sekolah    = getChartData($conn, "sekolah", $conditions, "", "LIMIT 5");
$chart_beasiswa   = getChartData($conn, "beasiswa", $conditions);
$chart_jenis_b    = getChartData($conn, "jenis_beasiswa", $conditions, "t1.jenis_beasiswa IS NOT NULL");
$chart_memilih    = getChartData($conn, "memilih_ubsi", $conditions);
$chart_mengetahui = getChartData($conn, "mengetahui_ubsi", $conditions);
$chart_minat      = getChartData($conn, "minat_kompetensi", $conditions);
$chart_organisasi = getChartData($conn, "aktivitas_organisasi", $conditions);

// --- KOMPILASI DATA MODUS RESPONDEN SECARA DINAMIS (IKUT FILTER) ---
$where_modus = "WHERE t1.id = (SELECT MAX(t2.id) FROM tabel_quisoner t2 WHERE t2.nim = t1.nim)";
if (count($conditions) > 0) { 
    $where_modus .= " AND " . implode(' AND ', $conditions); 
}

$modus_tinggal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.tempat_tinggal, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.tempat_tinggal ORDER BY jml DESC LIMIT 1"))['tempat_tinggal'] ?? '-';
$modus_sekolah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT TRIM(REPLACE(REPLACE(REPLACE(t1.sekolah, '    ', ' '), '   ', ' '), '  ', ' ')) as sch, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY sch ORDER BY jml DESC LIMIT 1"))['sch'] ?? '-';
$modus_beasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.beasiswa, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.beasiswa ORDER BY jml DESC LIMIT 1"))['beasiswa'] ?? '-';
$modus_jenis_b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.jenis_beasiswa, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus AND t1.jenis_beasiswa IS NOT NULL GROUP BY t1.jenis_beasiswa ORDER BY jml DESC LIMIT 1"))['jenis_beasiswa'] ?? 'Belum Terfokus';
$modus_memilih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.memilih_ubsi, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.memilih_ubsi ORDER BY jml DESC LIMIT 1"))['memilih_ubsi'] ?? '-';
$modus_mengetahui = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.mengetahui_ubsi, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.mengetahui_ubsi ORDER BY jml DESC LIMIT 1"))['mengetahui_ubsi'] ?? '-';
$modus_minat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.minat_kompetensi, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.minat_kompetensi ORDER BY jml DESC LIMIT 1"))['minat_kompetensi'] ?? '-';
$modus_organisasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT t1.aktivitas_organisasi, COUNT(*) as jml FROM tabel_quisoner t1 $where_modus GROUP BY t1.aktivitas_organisasi ORDER BY jml DESC LIMIT 1"))['aktivitas_organisasi'] ?? '-';

// Dropdown filter data
$opt_tinggal = mysqli_query($conn, "SELECT tempat_tinggal FROM tabel_quisoner GROUP BY tempat_tinggal");
$opt_beasiswa = mysqli_query($conn, "SELECT beasiswa FROM tabel_quisoner GROUP BY beasiswa");
$opt_jenis_b = mysqli_query($conn, "SELECT jenis_beasiswa FROM tabel_quisoner WHERE jenis_beasiswa IS NOT NULL GROUP BY jenis_beasiswa");
$opt_memilih = mysqli_query($conn, "SELECT memilih_ubsi FROM tabel_quisoner GROUP BY memilih_ubsi");
$opt_mengetahui = mysqli_query($conn, "SELECT mengetahui_ubsi FROM tabel_quisoner GROUP BY mengetahui_ubsi");
$opt_minat = mysqli_query($conn, "SELECT minat_kompetensi FROM tabel_quisoner GROUP BY minat_kompetensi");
$opt_organisasi = mysqli_query($conn, "SELECT aktivitas_organisasi FROM tabel_quisoner GROUP BY aktivitas_organisasi");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" style="content=width=device-width, initial-scale=1.0">
    <title>Panel Kelola Kuesioner PMB - UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 5px; background-color: #fff; font-size: 8.5px; color: #000; }
            .card { border: none !important; box-shadow: none !important; }
            .table-responsive { overflow: visible !important; }
            .table th { background-color: #212529 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .kesimpulan-box { background-color: #f8f9fa !important; border: 1px solid #dee2e6 !important; page-break-inside: avoid; }
            .chart-card { page-break-inside: avoid; }
        }
        .kesimpulan-box { background-color: #e9ecef; border-left: 5px solid #198754; }
        .nav-tabs .nav-link { color: #495057; font-weight: bold; }
        .nav-tabs .nav-link.active { background-color: #fff; border-bottom-color: transparent; color: #0d6efd; }
        .uraian-table { font-size: 10.5px; }
        .total-badge { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; border-radius: 6px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm no-print">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">Dashboard UBSI [<?= strtoupper($_SESSION['role']); ?>]</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="index2.php">📊 Dashboard</a>
            <a class="nav-link" href="crm.php">📋 Manajemen CR</a>
            <a class="nav-link" href="hasil_crm.php">📈 Hasil & Analisis CRM</a>
            <a class="nav-link" href="generate.php?p=bju" target="_blank">📈 Laporan Data BJU</a>
            <a class="nav-link" href="data-kuesioner.php" target="_blank">📈 Laporan Empowering BJU</a>    
        </div>
        <a class="nav-link btn btn-danger btn-sm text-white ms-3 px-3" href="../logout.php">Logout</a>
    </div>
</nav>

<div class="container-fluid my-4 px-4">
    <div class="d-none d-print-block text-center mb-3">
        <h4 class="fw-bold mb-0">UNIVERSITAS BINA SARANA INFORMATIKA KAMPUS TASIKMALAYA</h4>
        <h6 class="text-uppercase text-secondary fw-bold mb-1">Laporan Validasi Data Master Kuesioner & Intelijen PMB</h6>
        <hr style="border: 1.5px solid #000; opacity: 1; margin-top: 4px; margin-bottom: 10px;">
    </div>

    <ul class="nav nav-tabs mb-4 no-print">
        <li class="nav-item">
            <a class="nav-link <?= $tab == 'crud' ? 'active' : ''; ?>" href="data-kuesioner.php?tab=crud&f_tinggal=<?= $f_tinggal; ?>&f_sekolah=<?= urlencode($f_sekolah); ?>&f_beasiswa=<?= $f_beasiswa; ?>&f_jenis_b=<?= $f_jenis_b; ?>&f_memilih=<?= $f_memilih; ?>&f_mengetahui=<?= $f_mengetahui; ?>&f_minat=<?= $f_minat; ?>&f_organisasi=<?= $f_organisasi; ?>">📂 Data Kuesioner (CRUD)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $tab == 'analisa' ? 'active' : ''; ?>" href="data-kuesioner.php?tab=analisa&f_tinggal=<?= $f_tinggal; ?>&f_sekolah=<?= urlencode($f_sekolah); ?>&f_beasiswa=<?= $f_beasiswa; ?>&f_jenis_b=<?= $f_jenis_b; ?>&f_memilih=<?= $f_memilih; ?>&f_mengetahui=<?= $f_mengetahui; ?>&f_minat=<?= $f_minat; ?>&f_organisasi=<?= $f_organisasi; ?>">📈 Analisa Marketing UBSI Tasik 2026</a>
        </li>
        <?php if($_SESSION['role'] == 'admin') : ?>
        <li class="nav-item">
            <a class="nav-link bg-warning-subtle text-warning-emphasis fw-bold" href="data-kuesioner.php?action=backup" onclick="return confirm('Apakah Anda yakin ingin mengunduh file cadangan UTUH seluruh isi tabel kuesioner (.zip) sekarang?')">📦 Backup Database (.zip)</a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="row g-3 mb-4 no-print">
        <div class="col-md-2">
            <div class="p-3 text-center total-badge shadow-sm d-flex flex-column justify-content-center h-100">
                <h6 class="text-uppercase fw-semibold mb-1" style="font-size: 11px;">Data Cocok</h6>
                <h2 class="fw-bold mb-0"><?= $total_rows; ?></h2>
                <small class="opacity-75" style="font-size: 10px;">Mhs Unik</small>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-secondary text-white py-1"><h6 class="mb-0 fw-bold small">🔍 Filter Parameter Dinamis Kuesioner</h6></div>
                <div class="card-body bg-white p-2">
                    <form method="GET" action="">
                        <input type="hidden" name="tab" value="<?= $tab; ?>">
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <select name="f_tinggal" class="form-select form-select-sm">
                                    <option value="">-- Tempat Tinggal --</option>
                                    <?php while($row = mysqli_fetch_assoc($opt_tinggal)) : ?><option value="<?= $row['tempat_tinggal']; ?>" <?= $f_tinggal == $row['tempat_tinggal']?'selected':''; ?>><?= $row['tempat_tinggal']; ?></option><?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3"><input type="text" name="f_sekolah" value="<?= htmlspecialchars($f_sekolah); ?>" class="form-control form-control-sm" placeholder="Ketik Asal Sekolah..."></div>
                            <div class="col-md-3">
                                <select name="f_beasiswa" class="form-select form-select-sm">
                                    <option value="">-- Beasiswa --</option>
                                    <?php while($row = mysqli_fetch_assoc($opt_beasiswa)) : ?><option value="<?= $row['beasiswa']; ?>" <?= $f_beasiswa == $row['beasiswa']?'selected':''; ?>><?= $row['beasiswa']; ?></option><?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="f_jenis_b" class="form-select form-select-sm">
                                    <option value="">-- Jenis Beasiswa --</option>
                                    <?php while($row = mysqli_fetch_assoc($opt_jenis_b)) : ?><option value="<?= $row['jenis_beasiswa']; ?>" <?= $f_jenis_b == $row['jenis_beasiswa']?'selected':''; ?>><?= $row['jenis_beasiswa']; ?></option><?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">Terapkan Saringan</button>
                            <a href="data-kuesioner.php?tab=<?= $tab; ?>" class="btn btn-light btn-sm border px-3">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($tab == 'crud'): ?>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Master Data Master Kuesioner (Bebas Redundansi NIM)</h5>
            <div class="d-flex gap-2 no-print">
                <button onclick="window.print()" class="btn btn-light btn-sm fw-bold px-3">🖨️ Cetak Rekap (PDF)</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle small mb-0" style="font-size: 11.5px;">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th><th>NIM</th><th>Nama</th><th>WhatsApp</th><th>Tempat Tinggal</th><th>Sekolah</th>
                        <th>Beasiswa</th><th>Jenis Beasiswa</th><th>Memilih UBSI</th><th>Mengetahui UBSI</th><th>Minat</th><th>Organisasi</th><th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; if($result && mysqli_num_rows($result) > 0) { while($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nim']); ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['whatsapp']); ?></td>
                        <td><?= htmlspecialchars($row['tempat_tinggal']); ?></td>
                        <td><?= htmlspecialchars($row['sekolah']); ?></td>
                        <td class="text-center"><span class="badge bg-<?= $row['beasiswa']=='Ya'?'success':'secondary'; ?>"><?= $row['beasiswa']; ?></span></td>
                        <td><?= $row['jenis_beasiswa'] ? htmlspecialchars($row['jenis_beasiswa']) : '-'; ?></td>
                        <td><?= htmlspecialchars($row['memilih_ubsi']); ?></td>
                        <td><?= htmlspecialchars($row['mengetahui_ubsi']); ?></td>
                        <td><?= htmlspecialchars($row['minat_kompetensi']); ?></td>
                        <td><?= htmlspecialchars($row['aktivitas_organisasi']); ?></td>
                        <td class="text-center no-print">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="edit.php?nama=<?php echo urlencode($row['nama']); ?>&nim=<?php echo urlencode($row['nim']); ?>" class="btn btn-warning btn-sm py-0 px-2" style="font-size: 11px;">Edit</a>
                                <?php if($_SESSION['role'] == 'admin') : ?>
                                    <a href="hapus.php?nama=<?php echo urlencode($row['nama']); ?>&nim=<?php echo urlencode($row['nim']); ?>" onclick="return confirm('Hapus permanen data responden NIM <?php echo htmlspecialchars($row['nim']); ?>?')" class="btn btn-danger btn-sm py-0 px-2" style="font-size: 11px;">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; } else { ?>
                        <tr><td colspan="13" class="text-center text-muted py-3">Tidak ditemukan data kuesioner terfilter yang cocok.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>


    <?php if ($tab == 'analisa'): ?>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3 chart-card">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">1. Tempat Tinggal</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartTinggal"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Pilihan Wilayah</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_tinggal)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_tinggal, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">2. Top 5 Asal Sekolah</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartSekolah"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Nama Sekolah</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_sekolah)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_sekolah, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">3. Status Beasiswa</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartBeasiswa"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Pilihan</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_beasiswa)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_beasiswa, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">4. Jenis Beasiswa</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartJenisB"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Skema Program</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_jenis_b)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_jenis_b, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card mt-3">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">5. Alasan Memilih UBSI</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartMemilih"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Variabel Alasan</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_memilih)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_memilih, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card mt-3">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">6. Mengetahui UBSI</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartMengetahui"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Sumber Kanal</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_mengetahui)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_mengetahui, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card mt-3">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">7. Minat & Kompetensi</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartMinat"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Sub-Sektor Minat</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_minat)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_minat, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3 chart-card mt-3">
            <div class="card shadow-sm border-0 p-3 bg-white text-center">
                <h6 class="fw-bold text-secondary small mb-2">8. Aktivitas Organisasi</h6>
                <div style="height: 130px; position: relative;"><canvas id="chartOrganisasi"></canvas></div>
                <div class="mt-3 table-responsive">
                    <table class="table table-sm table-bordered table-striped text-start mb-0 uraian-table">
                        <thead class="table-light"><tr><th>Grup Komunitas</th><th width="30%">Total</th></tr></thead>
                        <tbody>
                            <?php while($r = mysqli_fetch_assoc($chart_organisasi)) { ?>
                            <tr><td><?= htmlspecialchars($r['label']); ?></td><td class="fw-bold text-center"><?= $r['jumlah']; ?> Mhs</td></tr>
                            <?php } mysqli_data_seek($chart_organisasi, 0); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 kesimpulan-box p-4 mt-4 mb-3">
        <h5 class="fw-bold text-success mb-3">💡 EXECUTIVE SUMMARY & STRATEGIC MARKETING ANALYSIS (GEMINI AI REPORT)</h5>
        
        <div class="row g-3 border-bottom pb-3" style="font-size: 12.5px; line-height: 1.6; color: #212529;">
            <div class="col-md-6 border-end">
                <p class="mb-2"><strong>1. Analisis Profil Kewilayahan & Sekolah Input:</strong><br>
                Berdasarkan kompilasi grafik batasan di atas, peta demografi sebaran mahasiswa baru didominasi oleh pendaftar yang berdomisili di <u><b><?= htmlspecialchars($modus_tinggal); ?></b></u>, dengan penyerapan lulusan sekolah terpadat berasal dari <u><b><?= htmlspecialchars($modus_sekolah); ?></b></u>. Parameter wilayah ini direkomendasikan dikunci sebagai zona prioritas pertahanan marcom.</p>
                
                <p class="mb-0"><strong>2. Skema Insentif Pembiayaan & Pola Beasiswa:</strong><br>
                Status penerimaan pendanaan pendidikan menunjukkan kecenderungan terbesar mahasiswa memilih opsi beasiswa <u><b><?= htmlspecialchars($modus_beasiswa); ?></b></u>, dengan program penunjang yang paling diminati tertuju pada skema <u><b><?= htmlspecialchars($modus_jenis_b); ?></b></u>. Penawaran insentif ini terbukti efektif menjadi pemicu utama pendaftaran maba.</p>
            </div>
            <div class="col-md-6 ps-md-4">
                <p class="mb-2"><strong>3. Penentu Komparatif & Kanal Informasi Unggulan:</strong><br>
                Faktor penentu terbesar yang berhasil memikat ketertarikan mahasiswa memilih Universitas Bina Sarana Informatika bersandar pada alasan keunggulan <u><b><?= htmlspecialchars($modus_memilih); ?></b></u>. Sementara itu, jembatan publikasi informasi dengan daya penetrasi pesan paling efektif diraih oleh media <u><b><?= htmlspecialchars($modus_mengetahui); ?></b></u>.</p>
                
                <p class="mb-0"><strong>4. Pemetaan Bakat Praktis & Kelompok Komunitas:</strong><br>
                Karakteristik minat terbesar luaran pendaftar mengarah kuat pada sub-kompetensi digital kreatif yaitu <u><b><?= htmlspecialchars($modus_minat); ?></b></u>, yang berjalan selaras dengan keaktifan pada rumpun organisasi kelompok <u><b><?= htmlspecialchars($modus_organisasi); ?></b></u>. Kompilasi ini dapat dimanfaatkan pimpinan untuk merancang blueprint inkubasi bakat.</p>
            </div>
        </div>

        <div class="mt-3" style="font-size: 12.5px; line-height: 1.6; color: #212529;">
            <h6 class="fw-bold text-primary mb-2">🚀 STRATEGIC MARKETING BLUEPRINT (Rekomendasi Taktis)</h6>
            <div class="list-group list-group-flush small">
                <div class="list-group-item px-0 py-2 bg-transparent border-0">
                    🎯 <strong>Optimasi Saluran Informasi:</strong> Fokuskan alokasi anggaran marcom digital secara intensif pada kanal <strong>"<?= htmlspecialchars($modus_mengetahui); ?>"</strong> yang terbukti memiliki daya penetrasi tertinggi pada sebaran filter data ini.
                </div>
                <div class="list-group-item px-0 py-2 bg-transparent border-0">
                    ⚔️ <strong>Event Penetration Counter:</strong> Adakan kegiatan inkubasi/workshop terarah berbasis komunitas <strong>"<?= htmlspecialchars($modus_organisasi); ?>"</strong> dengan mengangkat tema keahlian praktis di bidang <strong>"<?= htmlspecialchars($modus_minat); ?>"</strong> langsung di area basis pertahanan utama <strong><?= htmlspecialchars($modus_tinggal); ?></strong> guna mengunci data registrasi siswa sebelum diklaim kompetitor.
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h6 class="fw-bold text-primary mb-2">📊 MATRIX ANALYSIS (SWOT)</h6>
            <div class="row g-2" style="font-size: 11.5px;">
                <div class="col-md-6">
                    <div class="p-2 bg-white rounded border-start border-success border-3 h-100 shadow-sm">
                        <strong class="text-success">💪 Strengths (Kekuatan)</strong><br>
                        Daya pikat program bersandar pada keunggulan variabel nilai utama <strong>"<?= htmlspecialchars($modus_memilih); ?>"</strong> yang efektif menarik animo pendaftar.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 bg-white rounded border-start border-info border-3 h-100 shadow-sm">
                        <strong class="text-info">🚀 Opportunities (Peluang)</strong><br>
                        Terdapat ceruk pasar potensial untuk mengonversi kegemaran siswa pada sub-sektor minat <strong>"<?= htmlspecialchars($modus_minat); ?>"</strong> menjadi angka pendaftar prodi rumpun IT terpadu.
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="text-end no-print mb-4">
        <button onclick="window.print()" class="btn btn-success btn-sm fw-bold px-4 shadow-sm">🖨️ Cetak Analisa Pemasaran (PDF)</button>
    </div>
    <?php endif; ?>

</div>

<?php if ($tab == 'analisa'): ?>
<script>
function createBarChart(canvasId, labelArray, dataArray, color) {
    new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: {
            labels: labelArray,
            datasets: [{ data: dataArray, backgroundColor: color, borderWidth: 0, borderRadius: 3 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } } },
                x: { ticks: { font: { size: 9 } } }
            }
        }
    });
}

createBarChart('chartTinggal', [<?php while($r = mysqli_fetch_assoc($chart_tinggal)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_tinggal, 0); while($r = mysqli_fetch_assoc($chart_tinggal)) echo $r['jumlah'].","; ?>], '#0d6efd');
createBarChart('chartSekolah', [<?php while($r = mysqli_fetch_assoc($chart_sekolah)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_sekolah, 0); while($r = mysqli_fetch_assoc($chart_sekolah)) echo $r['jumlah'].","; ?>], '#198754');
createBarChart('chartBeasiswa', [<?php while($r = mysqli_fetch_assoc($chart_beasiswa)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_beasiswa, 0); while($r = mysqli_fetch_assoc($chart_beasiswa)) echo $r['jumlah'].","; ?>], '#ffc107');
createBarChart('chartJenisB', [<?php while($r = mysqli_fetch_assoc($chart_jenis_b)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_jenis_b, 0); while($r = mysqli_fetch_assoc($chart_jenis_b)) echo $r['jumlah'].","; ?>], '#dc3545');
createBarChart('chartMemilih', [<?php while($r = mysqli_fetch_assoc($chart_memilih)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_memilih, 0); while($r = mysqli_fetch_assoc($chart_memilih)) echo $r['jumlah'].","; ?>], '#6f42c1');
createBarChart('chartMengetahui', [<?php while($r = mysqli_fetch_assoc($chart_mengetahui)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_mengetahui, 0); while($r = mysqli_fetch_assoc($chart_mengetahui)) echo $r['jumlah'].","; ?>], '#fd7e14');
createBarChart('chartMinat', [<?php while($r = mysqli_fetch_assoc($chart_minat)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_minat, 0); while($r = mysqli_fetch_assoc($chart_minat)) echo $r['jumlah'].","; ?>], '#20c997');
createBarChart('chartOrganisasi', [<?php while($r = mysqli_fetch_assoc($chart_organisasi)) echo "'".$r['label']."',"; ?>], [<?php mysqli_data_seek($chart_organisasi, 0); while($r = mysqli_fetch_assoc($chart_organisasi)) echo $r['jumlah'].","; ?>], '#0dcaf0');
</script>
<?php endif; ?>
</body>
</html>