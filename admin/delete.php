<?php 
session_start(); 
// Proteksi ganda: Hanya akun dengan role 'admin' yang bisa menghapus data
if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin'){ 
    header("Location: crm.php"); 
    exit; 
}

include '../config.php'; 
$id = $_GET['id']; 

// Eksekusi hapus baris pendaftar
mysqli_query($conn, "DELETE FROM tabel_event WHERE id=$id"); 

// REVISI: Setelah menghapus, tetap stay/kembali di halaman crm.php
header("Location: crm.php"); 
exit; 
?>