<?php
session_start();
// Proteksi Halaman Login
if (!isset($_SESSION['login'])) { 
    header("Location: ../login.php"); 
    exit; 
}
include '../config.php';

// Fungsi pembantu untuk membuat huruf depan setiap kata menjadi Kapital
function kapitalisasiKata($string) {
    return ucwords(strtolower(trim($string)));
}

// =========================================================================
// 🚀 PROSES ACTION: BACKUP DATABASE TABEL_EVENT DAN SELURUH GAMBAR (ZIP)
// =========================================================================
if (isset($_POST['backup_data'])) {
    if (!class_exists('ZipArchive')) {
        die("<script>alert('Ekstensi ZipArchive PHP Anda mati! Silakan aktifkan di php.ini'); window.location.href='hasil_crm.php';</script>");
    }

    $zip = new ZipArchive();
    $zip_filename = "Backup_CRM_UBSI_" . date('Ymd_His') . ".zip";

    if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        
        // --- Langkah A: Generate Struktur & Data SQL dari tabel_event ---
        $sql_content = "-- Backup tabel_event CRM UBSI Tasikmalaya\n";
        $sql_content .= "-- Di-generate pada: " . date('Y-m-d H:i:s') . "\n\n";
        $sql_content .= "DROP TABLE IF EXISTS tabel_event;\n";
        
        $res_create = mysqli_query($conn, "SHOW CREATE TABLE tabel_event");
        $row_create = mysqli_fetch_assoc($res_create);
        $sql_content .= $row_create['Create Table'] . ";\n\n";

        $res_data = mysqli_query($conn, "SELECT * FROM tabel_event");
        while ($row_data = mysqli_fetch_assoc($res_data)) {
            $fields = array_map(function($val) use ($conn) {
                if ($val === null) return "NULL";
                return "'" . mysqli_real_escape_string($conn, $val) . "'";
            }, $row_data);
            
            $sql_content .= "INSERT INTO tabel_event VALUES (" . implode(", ", $fields) . ");\n";
        }

        $zip->addFromString("database_backup.sql", $sql_content);

        // --- Langkah B: Memasukkan Seluruh File Gambar ke dalam ZIP ---
        $dir_uploads = '../uploads/';
        if (is_dir($dir_uploads)) {
            $files_images = scandir($dir_uploads);
            foreach ($files_images as $file) {
                if ($file != '.' && $file != '..') {
                    $file_path = $dir_uploads . $file;
                    if (is_file($file_path)) {
                        $zip->addFile($file_path, "uploads/" . $file);
                    }
                }
            }
        }

        $zip->close();

        // --- Langkah C: Kirim file ZIP ke browser ---
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=" . $zip_filename);
        header("Content-Length: " . filesize($zip_filename));
        readfile($zip_filename);
        
        unlink($zip_filename);
        exit;
    } else {
        die("<script>alert('Gagal membuat file ZIP cadangan!'); window.location.href='hasil_crm.php';</script>");
    }
}

// --- 2. KONDISI GRAFIK & RESUME PERSENTASE ---
$status_counts = ['Nolak' => 0, 'Ragu' => 0, 'Minat' => 0, 'Closing' => 0];
$total_data = 0;

$query_status = mysqli_query($conn, "SELECT status_crm, COUNT(*) as jumlah FROM tabel_event WHERE status_crm IN ('Nolak', 'Ragu', 'Minat', 'Closing') GROUP BY status_crm");
while ($row = mysqli_fetch_assoc($query_status)) {
    $status_counts[$row['status_crm']] = (int)$row['jumlah'];
    $total_data += (int)$row['jumlah'];
}

// --- 3. QUERY TABEL DETAIL PESERTA ---
$query_tabel = mysqli_query($conn, "SELECT nama_lengkap, no_whatsapp, tanggal_follow, status_crm, alasan FROM tabel_event WHERE status_crm IN ('Nolak', 'Ragu', 'Minat', 'Closing') ORDER BY id DESC");

