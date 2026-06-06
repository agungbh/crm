<?php
include 'config.php';
$pesan = "";

if (isset($_POST['submit'])) {
    $nama          = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $wa            = mysqli_real_escape_string($conn, $_POST['no_whatsapp']);
    $sekolah       = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $kelas         = $_POST['kelas'];
    $rencana       = $_POST['rencana_kuliah'];
    $beasiswa      = $_POST['tertarik_beasiswa'];
    $dihubungi     = $_POST['mau_dihubungi'];
    
    $jurusan = ($_POST['tertarik_jurusan'] == 'Jurusan Lainnya') ? $_POST['jurusan_lainnya'] : $_POST['tertarik_jurusan'];
    $sumber  = ($_POST['sumber_event'] == 'Event Lainnya') ? $_POST['event_lainnya'] : $_POST['sumber_event'];

    $jurusan = mysqli_real_escape_string($conn, $jurusan);
    $sumber  = mysqli_real_escape_string($conn, $sumber);

    $query = "INSERT INTO tabel_event (nama_lengkap, no_whatsapp, asal_sekolah, kelas, tertarik_jurusan, rencana_kuliah, tertarik_beasiswa, mau_dihubungi, sumber_event) 
              VALUES ('$nama', '$wa', '$sekolah', '$kelas', '$jurusan', '$rencana', '$beasiswa', '$dihubungi', '$sumber')";
    
    if (mysqli_query($conn, $query)) {
        $pesan = "<div class='alert alert-success shadow-sm'>🎉 Data berhasil dikirim! Terima kasih telah mengisi form.</div>";
    } else {
        $pesan = "<div class='alert alert-danger shadow-sm'>❌ Gagal mengirim data: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Event - UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 12px; }
        .card-header { border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-1 fw-bold">UBSI Kampus Tasikmalaya</h3>
                    <p class="mb-0 opacity-75">Formulir Data Peserta & Minat Jurusan Kuliah</p>
                </div>
                <div class="card-body p-4">
                    <?= $pesan; ?>
                    <form action="" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No Whatsapp</label>
                            <input type="tel" name="no_whatsapp" class="form-control" placeholder="Contoh: 08123456xxx" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control" placeholder="Masukkan nama sekolah asal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kelas</label>
                            <select name="kelas" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                                <option value="Sudah Lulus">Sudah Lulus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tertarik Pada Jurusan</label>
                            <select name="tertarik_jurusan" id="jurusan" class="form-select" onchange="checkJurusan(this.value)" required>
                                <option value="" disabled selected>-- Pilih Jurusan --</option>
                                <option value="Manajemen">Manajemen</option>
                                <option value="Akuntansi">Akuntansi</option>
                                <option value="Informatika">Informatika</option>
                                <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                                <option value="Pariwisata">Pariwisata</option>
                                <option value="Jurusan Lainnya">Jurusan Lainnya</option>
                            </select>
                            <input type="text" name="jurusan_lainnya" id="jurusan_lainnya" class="form-control mt-2 d-none" placeholder="Tuliskan nama jurusan lainnya...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rencana Kuliah</label>
                            <select name="rencana_kuliah" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Rencana Kuliah --</option>
                                <option value="Tahun Ini">Tahun Ini</option>
                                <option value="Tahun Depan">Tahun Depan</option>
                                <option value="Mencari Info">Mencari Info</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tertarik dengan Beasiswa?</label>
                                <select name="tertarik_beasiswa" class="form-select" required>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Mau Dihubungi Admin?</label>
                                <select name="mau_dihubungi" class="form-select" required>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Sumber Event</label>
                            <select name="sumber_event" id="event" class="form-select" onchange="checkEvent(this.value)" required>
                                <option value="" disabled selected>-- Pilih Sumber Event --</option>
                                <option value="Seminar Digital Kreatif">Seminar Digital Kreatif</option>
                                <option value="Workshop Digital Kreatif">Workshop Digital Kreatif</option>
                                <option value="Event Lainnya">Event Lainnya</option>
                            </select>
                            <input type="text" name="event_lainnya" id="event_lainnya" class="form-control mt-2 d-none" placeholder="Tuliskan nama event lainnya...">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">Kirim Formulir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkJurusan(val){
    var element = document.getElementById('jurusan_lainnya');
    if(val === 'Jurusan Lainnya') {
        element.classList.remove('d-none');
        element.setAttribute('required', 'required');
    } else {
        element.classList.add('d-none');
        element.removeAttribute('required');
    }
}
function checkEvent(val){
    var element = document.getElementById('event_lainnya');
    if(val === 'Event Lainnya') {
        element.removeProperty ? element.removeProperty('required') : null;
        element.classList.remove('d-none');
        element.setAttribute('required', 'required');
    } else {
        element.classList.add('d-none');
        element.removeAttribute('required');
    }
}
</script>
</body>
</html>