<?php
session_start();
if (!isset($_SESSION['login'])) { 
    header("Location: ../login.php"); 
    exit; 
}
include '../config.php';

$id = $_GET['id'];
$r = mysqli_query($conn, "SELECT * FROM tabel_event WHERE id=$id"); 
$row = mysqli_fetch_assoc($r);

if (isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_lengkap']); 
    $wa       = $_POST['no_whatsapp']; 
    $sekolah  = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $kelas    = $_POST['kelas']; 
    $jurusan  = mysqli_real_escape_string($conn, $_POST['tertarik_jurusan']); 
    $rencana  = $_POST['rencana_kuliah'];
    $beasiswa = $_POST['tertarik_beasiswa']; 
    $hubungi  = $_POST['mau_dihubungi']; 
    $sumber   = mysqli_real_escape_string($conn, $_POST['sumber_event']);
    $status   = $_POST['status_crm']; 
    $alasan   = mysqli_real_escape_string($conn, $_POST['alasan']);

    mysqli_query($conn, "UPDATE tabel_event SET 
        nama_lengkap='$nama', no_whatsapp='$wa', asal_sekolah='$sekolah', 
        kelas='$kelas', tertarik_jurusan='$jurusan', rencana_kuliah='$rencana', 
        tertarik_beasiswa='$beasiswa', mau_dihubungi='$hubungi', sumber_event='$sumber', 
        status_crm='$status', alasan='$alasan' 
        WHERE id=$id");

    // REVISI: Tetap berada di halaman crm.php setelah simpan
    header("Location: crm.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Edit Data Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 550px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning fw-bold">📝 Form Edit Data Peserta & CRM</div>
        <div class="card-body" style="font-size: 13px;">
            <form action="" method="POST">
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control form-control-sm" value="<?= htmlspecialchars($row['nama_lengkap']); ?>" required></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">No Whatsapp</label><input type="number" name="no_whatsapp" class="form-control form-control-sm" value="<?= $row['no_whatsapp']; ?>" required></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Asal Sekolah</label><input type="text" name="asal_sekolah" class="form-control form-control-sm" value="<?= htmlspecialchars($row['asal_sekolah']); ?>" required></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Kelas</label><select name="kelas" class="form-select form-select-sm"><option value="X" <?=$row['kelas']=='X'?'selected':''?>>X</option><option value="XI" <?=$row['kelas']=='XI'?'selected':''?>>XI</option><option value="XII" <?=$row['kelas']=='XII'?'selected':''?>>XII</option><option value="Sudah Lulus" <?=$row['kelas']=='Sudah Lulus'?'selected':''?>>Sudah Lulus</option></select></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Jurusan</label><input type="text" name="tertarik_jurusan" class="form-control form-control-sm" value="<?= htmlspecialchars($row['tertarik_jurusan']); ?>" required></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Rencana Kuliah</label><select name="rencana_kuliah" class="form-select form-select-sm"><option value="Tahun Ini" <?=$row['rencana_kuliah']=='Tahun Ini'?'selected':''?>>Tahun Ini</option><option value="Tahun Depan" <?=$row['rencana_kuliah']=='Tahun Depan'?'selected':''?>>Tahun Depan</option><option value="Mencari Info" <?=$row['rencana_kuliah']=='Mencari Info'?'selected':''?>>Mencari Info</option></select></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Sumber Event</label><input type="text" name="sumber_event" class="form-control form-control-sm" value="<?= htmlspecialchars($row['sumber_event']); ?>" required></div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Beasiswa</label><br><input type="radio" name="tertarik_beasiswa" value="Ya" <?=$row['tertarik_beasiswa']=='Ya'?'checked':''?>> Ya &nbsp; <input type="radio" name="tertarik_beasiswa" value="Tidak" <?=$row['tertarik_beasiswa']=='Tidak'?'checked':''?>> Tidak</div>
                <div class="mb-2"><label class="form-label mb-0 fw-bold">Dihubungi Admin?</label><br><input type="radio" name="mau_dihubungi" value="Ya" <?=$row['mau_dihubungi']=='Ya'?'checked':''?>> Ya &nbsp; <input type="radio" name="mau_dihubungi" value="Tidak" <?=$row['mau_dihubungi']=='Tidak'?'checked':''?>> Tidak</div>
                <hr>
                <div class="mb-2"><label class="form-label fw-bold mb-0">Status CRM</label><select name="status_crm" class="form-select form-select-sm"><option value="Belum Diproses" <?=$row['status_crm']=='Belum Diproses'?'selected':''?>>Belum Diproses</option><option value="Nolak" <?=$row['status_crm']=='Nolak'?'selected':''?>>Nolak</option><option value="Ragu" <?=$row['status_crm']=='Ragu'?'selected':''?>>Ragu</option><option value="Minat" <?=$row['status_crm']=='Minat'?'selected':''?>>Minat</option><option value="Closing" <?=$row['status_crm']=='Closing'?'selected':''?>>Closing</option></select></div>
                <div class="mb-3"><label class="form-label fw-bold mb-0">Alasan / Catatan</label><textarea name="alasan" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($row['alasan']??''); ?></textarea></div>
                
                <button type="submit" name="update" class="btn btn-sm btn-warning w-100 fw-bold">💾 Simpan Perubahan</button>
                <a href="crm.php" class="btn btn-sm btn-secondary w-100 mt-1">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>