// --- 4. ENGINE ANALISIS CRM - INTEGRASI NAMA LENGKAP PADA TOTAL PESAN WA ---
function dapatkanRekomendasiCRM($status, $alasan, $no_wa, $nama_raw) {
    $alasan_lc = strtolower(trim($alasan));
    $alasan_bersih = !empty($alasan) ? htmlspecialchars(ucwords(strtolower(trim($alasan)))) : '';
    $nama_prospek = !empty($nama_raw) ? htmlspecialchars(ucwords(strtolower(trim($nama_raw)))) : 'Kak';
    $pesan_wa = "";
    $label_kategori = "";

    // Bersihkan nomor WhatsApp agar siap kirim via API Link
    $wa_tujuan = preg_replace('/[^0-9]/', '', $no_wa);
    if (substr($wa_tujuan, 0, 1) === '0') {
        $wa_tujuan = '62' . substr($wa_tujuan, 1);
    }

    // A. DETEKSI BEASISWA / LOA
    if (strpos($alasan_lc, 'beasiswa') !== false || strpos($alasan_lc, 'loa') !== false) {
        $label_kategori = "Beasiswa & LoA";
        $pesan_wa = "Halo {$nama_prospek}! Mau ngingetin buat berkas \"{$alasan_bersih}\" kemarin ditunggu paling lambat sampai 20 Juni 2026 yaa. Sayang banget kalau hangus, soalnya UBSI udah Akreditasi UNGGUL, Bebas Uang Gedung, dan biayanya bisa dicicil banget. Plus nanti dibantu cari kerjaan lewat Career Center. Yuk, langsung beresin berkasnya di www.pmbubsi.id!";
    }
    // B. DETEKSI BIAYA / EKONOMI / MAHAL / DANA
    elseif (strpos($alasan_lc, 'biaya') !== false || strpos($alasan_lc, 'mahal') !== false || strpos($alasan_lc, 'uang') !== false || strpos($alasan_lc, 'dana') !== false || strpos($alasan_lc, 'ekonomi') !== false) {
        $label_kategori = "Solusi Finansial";
        $pesan_wa = "Halo {$nama_prospek}! Kemarin sempat cerita soal kendala \"{$alasan_bersih}\" ya? Gak usah khawatir Kak, di UBSI itu 100% Bebas Uang Gedung dan kuliahnya bisa dicicil bulanan kok, jadi aman gak bakal berat di awal. Kampus kita juga udah Terakreditasi UNGGUL dan ada fasilitas Career Center yang bakal ngejembatanin langsung kerja ke perusahaan mitra. Dicoba daftar dulu aja yuk di www.pmbubsi.id!";
    } 
    // C. DETEKSI ORANG TUA / RESTU
    elseif (strpos($alasan_lc, 'ortu') !== false || strpos($alasan_lc, 'orang tua') !== false || strpos($alasan_lc, 'keluarga') !== false || strpos($alasan_lc, 'restu') !== false) {
        $label_kategori = "Solusi Restu Ortu";
        $pesan_wa = "Halo {$nama_prospek}! Gimana obrolan sama orang tua kemarin soal kendala \"{$alasan_bersih}\"? Nanti coba sampein ke ayah atau ibu yaa, kalau kuliah di UBSI itu udah resmi Terakreditasi UNGGUL, 100% Bebas Uang Gedung, dan bisa dicicil bulanan. Terus lulusannya langsung disalurkan kerja via Career Center. Biar ortu makin tenang, yuk kita isi data awalnya dulu di www.pmbubsi.id";
    }
    // D. DETEKSI JARAK JAUH / LOKASI / ONGKOS
    elseif (strpos($alasan_lc, 'jauh') !== false || strpos($alasan_lc, 'lokasi') !== false || strpos($alasan_lc, 'luar kota') !== false || strpos($alasan_lc, 'ongkos') !== false) {
        $label_kategori = "Solusi Jarak";
        $pesan_wa = "Halo {$nama_prospek}! Menanggapi soal pertimbangan kemarin yang \"{$alasan_bersih}\", tenang aja yaa. Di UBSI ada sistem kuliah Blended Learning (bisa kuliah online dari rumah), jadi super hemat ongkos jalan. Padahal kampusnya udah Terakreditasi UNGGUL, Bebas Uang Gedung, bisa dicicil, dan dapet jaminan jembatan kerja lewat Career Center. Yuk, daftar lewat HP aja di www.pmbubsi.id!";
    }
    // E. DETEKSI MINAT JURUSAN / PRODI
    elseif (strpos($alasan_lc, 'jurusan') !== false || strpos($alasan_lc, 'minat') !== false || strpos($alasan_lc, 'prodi') !== false) {
        $label_kategori = "Solusi Pilihan Prodi";
        $pesan_wa = "Halo {$nama_prospek}! Masih galau ya soal kendala \"{$alasan_bersih}\" kemarin? 😉 Sekadar masukan nih, jurusan IT di UBSI yang udah Terakreditasi UNGGUL ini peluang kerjanya lagi luas banget di industri digital, apalagi kita punya Career Center sendiri buat nyalurin kerja. Mumpung lagi ada promo Bebas Uang Gedung & biaya bisa dicicil, kita amankan slot kuotanya dulu yuk di www.pmbubsi.id. Pilihan prodinya masih bisa kita ganti santai kok nanti!";
    }
    // F. DEFAULT STATUS CLOSING
    elseif ($status == 'Closing') {
        $label_kategori = "Retention Maba";
        $pesan_wa = "Selamat ya {$nama_prospek} udah resmi gabung di UBSI Tasikmalaya (Kampus Terakreditasi UNGGUL)! ✨ Nanti lulus tinggal manfaatin fasilitas Career Center buat dijembatanin langsung kerja ke perusahaan rekanan. Kalau ada temen sekolah yang mau barengan dapet promo Bebas Uang Gedung & kuliah bisa dicicil, ajak daftar di www.pmbubsi.id yaa!";
    }
    // G. DEFAULT STATUS MINAT
    elseif ($status == 'Minat') {
        $label_kategori = "Urgency Push";
        $pesan_wa = "Halo {$nama_prospek}! Info aja nih buat slot promo Bebas Uang Gedung di UBSI gelombang ini sisa dikit banget di sistem. Kuliahnya udah Terakreditasi UNGGUL, biaya bisa dicicil, dan ada Career Center buat jembatan kerja pas lulus nanti. Yuk, mumpung masih kebagian, langsung amanin slotnya siang ini di www.pmbubsi.id!";
    }
    // H. DEFAULT STATUS RAGU
    elseif ($status == 'Ragu') {
        $label_kategori = "Nurturing Ragu";
        $pesan_wa = "Halo {$nama_prospek}! Cuma mau nyapa sekaligus ngingetin kalau minggu ini kuota promo Bebas Uang Gedung di UBSI udah mau penuh. Kampus kita udah Terakreditasi UNGGUL, biaya bisa dicicil bulanan, plus didukung penuh sama Career Center buat penyaluran kerja. Biar gak kelewatan, kunci slot pendaftarannya dulu yuk di www.pmbubsi.id! 😊";
    }
    // I. DEFAULT STATUS NOLAK (COLD LEADS)
    else {
        $label_kategori = "Retargeting Silaturahmi";
        $pesan_wa = "Halo {$nama_prospek}! Makasih banyak ya atas waktunya kemarin. Akun pendaftaranmu di UBSI (Kampus Terakreditasi UNGGUL) tetep aku simpen aktif yaa. Siapa tahu ke depannya Kakak berubah pikiran dan mau manfaatin promo kuliah Bebas Uang Gedung, cicilan ringan, atau layanan jembatan kerja Career Center kami. Tinggal akses www.pmbubsi.id. Sukses selalu yaa!";
    }

    $url_wa_encoded = "https://api.whatsapp.com/send?phone={$wa_tujuan}&text=" . urlencode($pesan_wa);

    return "<p class='mb-0'><b>{$label_kategori}:</b></p>
            <textarea class='form-control form-control-sm mt-1 bg-warning bg-opacity-10 border-warning text-dark fw-medium' 
                      rows='2' readonly style='font-size: 12px; cursor: pointer; resize: none;' 
                      onclick='this.focus();this.select();' title='Klik untuk block semua teks'>{$pesan_wa}</textarea>
            <a href='{$url_wa_encoded}' target='_blank' class='btn btn-success btn-sm w-100 mt-1 fw-bold p-1' style='font-size: 11px;'>
                Kirim via WA 🚀
            </a>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Hasil CRM - UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container-wrapper {
            position: relative;
            width: 100%;
            height: 260px;
        }
        @media (max-width: 576px) {
            .chart-container-wrapper {
                height: 220px;
            }
        }
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-custom table {
            min-width: 1200px;
        }
        .text-capitalize-custom {
            text-transform: capitalize;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-info" href="#">CRM UBSI Tasikmalaya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <a class="nav-link active" href="dashboard.php">📊 Dashboard</a>
                <a class="nav-link" href="crm.php">📋 Manajemen CR</a>
                <a class="nav-link" href="hasil_crm.php">📈 Hasil & Analisis CRM</a>
                <a class="nav-link" href="generate.php?p=bju" target="_blank">📈 Laporan Data BJU</a>
                <a class="nav-link" href="data-kuesioner.php" target="_blank">📈 Laporan Empowering BJU</a>    
            </ul>
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-lg-0">
                <span class="text-white me-lg-3">Login: <span class="badge bg-primary"><?= strtoupper($_SESSION['role']); ?></span></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid my-4 px-3 px-md-4">
    <div class="row align-items-center g-3 mb-4 bg-white p-3 rounded shadow-sm mx-0">
        <div class="col-12 col-md-8 text-center text-md-start">
            <h4 class="fw-bold text-dark mb-1">📈 Grafik & Hasil Rekomendasi Taktis CRM</h4>
            <p class="text-muted mb-0 small">Analisis persentase konversi data serta saran tindak lanjut khusus berbasis kendala pendaftar.</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <form action="" method="POST" class="m-0">
                <button type="submit" name="backup_data" class="btn btn-success fw-bold shadow-sm w-100 w-md-auto py-2 px-3" onclick="return confirm('Sistem akan mengekstrak tabel_event beserta seluruh lampiran gambar ke dalam file ZIP. Lanjutkan backup?')">
                    📦 Backup Data (.ZIP)
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">📊 Diagram Lingkaran Status CRM</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3">
                    <div class="chart-container-wrapper">
                        <canvas id="chartStatusPie"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">🔢 Resume Komposisi Persentase</h6>
                </div>
                <div class="card-body p-3">
                    <p class="fw-bold text-secondary text-center text-xl-start mb-3">Total Responden: <span class="badge bg-dark fs-6"><?= $total_data; ?> Calon Mahasiswa</span></p>
                    <div class="row g-2 g-md-3">
                        <?php 
                        $badges = ['Nolak' => 'danger', 'Ragu' => 'warning', 'Minat' => 'info', 'Closing' => 'success'];
                        foreach ($status_counts as $status => $jumlah): 
                            $persen = $total_data > 0 ? round(($jumlah / $total_data) * 100, 1) : 0;
                        ?>
                            <div class="col-12 col-sm-6">
                                <div class="p-3 rounded bg-light border-start border-4 border-<?= $badges[$status]; ?> shadow-sm">
                                    <small class="text-muted fw-bold d-block text-uppercase" style="font-size:11px;"><?= $status; ?></small>
                                    <div class="d-flex align-items-baseline justify-content-between">
                                        <span class="fs-4 fw-bold text-dark"><?= $persen; ?>%</span>
                                        <small class="text-secondary">(<?= $jumlah; ?> siswa)</small>
                                    </div>
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar bg-<?= $badges[$status]; ?>" role="progressbar" style="width: <?= $persen; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="m-0 fw-bold">🤖 Tabel Deteksi Kendala & Rekomendasi Chat Follow Up</h6>
        </div>
        <div class="card-body p-0 table-responsive-custom">
            <table class="table table-bordered table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="table-secondary text-center">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 170px;">Nama Lengkap</th>
                        <th style="width: 120px;">No Whatsapp</th>
                        <th style="width: 100px;">Tgl Follow</th>
                        <th style="width: 100px;">Status Akhir</th>
                        <th style="width: 180px;">Alasan / Keterangan Masalah</th>
                        <th style="background-color: #e8f5e9; color: #1b5e20;">💡 Hasil Analisis & Teks Siap Kirim WA (Integrasi Link)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while ($row_tab = mysqli_fetch_assoc($query_tabel)): 
                        $lbl_cls = $badges[$row_tab['status_crm']];
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td class="text-capitalize-custom"><b><?= htmlspecialchars(kapitalisasiKata($row_tab['nama_lengkap'])); ?></b></td>
                        <td class="text-center">
                            <a href="https://wa.me/<?= $row_tab['no_whatsapp']; ?>" target="_blank" class="btn btn-xs btn-outline-success p-1 fw-bold w-100" style="font-size:11px;">🟢 <?= $row_tab['no_whatsapp']; ?></a>
                        </td>
                        <td class="text-center"><?= $row_tab['tanggal_follow'] ? date('d-m-Y', strtotime($row_tab['tanggal_follow'])) : '<span class="text-muted">-</span>'; ?></td>
                        <td class="text-center"><span class="badge bg-<?= $lbl_cls; ?> w-100"><?= $row_tab['status_crm']; ?></span></td>
                        <td><?= !empty($row_tab['alasan']) ? htmlspecialchars($row_tab['alasan']) : '<i class="text-muted">Tidak ada catatan harian</i>'; ?></td>
                        <td class="p-2 bg-light border-start border-3 border-success">
                            <?= dapatkanRekomendasiCRM($row_tab['status_crm'], $row_tab['alasan'], $row_tab['no_whatsapp'], $row_tab['nama_lengkap']); ?>
                        </td>
                    </tr>
                    <?php endwhile; if(mysqli_num_rows($query_tabel) == 0): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4 fw-bold">Belum ada data pendaftar dengan status akhir [Nolak, Ragu, Minat, Closing].</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('chartStatusPie').getContext('2d');
const chartStatusPie = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Nolak', 'Ragu', 'Minat', 'Closing'],
        datasets: [{
            data: [
                <?= $status_counts['Nolak']; ?>, 
                <?= $status_counts['Ragu']; ?>, 
                <?= $status_counts['Minat']; ?>, 
                <?= $status_counts['Closing']; ?>
            ],
            backgroundColor: ['#dc3545', '#ffc107', '#0dcaf0', '#198754'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { 
                position: window.innerWidth < 768 ? 'bottom' : 'right',
                labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } }
            } 
        }
    }
});

window.addEventListener('resize', () => {
    chartStatusPie.options.plugins.legend.position = window.innerWidth < 768 ? 'bottom' : 'right';
    chartStatusPie.update();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>