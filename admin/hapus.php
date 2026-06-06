<?php
include '../config.php';

if (!isset($_SESSION['role'])) { header("Location: ../login.php"); exit; }
if ($_SESSION['role'] !== 'admin') { echo "<script>alert('Akses Ditolak!'); window.location='data-kuesioner.php';</script>"; exit; }

if (!isset($_GET['nama']) || trim($_GET['nama']) == "") { header("Location: data-kuesioner.php"); exit; }
$nama = mysqli_real_escape_string($conn, $_GET['nama']);

if (mysqli_query($conn, "DELETE FROM tabel_quisoner WHERE nama = '$nama'")) {
    echo "<script>alert('Data Mahasiswa atas nama " . htmlspecialchars($nama) . " Berhasil Dihapus!'); window.location='data-kuesioner.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='data-kuesioner.php';</script>";
}
?>