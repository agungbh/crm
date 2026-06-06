<?php
session_start();
// Proteksi Halaman Login
if (!isset($_SESSION['login'])) { 
    header("Location: ../login.php"); 
    exit; 
}
include '../config.php';

$pesan_alert = "";

// Fungsi pembantu untuk membuat huruf depan setiap kata menjadi Kapital (Capitalize Each Word)
function kapitalisasiKata($string) {
    return ucwords(strtolower(trim($string)));
}

// =========================================================================
// 🚀 PROSES ACTION 1: TAMBAH PESERTA BARU (VALIDASI ANTI-REDUNDANSI)
// =========================================================================
if (isset($_POST['tambah_peserta'])) {
    $nama_lengkap     = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap']));
    $no_whatsapp      = mysqli_real_escape_string($conn, trim($_POST['no_whatsapp']));
    $asal_sekolah     = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $kelas            = $_POST['kelas'];
    $rencana_kuliah   = $_POST['rencana_kuliah'];
    $tertarik_beasiswa= $_POST['tertarik_beasiswa'];
    $mau_dihubungi    = $_POST['mau_dihubungi'];

    $tertarik_jurusan = $_POST['tertarik_jurusan'];
    if ($tertarik_jurusan == 'Jurusan Lainnya' && !empty($_POST['jurusan_lainnya'])) {
        $tertarik_jurusan = mysqli_real_escape_string($conn, $_POST['jurusan_lainnya']);
    }

    $sumber_event = $_POST['sumber_event'];
    if ($sumber_event == 'Event Lainnya' && !empty($_POST['sumber_lainnya'])) {
        $sumber_event = mysqli_real_escape_string($conn, $_POST['sumber_lainnya']);
    }

    $cek_duplikat = mysqli_query($conn, "SELECT nama_lengkap, no_whatsapp FROM tabel_event WHERE nama_lengkap = '$nama_lengkap' AND no_whatsapp = '$no_whatsapp'");
    
    if (mysqli_num_rows($cek_duplikat) > 0) {
        $pesan_alert = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            ⚠️ <b>Data Gagal Disimpan!</b> Peserta dengan Nama: <b>{$nama_lengkap}</b> dan No Whatsapp: <b>{$no_whatsapp}</b> sudah terdaftar.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>";
    } else {
        $query_insert = "INSERT INTO tabel_event (nama_lengkap, no_whatsapp, asal_sekolah, kelas, tertarik_jurusan, rencana_kuliah, tertarik_beasiswa, mau_dihubungi, sumber_event, status_crm) 
                         VALUES ('$nama_lengkap', '$no_whatsapp', '$asal_sekolah', '$kelas', '$tertarik_jurusan', '$rencana_kuliah', '$tertarik_beasiswa', '$mau_dihubungi', '$sumber_event', 'Belum Diproses')";
        
        if (mysqli_query($conn, $query_insert)) {
            $pesan_alert = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                ✅ <b>Sukses!</b> Data peserta baru berhasil didaftarkan.
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                            </div>";
        } else {
            $pesan_alert = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Error: " . mysqli_error($conn) . "
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                            </div>";
        }
    }
}

// =========================================================================
// 🚀 PROSES ACTION 2: UPDATE QUICK PANEL CRM (INLINE SUBMIT)
// =========================================================================
if (isset($_POST['update_crm'])) {
    $id = $_POST['id_event'];
    $status = $_POST['status_crm'];
    $tanggal = !empty($_POST['tanggal_follow']) ? $_POST['tanggal_follow'] : NULL;
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    
    if (!empty($_POST['di_follow_up_oleh_input'])) {
        $user_follow = mysqli_real_escape_string($conn, $_POST['di_follow_up_oleh_input']);
    } else {
        $user_follow = $_SESSION['username'];
    }

    $res_lama = mysqli_query($conn, "SELECT upload_gambar FROM tabel_event WHERE id=$id");
    $data_lama = mysqli_fetch_assoc($res_lama);
    $nama_gambar = $data_lama['upload_gambar'];

    if (isset($_FILES['gambar_follow']) && $_FILES['gambar_follow']['error'] === 0) {
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'gif'];
        $x = explode('.', $_FILES['gambar_follow']['name']);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['gambar_follow']['size'];
        $file_tmp = $_FILES['gambar_follow']['tmp_name'];

        $nama_gambar = "IMG_" . time() . "_" . rand(1000, 9999) . "." . $ekstensi;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) && $ukuran < 2097152) {
            if (!is_dir('../uploads')) { mkdir('../uploads', 0777, true); }
            move_uploaded_file($file_tmp, '../uploads/' . $nama_gambar);
            if (!empty($data_lama['upload_gambar']) && file_exists('../uploads/' . $data_lama['upload_gambar'])) {
                unlink('../uploads/' . $data_lama['upload_gambar']);
            }
        }
    }

    $query_update = "UPDATE tabel_event SET status_crm = '$status', tanggal_follow = " . ($tanggal ? "'$tanggal'" : "NULL") . ", alasan = '$alasan', upload_gambar = " . ($nama_gambar ? "'$nama_gambar'" : "NULL") . ", di_follow_up_oleh = '$user_follow' WHERE id = $id";
    mysqli_query($conn, $query_update);
    header("Location: crm.php?" . $_SERVER['QUERY_STRING']);
    exit;
}

