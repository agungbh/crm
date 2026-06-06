<?php
 include '../config.php';

if (isset($_POST['submit'])) {
    $nim = mysqli_real_escape_string($conn, trim($_POST['nim']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $whatsapp = mysqli_real_escape_string($conn, trim($_POST['whatsapp']));
    $tempat_tinggal = mysqli_real_escape_string($conn, $_POST['tempat_tinggal']);
    $sekolah = mysqli_real_escape_string($conn, trim($_POST['sekolah']));
    $beasiswa = mysqli_real_escape_string($conn, $_POST['beasiswa']);
    $jenis_beasiswa = ($beasiswa == 'Ya') ? mysqli_real_escape_string($conn, $_POST['jenis_beasiswa']) : NULL;
    $memilih_ubsi = mysqli_real_escape_string($conn, $_POST['memilih_ubsi']);
    $mengetahui_ubsi = mysqli_real_escape_string($conn, $_POST['mengetahui_ubsi']);
    $minat_kompetensi = mysqli_real_escape_string($conn, $_POST['minat_kompetensi']);
    $aktivitas_organisasi = mysqli_real_escape_string($conn, $_POST['aktivitas_organisasi']);

    /**
     * KETENTUAN 7: VALIDASI DATA NIM DAN NAMA ANTI-DUPLIKAT
     * Memeriksa apakah kombinasi NIM dan Nama yang sama sudah pernah mengisi kuesioner
     */
    $check_duplicate = mysqli_query($conn, "SELECT id FROM tabel_quisoner WHERE nim = '$nim' AND nama = '$nama'");
    
    if (mysqli_num_rows($check_duplicate) > 0) {
        echo "<script>alert('Mohon maaf nim dan nama sudah ada'); window.history.back();</script>";
    } else {
        // Eksekusi Simpan Data jika lolos validasi
        if ($jenis_beasiswa) {
            $query = "INSERT INTO tabel_quisoner (nim, nama, whatsapp, tempat_tinggal, sekolah, beasiswa, jenis_beasiswa, memilih_ubsi, mengetahui_ubsi, minat_kompetensi, aktivitas_organisasi) 
                      VALUES ('$nim', '$nama', '$whatsapp', '$tempat_tinggal', '$sekolah', '$beasiswa', '$jenis_beasiswa', '$memilih_ubsi', '$mengetahui_ubsi', '$minat_kompetensi', '$aktivitas_organisasi')";
        } else {
            $query = "INSERT INTO tabel_quisoner (nim, nama, whatsapp, tempat_tinggal, sekolah, beasiswa, jenis_beasiswa, memilih_ubsi, mengetahui_ubsi, minat_kompetensi, aktivitas_organisasi) 
                      VALUES ('$nim', '$nama', '$whatsapp', '$tempat_tinggal', '$sekolah', '$beasiswa', NULL, '$memilih_ubsi', '$mengetahui_ubsi', '$minat_kompetensi', '$aktivitas_organisasi')";
        }
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Terima kasih! Data kuesioner Anda berhasil tersimpan.'); window.location='data-kuesioner.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim data kuesioner.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner Pendaftaran Mahasiswa Baru UBSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
     <div class="navbar-nav me-auto">
                 <a class="nav-link" href="data-kuesioner.php" target="_blank"> 📊 KEMBALI KE DATA QUISONER</a>    
     </div>
                 <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">Kuesioner Motivasi & Minat Mahasiswa UBSI</h4>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIM</label>
                                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" placeholder="Contoh: 0822xxxxxxxx" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tempat Tinggal</label>
                            <select name="tempat_tinggal" class="form-select" required>
                                <option value="">-- Pilih Wilayah Tempat Tinggal --</option>
                                <option value="Kota Tasikmalaya">Kota Tasikmalaya</option>
                                <option value="Kabupaten Tasikmalaya">Kabupaten Tasikmalaya</option>
                                <option value="Kabupaten Ciamis">Kabupaten Ciamis</option>
                                <option value="Kota Banjar">Kota Banjar</option>
                                <option value="Kota Garut">Kota Garut</option>
                                <option value="Kabupaten Garut">Kabupaten Garut</option>
                                <option value="Kabupaten Pangandaran">Kabupaten Pangandaran</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Asal Sekolah</label>
                            <input type="text" name="sekolah" class="form-control" placeholder="Contoh: SMKN 1 KAWALI" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Menerima Beasiswa?</label>
                                <select name="beasiswa" id="beasiswa" class="form-select" required onchange="toggleBeasiswa(this.value)">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="field_jenis_beasiswa" style="display: none;">
                                <label class="form-label fw-semibold">Jenis Beasiswa</label>
                                <select name="jenis_beasiswa" id="jenis_beasiswa" class="form-select">
                                    <option value="">-- Pilih Jenis Beasiswa --</option>
                                    <option value="Beasiswa Jalur Undangan">Beasiswa Jalur Undangan</option>
                                    <option value="Beasiswa Talenta Digital">Beasiswa Talenta Digital</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alasan Memilih UBSI</label>
                            <select name="memilih_ubsi" class="form-select" required>
                                <option value="">-- Pilih Alasan Utama --</option>
                                <option value="Biaya Terjangkau">Biaya Terjangkau</option>
                                <option value="Beasiswa">Beasiswa</option>
                                <option value="Dekat Dari Rumah">Dekat Dari Rumah</option>
                                <option value="Akreditasi Unggul">Akreditasi Unggul</option>
                                <option value="Pilihan Orang Tua">Pilihan Orang Tua</option>
                                <option value="Rekomendasi Guru BK">Rekomendasi Guru BK</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mengetahui UBSI Dari Mana?</label>
                            <select name="mengetahui_ubsi" class="form-select" required>
                                <option value="">-- Pilih Sumber Informasi --</option>
                                <option value="Guru BK">Guru BK</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Teman">Teman</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Mahasiswa UBSI">Mahasiswa UBSI</option>
                                <option value="Alumni UBSI">Alumni UBSI</option>
                                <option value="Karyawan UBSI">Karyawan UBSI</option>
                                <option value="Instagram UBSI Tasikmalaya">Instagram UBSI Tasikmalaya</option>
                                <option value="Tiktok UBSI Tasikmalaya">Tiktok UBSI Tasikmalaya</option>
                                <option value="Brosur">Brosur</option>
                                <option value="Spanduk">Spanduk</option>
                                <option value="Baliho">Baliho</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Minat dan Kompetensi</label>
                            <select name="minat_kompetensi" class="form-select" required>
                                <option value="">-- Pilih Minat Terbesar --</option>
                                <option value="Editing Video">Editing Video</option>
                                <option value="Editing Photo">Editing Photo</option>
                                <option value="Menulis Berita">Menulis Berita</option>
                                <option value="MC">MC</option>
                                <option value="Live Streaming">Live Streaming</option>
                                <option value="Influencer">Influencer</option>
                                <option value="Selebgram">Selebgram</option>
                                <option value="Olah Raga">Olah Raga</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Aktivitas Organisasi</label>
                            <select name="aktivitas_organisasi" class="form-select" required>
                                <option value="">-- Pilih Aktivitas Organisasi --</option>
                                <option value="Keagamaan">Keagamaan</option>
                                <option value="Pencinta Alam">Pencinta Alam</option>
                                <option value="Musik">Musik</option>
                                <option value="Tari">Tari</option>
                                <option value="Bahasa">Bahasa</option>
                                <option value="OSIS">OSIS</option>
                                <option value="Karang Taruna">Karang Taruna</option>
                                <option value="Komunitas Jepang">Komunitas Jepang</option>
                                <option value="Komunitas Korea">Komunitas Korea</option>
                            </select>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 fw-bold">Kirim Data Kuesioner</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Handle Ketentuan 1: 
 * Menampilkan sub-pilihan Jenis Beasiswa secara dinamis hanya jika opsi beasiswa bernilai 'Ya'
 */
function toggleBeasiswa(val) {
    var field = document.getElementById('field_jenis_beasiswa');
    var selectJenis = document.getElementById('jenis_beasiswa');
    if (val === 'Ya') {
        field.style.display = 'block';
        selectJenis.setAttribute('required', 'required');
    } else {
        field.style.display = 'none';
        selectJenis.removeAttribute('required');
        selectJenis.value = '';
    }
}
</script>
</body>
</html>