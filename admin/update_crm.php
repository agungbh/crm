<?php
include '../config.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id         = (int)$_POST['id'];
    $status_crm = mysqli_real_escape_string($conn, $_POST['status_crm']);
    $alasan_crm = mysqli_real_escape_string($conn, $_POST['alasan_crm']);

    $query = "UPDATE tabel_event SET status_crm = '$status_crm', alasan_crm = '$alasan_crm' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: crm.php");
        exit;
    } else {
        echo "Gagal memperbarui data status: " . mysqli_error($conn);
    }
}
?>