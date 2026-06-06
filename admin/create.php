<?php
include '../config.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

if (isset($_POST['save_peserta'])) {
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
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Gagal menyimpan data pendaftaran: " . mysqli_error($conn);
    }
}
?>