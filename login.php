<?php
session_start();
include 'config.php';
$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            
            // REVISI: Mengalihkan rute default setelah login langsung ke halaman crm.php
            header("Location: admin/crm.php");
            exit;
        }
    }
    $error = "<div class='alert alert-danger'>Username atau Password salah!</div>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary d-flex align-items-center justify-content-center" style="height: 100vh;">
<div class="card shadow p-4" style="width: 380px;">
    <h3 class="text-center mb-4 fw-bold text-dark">CRM Login</h3>
    <?= $error; ?>
    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Username</label>
            <input type="text" name="username" class="form-control form-control-sm" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control form-control-sm" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">🔓 Masuk Ke Sistem</button>
    </form>
</div>
</body>
</html>