<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bsi_tasikmalaya";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>