// --- LOGIKA FILTER DATA ---
$f_status  = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

$q = "SELECT * FROM tabel_event WHERE 1=1";
if ($f_status != '') {
    $q .= " AND status_crm = '$f_status'";
}
$q .= " ORDER BY id DESC"; 
$res = mysqli_query($conn, $q);

$status_options = ['Nolak', 'Ragu', 'Minat', 'Closing'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Manajemen CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-custom table {
            min-width: 1200px;
        }
        
        /* =========================================================================
           🎯 CONFIG PRINT LAYOUT: OTOMATIS A4 LANDSCAPE DAN SANGAT RAPI
           ========================================================================= */
        @media print {
            /* Pengaturan Kertas Ukuran A4 Posisi Landscape Miring */
            @page {
                size: A4 landscape;
                margin: 10mm 10mm 10mm 10mm;
            }
            
            /* Menyembunyikan elemen navigasi dan kontrol aksi interaktif */
            .navbar, .filter-wrapper-panel, .form-update-crm, .btn-aksi-admin,
            th.kolom-aksi, td.kolom-aksi {
                display: none !important;
            }
            
            /* Reset Layout Utama Agar Memenuhi Lembar A4 */
            body { 
                background-color: #ffffff !important; 
                color: #000000 !important;
                font-family: Arial, sans-serif !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .card { 
                border: none !important; 
                box-shadow: none !important; 
            }
            
            /* Optimasi Tabel Agar Tidak Terpotong di Tepi Kertas */
            .table-responsive-custom {
                overflow: visible !important;
            }
            .table-responsive-custom table { 
                min-width: 100% !important; 
                width: 100% !important;
                table-layout: fixed !important; 
                word-wrap: break-word !important;
            }
            
            /* Pewarnaan Khusus Cetak Browser Agar Tetap Muncul */
            .table {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .table-dark {
                background-color: #212529 !important;
                color: #ffffff !important;
            }
            
            /* Memaksa Teks Menjadi Kapital Huruf Depan Setiap Kata Saat Di-print */
            .text-capitalize-print {
                text-transform: capitalize !important;
            }
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-info" href="#">CRM UBSI Tasikmalaya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 <a class="nav-link active" href="dashboard.php">📊 Dashboard</a>
                <a class="nav-link" href="crm.php">📋 Manajemen CR</a>
                <a class="nav-link" href="hasil_crm.php">📈 Hasil & Analisis CRM</a>
                <a class="nav-link" href="generate.php?p=bju" target="_blank">📈 Laporan Data BJU</a>
                <a class="nav-link" href="data-kuesioner.php" target="_blank">📈 Laporan Empowering BJU</a>    
            </div>
            </ul>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="text-white me-lg-3">Login: <span class="badge bg-primary"><?= strtoupper($_SESSION['role']); ?></span></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid my-4 px-3">
    <?= $pesan_alert; ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start bg-white p-3 rounded shadow-sm gap-3 mb-3">
        <div class="text-center text-md-start">
            <h4 class="m-0 text-primary fw-bold">Halaman Manajemen CRM</h4>
            <p class="m-0 fw-bold text-success mt-1" style="font-size: 15px;">"Chat Dulu Closing Kemudian"</p>
            <p class="m-0 text-muted font-monospace" style="font-size: 12px;">Agung Baitul Hikmah - 2026</p>
            <p class="m-0 fw-extrabold text-danger mt-1" style="font-size: 13px; font-weight: 800; letter-spacing: 0.5px;">"SMALL IS BIG - GAS....POLLL"</p>
        </div>
        
        <form action="" method="GET" class="filter-wrapper-panel d-flex flex-wrap justify-content-center justify-content-md-end gap-2 m-0 align-items-center pt-md-2">
            <div class="input-group input-group-sm w-auto">
                <label class="input-group-text bg-secondary text-white fw-bold">Status Akhir</label>
                <select name="filter_status" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">-- Semua Status --</option>
                    <option value="Belum Diproses" <?= $f_status=='Belum Diproses'?'selected':''; ?>>Belum Diproses</option>
                    <?php foreach($status_options as $so): ?>
                        <option value="<?= $so; ?>" <?= $f_status==$so?'selected':''; ?>><?= $so; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-sm btn-primary px-3 fw-bold">Filter</button>
            
            <a href="export_pdf.php?filter_status=<?= $f_status; ?>" class="btn btn-sm btn-success px-2 fw-bold d-inline-flex align-items-center gap-1" title="Unduh format Excel/CSV">
                <i class="bi bi-file-earmark-excel-fill"></i> Cetak Excel
            </a>

            <button type="button" onclick="window.print();" class="btn btn-sm btn-danger px-2 fw-bold d-inline-flex align-items-center gap-1" title="Cetak ke Kertas A4 Landscape">
                <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
            </button>
            
            <button type="button" class="btn btn-sm btn-dark px-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                ➕ Tambah Calon CRM PMB UBSI TASIK 
            </button>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive-custom p-0">
            <table class="table border-1 table-bordered table-striped align-middle mb-0" style="font-size: 13px;">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th style="width: 180px;">Nama Lengkap</th>
                        <th style="width: 120px;">No Whatsapp</th>
                        <th style="width: 150px;">Asal Sekolah</th>
                        <th style="width: 90px;">Tgl Follow</th>
                        <th style="width: 90px;">Upload Gambar</th>
                        <th style="width: 110px;">Status Akhir</th>
                        <th>Alasan / Keterangan</th>
                        <th style="width: 130px;">Di Follow Up Oleh</th>
                        <th style="width: 310px;" class="form-update-crm kolom-aksi">Aksi Update Status</th>
                        <th style="width: 100px;" class="btn-aksi-admin kolom-aksi">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="text-capitalize-print">
                    <?php 
                    $no = 1; 
                    while($row = mysqli_fetch_assoc($res)): 
                        $bg_row = '';
                        if($row['status_crm'] == 'Closing') $bg_row = 'table-success';
                        elseif($row['status_crm'] == 'Minat') $bg_row = 'table-info';
                        elseif($row['status_crm'] == 'Ragu') $bg_row = 'table-warning';
                        elseif($row['status_crm'] == 'Nolak') $bg_row = 'table-danger';
                    ?>
                    <tr class="<?= $bg_row; ?>">
                        <td class="text-center"><?= $no++; ?></td>
                        
                        <td>
                            <b><?= htmlspecialchars(kapitalisasiKata($row['nama_lengkap'])); ?></b><br>
                            <small class="text-muted"><?= htmlspecialchars(kapitalisasiKata($row['sumber_event'])); ?></small>
                        </td>
                        
                        <td class="text-center">
                            <a href="https://wa.me/<?= $row['no_whatsapp']; ?>" target="_blank" class="btn btn-xs btn-outline-success p-1 fw-bold w-100" style="font-size: 11px;">🟢 <?= $row['no_whatsapp']; ?></a>
                        </td>
                        
                        <td><?= htmlspecialchars(kapitalisasiKata($row['asal_sekolah'])); ?> (<?= strtoupper($row['kelas']); ?>)</td>
                        
                        <td class="text-center"><?= $row['tanggal_follow'] ? date('d-m-Y', strtotime($row['tanggal_follow'])) : '-'; ?></td>
                        
                        <td class="text-center">
                            <?php if (!empty($row['upload_gambar']) && file_exists('../uploads/' . $row['upload_gambar'])): ?>
                                <a href="../uploads/<?= $row['upload_gambar']; ?>" target="_blank"><img src="../uploads/<?= $row['upload_gambar']; ?>" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></a>
                            <?php else: ?> <span class="text-muted">Tidak Ada</span> <?php endif; ?>
                        </td>
                        
                        <td class="text-center"><span class="badge bg-dark w-100"><?= htmlspecialchars(kapitalisasiKata($row['status_crm'])); ?></span></td>
                        
                        <td><?= htmlspecialchars(kapitalisasiKata($row['alasan'] ?? '-')); ?></td>
                        
                        <td class="text-center fw-bold text-secondary" style="font-size: 11px;">
                            <?= !empty($row['di_follow_up_oleh']) ? htmlspecialchars(kapitalisasiKata($row['di_follow_up_oleh'])) : 'Belum Diambil'; ?>
                        </td>
                        
                        <td class="bg-light p-2 form-update-crm kolom-aksi">
                            <form action="" method="POST" enctype="multipart/form-data" class="row g-1 m-0">
                                <input type="hidden" name="id_event" value="<?= $row['id']; ?>">
                                <div class="col-12 d-flex justify-content-between px-1 mb-1">
                                    <?php foreach($status_options as $opt): ?>
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="radio" name="status_crm" value="<?= $opt; ?>" id="st_<?= $opt . $row['id']; ?>" <?= $row['status_crm'] == $opt ? 'checked' : ''; ?> required>
                                            <label class="form-check-label fw-bold" style="font-size:11px;" for="st_<?= $opt . $row['id']; ?>"><?= $opt; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-6"><input type="date" name="tanggal_follow" class="form-control form-control-sm" value="<?= $row['tanggal_follow']; ?>"></div>
                                <div class="col-6"><input type="file" name="gambar_follow" class="form-control form-control-sm" accept="image/*"></div>
                                <div class="col-6"><input type="text" name="di_follow_up_oleh_input" class="form-control form-control-sm" placeholder="Nama Petugas..." value="<?= htmlspecialchars($row['di_follow_up_oleh'] ?? ''); ?>"></div>
                                <div class="col-6"><input type="text" name="alasan" class="form-control form-control-sm" placeholder="Catatan/Alasan..." value="<?= htmlspecialchars($row['alasan'] ?? ''); ?>"></div>
                                <div class="col-12 mt-1"><button type="submit" name="update_crm" class="btn btn-sm btn-success w-100 py-1 fw-bold" style="font-size:11px;">💾 Simpan Status</button></div>
                            </form>
                        </td>
                        
                        <td class="text-center btn-aksi-admin kolom-aksi">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm p-1 fw-bold" style="font-size:11px;">Edit</a>
                                <?php if($_SESSION['role'] == 'admin'): ?>
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm p-1 fw-bold" style="font-size:11px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data peserta ini?')">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; if(mysqli_num_rows($res) == 0): ?>
                        <tr><td colspan="11" class="text-center py-3 text-muted fw-bold">Tidak ada data pendaftar dengan status akhir terpilih.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPeserta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title fw-bold">➕ Tambah Peserta Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3" style="font-size: 13px;">
                <form action="" method="POST">
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">No Whatsapp</label>
                        <input type="number" name="no_whatsapp" class="form-control form-control-sm" placeholder="Contoh: 08xxxxxxxx" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">Kelas</label>
                        <select name="kelas" class="form-select form-select-sm" required>
                            <option value="X">X</option><option value="XI">XI</option><option value="XII">XII</option><option value="Sudah Lulus">Sudah Lulus</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">Tertarik Pada Jurusan</label>
                        <select name="tertarik_jurusan" class="form-select form-select-sm" onchange="document.getElementById('div_j_lain').classList.toggle('d-none', this.value != 'Jurusan Lainnya')" required>
                            <option value="Manajemen">Manajemen</option><option value="Akuntansi">Akuntansi</option><option value="Informatika">Informatika</option><option value="Ilmu Komunikasi">Ilmu Komunikasi</option><option value="Pariwisata">Pariwisata</option><option value="Jurusan Lainnya">Jurusan Lainnya (Isi Manual)</option>
                        </select>
                        <input type="text" name="jurusan_lainnya" id="div_j_lain" class="form-control form-control-sm mt-1 d-none" placeholder="Ketik nama jurusan manual...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold mb-0">Rencana Kuliah</label>
                        <select name="rencana_kuliah" class="form-select form-select-sm" required>
                            <option value="Tahun Ini">Tahun Ini</option><option value="Tahun Depan">Tahun Depan</option><option value="Mencari Info">Mencari Info</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-bold mb-0">Tertarik Beasiswa?</label>
                            <select name="tertarik_beasiswa" class="form-select form-select-sm"><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold mb-0">Mau Dihubungi?</label>
                            <select name="mau_dihubungi" class="form-select form-select-sm"><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-0">Sumber Event</label>
                        <select name="sumber_event" class="form-select form-select-sm" onchange="document.getElementById('div_s_lain').classList.toggle('d-none', this.value != 'Event Lainnya')" required>
                            <option value="Seminar Digital Kreatif">Seminar Digital Kreatif</option><option value="Workshop Digital Kreatif">Workshop Digital Kreatif</option><option value="Event Lainnya">Event Lainnya (Isi Manual)</option>
                        </select>
                        <input type="text" name="sumber_lainnya" id="div_s_lain" class="form-control form-control-sm mt-1 d-none" placeholder="Ketik nama event manual...">
                    </div>
                    <div class="text-end pt-2 border-top">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_peserta" class="btn btn-sm btn-success fw-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>