<?php
include '../config.php';
if (!isset($_SESSION['role'])) { header("Location: ../login.php"); exit; }

if (!isset($_GET['nama']) || trim($_GET['nama']) == "") { header("Location: data-kuesioner.php"); exit; }
$nama_get = mysqli_real_escape_string($conn, $_GET['nama']);

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tabel_quisoner WHERE nama = '$nama_get'"));
if (!$data) { echo "<script>window.location='data-kuesioner.php';</script>"; exit; }

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $tempat_tinggal = mysqli_real_escape_string($conn, $_POST['tempat_tinggal']);
    $sekolah = mysqli_real_escape_string($conn, $_POST['sekolah']);
    $beasiswa = mysqli_real_escape_string($conn, $_POST['beasiswa']);
    $jenis_beasiswa = ($beasiswa == 'Ya') ? mysqli_real_escape_string($conn, $_POST['jenis_beasiswa']) : NULL;
    $memilih_ubsi = mysqli_real_escape_string($conn, $_POST['memilih_ubsi']);
    $mengetahui_ubsi = mysqli_real_escape_string($conn, $_POST['mengetahui_ubsi']);
    $minat_kompetensi = mysqli_real_escape_string($conn, $_POST['minat_kompetensi']);
    $aktivitas_organisasi = mysqli_real_escape_string($conn, $_POST['aktivitas_organisasi']);

    if ($jenis_beasiswa) {
        $query_update = "UPDATE tabel_quisoner SET nama='$nama', whatsapp='$whatsapp', tempat_tinggal='$tempat_tinggal', sekolah='$sekolah', beasiswa='$beasiswa', jenis_beasiswa='$jenis_beasiswa', memilih_ubsi='$memilih_ubsi', mengetahui_ubsi='$mengetahui_ubsi', minat_kompetensi='$minat_kompetensi', aktivitas_organisasi='$aktivitas_organisasi' WHERE nama='$nama_get'";
    } else {
        $query_update = "UPDATE tabel_quisoner SET nama='$nama', whatsapp='$whatsapp', tempat_tinggal='$tempat_tinggal', sekolah='$sekolah', beasiswa='$beasiswa', jenis_beasiswa=NULL, memilih_ubsi='$memilih_ubsi', mengetahui_ubsi='$mengetahui_ubsi', minat_kompetensi='$minat_kompetensi', aktivitas_organisasi='$aktivitas_organisasi' WHERE nama='$nama_get'";
    }

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='data-kuesioner.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Kuesioner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning py-3"><h5>📝 Form Edit Data Responden (Berdasarkan Nama)</h5></div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6"><label class="form-label">NIM</label><input type="text" name="nim" class="form-control bg-secondary-subtle text-muted" value="<?= htmlspecialchars($data['nim']); ?>" readonly></div>
                            <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required></div>
                        </div>
                        <div class="mb-3"><label class="form-label">No. WhatsApp</label><input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($data['whatsapp']); ?>" required></div>
                        <div class="mb-3">
                            <label class="form-label">Tempat Tinggal</label>
                            <select name="tempat_tinggal" class="form-select" required>
                                <option value="Kota Tasikmalaya" <?= $data['tempat_tinggal'] == 'Kota Tasikmalaya' ? 'selected' : ''; ?>>Kota Tasikmalaya</option>
                                <option value="Kabupaten Tasikmalaya" <?= $data['tempat_tinggal'] == 'Kabupaten Tasikmalaya' ? 'selected' : ''; ?>>Kabupaten Tasikmalaya</option>
                                <option value="Kabupaten Ciamis" <?= $data['tempat_tinggal'] == 'Kabupaten Ciamis' ? 'selected' : ''; ?>>Kabupaten Ciamis</option>
                                <option value="Kota Banjar" <?= $data['tempat_tinggal'] == 'Kota Banjar' ? 'selected' : ''; ?>>Kota Banjar</option>
                                <option value="Kota Garut" <?= $data['tempat_tinggal'] == 'Kota Garut' ? 'selected' : ''; ?>>Kota Garut</option>
                                <option value="Kabupaten Garut" <?= $data['tempat_tinggal'] == 'Kabupaten Garut' ? 'selected' : ''; ?>>Kabupaten Garut</option>
                                <option value="Kabupaten Pangandaran" <?= $data['tempat_tinggal'] == 'Kabupaten Pangandaran' ? 'selected' : ''; ?>>Kabupaten Pangandaran</option>
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label">Asal Sekolah</label><input type="text" name="sekolah" class="form-control" value="<?= htmlspecialchars($data['sekolah']); ?>" required></div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Menerima Beasiswa?</label>
                                <select name="beasiswa" id="beasiswa" class="form-select" required onchange="toggleBeasiswa(this.value)">
                                    <option value="Ya" <?= $data['beasiswa'] == 'Ya' ? 'selected' : ''; ?>>Ya</option>
                                    <option value="Tidak" <?= $data['beasiswa'] == 'Tidak' ? 'selected' : ''; ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="field_jenis_beasiswa" style="display: <?= $data['beasiswa'] == 'Ya' ? 'block' : 'none'; ?>;">
                                <label class="form-label">Jenis Beasiswa</label>
                                <select name="jenis_beasiswa" id="jenis_beasiswa" class="form-select">
                                    <option value="Beasiswa Jalur Undangan" <?= $data['jenis_beasiswa'] == 'Beasiswa Jalur Undangan' ? 'selected' : ''; ?>>Beasiswa Jalur Undangan</option>
                                    <option value="Beasiswa Talenta Digital" <?= $data['jenis_beasiswa'] == 'Beasiswa Talenta Digital' ? 'selected' : ''; ?>>Beasiswa Talenta Digital</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Memilih UBSI</label>
                            <select name="memilih_ubsi" class="form-select" required>
                                <option value="Biaya Terjangkau" <?= $data['memilih_ubsi'] == 'Biaya Terjangkau' ? 'selected' : ''; ?>>Biaya Terjangkau</option>
                                <option value="Beasiswa" <?= $data['memilih_ubsi'] == 'Beasiswa' ? 'selected' : ''; ?>>Beasiswa</option>
                                <option value="Dekat Dari Rumah" <?= $data['memilih_ubsi'] == 'Dekat Dari Rumah' ? 'selected' : ''; ?>>Dekat Dari Rumah</option>
                                <option value="Akreditasi Unggul" <?= $data['memilih_ubsi'] == 'Akreditasi Unggul' ? 'selected' : ''; ?>>Akreditasi Unggul</option>
                                <option value="Pilihan Orang Tua" <?= $data['memilih_ubsi'] == 'Pilihan Orang Tua' ? 'selected' : ''; ?>>Pilihan Orang Tua</option>
                                <option value="Rekomendasi Guru BK" <?= $data['memilih_ubsi'] == 'Rekomendasi Guru BK' ? 'selected' : ''; ?>>Rekomendasi Guru BK</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mengetahui UBSI Dari Mana?</label>
                            <select name="mengetahui_ubsi" class="form-select" required>
                                <option value="Guru BK" <?= $data['mengetahui_ubsi'] == 'Guru BK' ? 'selected' : ''; ?>>Guru BK</option>
                                <option value="Saudara" <?= $data['mengetahui_ubsi'] == 'Saudara' ? 'selected' : ''; ?>>Saudara</option>
                                <option value="Teman" <?= $data['mengetahui_ubsi'] == 'Teman' ? 'selected' : ''; ?>>Teman</option>
                                <option value="Orang Tua" <?= $data['mengetahui_ubsi'] == 'Orang Tua' ? 'selected' : ''; ?>>Orang Tua</option>
                                <option value="Mahasiswa UBSI" <?= $data['mengetahui_ubsi'] == 'Mahasiswa UBSI' ? 'selected' : ''; ?>>Mahasiswa UBSI</option>
                                <option value="Alumni UBSI" <?= $data['mengetahui_ubsi'] == 'Alumni UBSI' ? 'selected' : ''; ?>>Alumni UBSI</option>
                                <option value="Karyawan UBSI" <?= $data['mengetahui_ubsi'] == 'Karyawan UBSI' ? 'selected' : ''; ?>>Karyawan UBSI</option>
                                <option value="Instagram UBSI Tasikmalaya" <?= $data['mengetahui_ubsi'] == 'Instagram UBSI Tasikmalaya' ? 'selected' : ''; ?>>Instagram UBSI Tasikmalaya</option>
                                <option value="Tiktok UBSI Tasikmalaya" <?= $data['mengetahui_ubsi'] == 'Tiktok UBSI Tasikmalaya' ? 'selected' : ''; ?>>Tiktok UBSI Tasikmalaya</option>
                                <option value="Brosur" <?= $data['mengetahui_ubsi'] == 'Brosur' ? 'selected' : ''; ?>>Brosur</option>
                                <option value="Spanduk" <?= $data['mengetahui_ubsi'] == 'Spanduk' ? 'selected' : ''; ?>>Spanduk</option>
                                <option value="Baliho" <?= $data['mengetahui_ubsi'] == 'Baliho' ? 'selected' : ''; ?>>Baliho</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minat dan Kompetensi</label>
                            <select name="minat_kompetensi" class="form-select" required>
                                <option value="Editing Video" <?= $data['minat_kompetensi'] == 'Editing Video' ? 'selected' : ''; ?>>Editing Video</option>
                                <option value="Editing Photo" <?= $data['minat_kompetensi'] == 'Editing Photo' ? 'selected' : ''; ?>>Editing Photo</option>
                                <option value="Menulis Berita" <?= $data['minat_kompetensi'] == 'Menulis Berita' ? 'selected' : ''; ?>>Menulis Berita</option>
                                <option value="MC" <?= $data['minat_kompetensi'] == 'MC' ? 'selected' : ''; ?>>MC</option>
                                <option value="Live Streaming" <?= $data['minat_kompetensi'] == 'Live Streaming' ? 'selected' : ''; ?>>Live Streaming</option>
                                <option value="Influencer" <?= $data['minat_kompetensi'] == 'Influencer' ? 'selected' : ''; ?>>Influencer</option>
                                <option value="Selebgram" <?= $data['minat_kompetensi'] == 'Selebgram' ? 'selected' : ''; ?>>Selebgram</option>
                                <option value="Olah Raga" <?= $data['minat_kompetensi'] == 'Olah Raga' ? 'selected' : ''; ?>>Olah Raga</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Aktivitas Organisasi</label>
                            <select name="aktivitas_organisasi" class="form-select" required>
                                <option value="Keagamaan" <?= $data['aktivitas_organisasi'] == 'Keagamaan' ? 'selected' : ''; ?>>Keagamaan</option>
                                <option value="Pencinta Alam" <?= $data['aktivitas_organisasi'] == 'Pencinta Alam' ? 'selected' : ''; ?>>Pencinta Alam</option>
                                <option value="Musik" <?= $data['aktivitas_organisasi'] == 'Musik' ? 'selected' : ''; ?>>Musik</option>
                                <option value="Tari" <?= $data['aktivitas_organisasi'] == 'Tari' ? 'selected' : ''; ?>>Tari</option>
                                <option value="Bahasa" <?= $data['aktivitas_organisasi'] == 'Bahasa' ? 'selected' : ''; ?>>Bahasa</option>
                                <option value="OSIS" <?= $data['aktivitas_organisasi'] == 'OSIS' ? 'selected' : ''; ?>>OSIS</option>
                                <option value="Karang Taruna" <?= $data['aktivitas_organisasi'] == 'Karang Taruna' ? 'selected' : ''; ?>>Karang Taruna</option>
                                <option value="Komunitas Jepang" <?= $data['aktivitas_organisasi'] == 'Komunitas Jepang' ? 'selected' : ''; ?>>Komunitas Jepang</option>
                                <option value="Komunitas Korea" <?= $data['aktivitas_organisasi'] == 'Komunitas Korea' ? 'selected' : ''; ?>>Komunitas Korea</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="update" class="btn btn-warning w-100 fw-bold">Simpan Perubahan</button>
                            <a href="data-kuesioner.php" class="btn btn-secondary px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function toggleBeasiswa(val) {
    var field = document.getElementById('field_jenis_beasiswa');
    if (val === 'Ya') { field.style.display = 'block'; } else { field.style.display = 'none'; }
}
</script>
</body>
</html>