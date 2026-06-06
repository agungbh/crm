<?php
/**
 * SISTEM INFORMASI BEASISWA JALUR UNDANGAN (BJU) 2026
 * UNIVERSITAS BINA SARANA INFORMATIKA (UBSI) KAMPUS TASIKMALAYA
 * * File All-In-One Application Suite Suite Terupdate dengan Filter Cetak PDF.
 */

// --- 1. KONFIGURASI DATABASE ---
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bsi_tasikmalaya';

try {
    $pdo_init = new PDO("mysql:host=$host;charset=utf8", $db_user, $db_pass);
    $pdo_init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8 COLLATE utf8_general_ci;");
} catch (PDOException $e) {
    die("<div style='padding:20px; background:#f8d7da; color:#721c24; font-family:sans-serif;'>
            <h4>Koneksi database gagal!</h4>
            <p>Pesan Error: " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}

$pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// --- 2. MIGRASI STRUKTUR TABEL & DATA AWAL ---
$table_check = $pdo->query("SHOW TABLES LIKE 'bju_data'")->fetch();
if (!$table_check) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nama VARCHAR(100) NOT NULL,
        role ENUM('admin', 'operator') NOT NULL
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bju_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        formulir VARCHAR(50) NOT NULL,
        nama VARCHAR(150) NOT NULL,
        rangking VARCHAR(50),
        asal_sekolah VARCHAR(150) NOT NULL,
        beasiswa VARCHAR(50),
        status VARCHAR(50)
    );");

    $admin_pass = password_hash('admin', PASSWORD_DEFAULT);
    $operator_pass = password_hash('operator', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (username, password, nama, role) VALUES 
        ('admin', '$admin_pass', 'Agung Baitul Hikmah, M.Kom', 'admin'),
        ('operator', '$operator_pass', 'Operator BSI Tasikmalaya', 'operator');");

    $insert_query = "INSERT INTO bju_data (formulir, nama, rangking, asal_sekolah, beasiswa, status) VALUES
    ('1202604007578', 'Asri Nurfalah', 'Peringkat 1', 'SMK YPC Tasikmalaya', '100%', 'Closing'),
    ('1202604007679', 'Aini Dwi Guna', 'Peringkat 3', 'SMK YPC Tasikmalaya', '50%', 'Closing'),
    ('1202604007650', 'Nendi Sanjaya', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Closing'),
    ('1202604007079', 'Kirani Nurramadhani', 'Peringkat 1', 'SMAN 6 Tasikmalaya', '100%', 'Closing'),
    ('1202604007177', 'Kirana Nurramadhina', 'Peringkat 2', 'SMAN 6 Tasikmalaya', '75%', 'Closing'),
    ('1202604007506', 'Vira Haerunisa', 'Peringkat 1', 'SMAN 6 Tasikmalaya', '100%', 'Closing'),
    ('1202603006894', 'Keysa Maharani Saepul', 'Peringkat 5', 'SMKN 1 Kawali', '50%', 'Closing'),
    ('1202604007190', 'Liva', 'Peringkat 1', 'SMKN 1 Kawali', '100%', 'Closing'),
    ('1202603006905', 'Nadia Nurdianti', 'Peringkat 10', 'SMKN 1 Kawali', '50%', 'Closing'),
    ('1202603006644', 'Pajar Azmi Anugraha', 'Peringkat 1', 'SMKN 1 Kawali', '100%', 'Closing'),
    ('1202604007197', 'Sri Dwi Wahyuni', 'Peringkat 2', 'SMKN 1 Kawali', '75%', 'Closing'),
    ('1202604006978', 'Shalfa Aznha Aditya', 'Peringkat 1', 'MA Ibadul Ghofur Rajadesa', '100%', 'Closing'),
    ('1202603006924', 'Ulya Nuryaomi', 'Peringkat 1', 'SMAN 10 Tasikmalaya', '100%', 'Closing'),
    ('1202603006503', 'Moh Tazky Yanjali', 'Peringkat 9', 'SMK YPC Tasikmalaya', '50%', 'Closing'),
    ('1202603006902', 'Noval Albi Ba\'\'adilah', 'Peringkat 3', 'SMAN 10 Tasikmalaya', '50%', 'Closing'),
    ('1202603006901', 'Budi Wahyu', 'Peringkat 2', 'SMAN 10 Tasikmalaya', '75%', 'Closing'),
    ('1202603006900', 'Risad Diya Ulhaq', 'Peringkat 1', 'SMAN 10 Tasikmalaya', '100%', 'Closing'),
    ('1202603005530', 'Randi Ardiansyah', 'Peringkat 1', 'SMAN 8 Tasikmalaya', '100%', 'Closing'),
    ('1202601003015', 'Aang Anwar', 'Peringkat 1', 'MA AS-SA\'\'ADAH', '100%', 'Belum Closing'),
    ('1202601002997', 'Defril Mulya Saputra', 'Peringkat 1', 'SMK PLUS AN-NUUR', '100%', 'Belum Closing'),
    ('1,2026E+12', 'Nirma Nur Fatimah', 'Peringkat 1', 'SMA Negeri 1 Baregbeg', '100%', 'Closing'),
    ('1,2026E+12', 'Reya Pamungkas', 'Peringkat 2', 'SMK PGRI Cikoneng', '100%', 'Belum Closing'),
    ('1202604007216', 'Antung Tasria Tambusai', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
    ('1202604007215', 'Abdi Fajar Maulana', 'Peringkat 2', 'SMKN 3 Tasikmalaya', '75%', 'Closing'),
    ('1202604007493', 'Rahmadian Auliani Putri', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
    ('1202604008320', 'Mila Amalia', 'Peringkat 1', 'SMKN 3 Tasikmalaya', '100%', 'Closing'),
    ('1202604008376', 'Deseu Pohaseu', 'Peringkat 1', 'SMK PGRI Cikoneng', '100%', 'Closing'),
    ('1202604007518', 'Radhitya Adira', 'Peringkat 2', 'SMK PGRI Cikoneng', '75%', 'Belum Closing'),
    ('1202604007634', 'Ade Sofyan Irfani', 'Peringkat 1', 'SMK YPC Tasikmalaya', '100%', 'Belum Closing'),
    ('1202604007797', 'Mita Putri Cahyadi', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Closing'),
    ('1202604008510', 'Rafi Izazul Hakim', 'Peringkat 1', 'SMKN 1 Rajadesa', '100%', 'Closing'),
    ('1202604008404', 'Gina Qolbiatus Saadah S', 'Peringkat 2', 'SMK PGRI Cikoneng', '75%', 'Closing'),
    ('1202604007788', 'Dendra Alfiansyah', 'Peringkat 3', 'SMKN 3 Tasikmalaya', '50%', 'Belum Closing'),
    ('1202604007812', 'Anggiea Putri Luswandari', 'Peringkat 1', 'SMKN 1 Ciamis', '100%', 'Closing'),
    ('1202604009143', 'Safitri indriani', 'Peringkat 1', 'SMK Arrohmah Dadaha', '100%', 'Closing'),
    ('1202602003959', 'Muhammad Yasin Fadilah', 'Peringkat 1', 'SMK Igasar Pindad', '100%', 'Closing'),
    ('1202604008564', 'Fawwaz Zaidan Baariqni', 'Peringkat 9', 'SMK YPC Tasikmalaya', '50%', 'Belum Closing'),
    ('1202604009343', 'Lisna', 'Peringkat 1', 'SMK Sukapura', '75%', 'Closing'),
    ('1202604009306', 'Muhammad Syarip Hidayatullah', 'Peringkat 2', 'SMK Arrohmah Dadaha', '50%', 'Closing'),
    ('1202604009227', 'Nurul Hasna', 'Peringkat 4', 'SMK Nurul Wafa', '50%', 'Belum Closing'),
    ('1202604009791', 'Siti Fauziah', 'Peringkat 1', 'MAN 1 Kota Tasikmalaya', '75%', 'Belum Closing')";
    $pdo->exec($insert_query);
}

// --- 3. LOGIKA ROUTING & SESSION MANAJEMEN ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page = isset($_GET['p']) ? $_GET['p'] : 'dashboard';

if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header("Location: ?p=login");
    exit;
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// --- 4. AKSI POST (CRUD & AUTH) ---
$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            header("Location: ?p=dashboard");
            exit;
        } else {
            $error_msg = "Username atau Password Anda keliru!";
        }
    }
    
    if (isset($_POST['action_bju'])) {
        $act = $_POST['action_bju'];
        if ($act === 'add') {
            $stmt = $pdo->prepare("INSERT INTO bju_data (formulir, nama, rangking, asal_sekolah, beasiswa, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['formulir'], $_POST['nama'], $_POST['rangking'], $_POST['asal_sekolah'], $_POST['beasiswa'], $_POST['status']]);
            $success_msg = "Data siswa berhasil ditambahkan!";
        } elseif ($act === 'edit') {
            $stmt = $pdo->prepare("UPDATE bju_data SET formulir = ?, nama = ?, rangking = ?, asal_sekolah = ?, beasiswa = ?, status = ? WHERE id = ?");
            $stmt->execute([$_POST['formulir'], $_POST['nama'], $_POST['rangking'], $_POST['asal_sekolah'], $_POST['beasiswa'], $_POST['status'], $_POST['id']]);
            $success_msg = "Data siswa sukses diperbarui!";
        }
    }

    if (isset($_POST['action_user']) && is_admin()) {
        $act = $_POST['action_user'];
        if ($act === 'add') {
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['username'], $pass_hash, $_POST['nama'], $_POST['role']]);
                $success_msg = "User baru berhasil dibuat!";
            } catch (PDOException $e) {
                $error_msg = "Username sudah terdaftar di sistem!";
            }
        } elseif ($act === 'edit') {
            if (!empty($_POST['password'])) {
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username = ?, nama = ?, role = ?, password = ? WHERE id = ?");
                $stmt->execute([$_POST['username'], $_POST['nama'], $_POST['role'], $pass_hash, $_POST['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, nama = ?, role = ? WHERE id = ?");
                $stmt->execute([$_POST['username'], $_POST['nama'], $_POST['role'], $_POST['id']]);
            }
            $success_msg = "Data pengguna berhasil diubah!";
        }
    }
}

if (isset($_GET['delete_bju'])) {
    $stmt = $pdo->prepare("DELETE FROM bju_data WHERE id = ?");
    $stmt->execute([$_GET['delete_bju']]);
    header("Location: ?p=bju&msg=deleted");
    exit;
}
if (isset($_GET['delete_user']) && is_admin()) {
    if ((int)$_GET['delete_user'] !== (int)$_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_GET['delete_user']]);
        header("Location: ?p=users&msg=deleted");
        exit;
    }
}

// LOGIKA DOWNLOAD LAPORAN FORMAT PDF TERFILTER STATUS
if ($page === 'download_format') {
    $f_status = isset($_GET['status']) ? trim($_GET['status']) : '';
    
    $query_str = "SELECT * FROM bju_data";
    $params = [];
    if (!empty($f_status)) {
        $query_str .= " WHERE status = ?";
        $params[] = $f_status;
    }
    $query_str .= " ORDER BY id DESC";
    
    $stmt = $pdo->prepare($query_str);
    $stmt->execute($params);
    $all_bju = $stmt->fetchAll();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Data Beasiswa Jalur Undangan (BJU) 2026</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @media print {
                .no-print { display: none; }
                body { padding: 0; margin: 0; background-color: #fff; font-size: 11pt; }
                @page { size: A4 portrait; margin: 15mm 12mm; }
            }
            body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8f9fa; color: #333; }
            .report-header { border-bottom: 3px double #0d6efd; padding-bottom: 12px; margin-bottom: 25px; }
            .logo-placeholder { font-size: 24px; font-weight: 800; color: #0d6efd; letter-spacing: 1px; }
            .table th { background-color: #f1f3f5 !important; color: #111 !important; font-weight: 600; text-transform: uppercase; font-size: 10pt; }
            .badge-closing { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 9pt; }
            .badge-progress { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 9pt; }

        </style>
    </head>
    <body onload="window.print()">
        <div class="container my-5" style="max-width: 1000px;">
            <div class="no-print alert alert-info d-flex justify-content-between align-items-center mb-4">
                <span><i class="fa-solid fa-print me-2"></i>Kotak dialog cetak browser otomatis muncul. Anda dapat memilih opsi <strong>"Save as PDF"</strong> / <strong>"Simpan sebagai PDF"</strong> pada komputer Anda.</span>
                <a href="?p=bju" class="btn btn-sm btn-secondary fw-bold">Kembali ke SIM</a>
            </div>
            
            <div class="report-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="logo-placeholder">UBSI TASIKMALAYA</div>
                    <div class="text-muted small">Sistem Informasi Beasiswa Jalur Undangan (BJU) 2026</div>
                </div>
                <div class="text-end">
                    <h4 class="fw-bold m-0 text-dark">LAPORAN DATA BJU</h4>
                    <span class="badge bg-primary text-white text-uppercase small mt-1"><?= !empty($f_status) ? 'Kategori: ' . htmlspecialchars($f_status) : 'Semua Status Data' ?></span>
                    <br><small class="text-muted">Dicetak pada: <?= date('d-m-Y H:i') ?> WIB</small>
                </div>
            </div>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Formulir/NIM</th>
                        <th style="width: 25%;">Nama Siswa</th>
                        <th style="width: 15%;">Peringkat</th>
                        <th style="width: 25%;">Asal Sekolah</th>
                        <th style="width: 10%;">Beasiswa</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($all_bju) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada entri data siswa yang memenuhi filter status ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($all_bju as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold text-dark text-center"><?= htmlspecialchars($row['formulir']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['rangking']) ?></td>
                                <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                                <td class="text-center fw-bold text-success"><?= htmlspecialchars($row['beasiswa']) ?></td>
                                <td class="text-center">
                                    <span class="<?= strtolower($row['status']) === 'closing' ? 'badge-closing' : 'badge-progress' ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="mt-5 d-flex justify-content-between align-items-center">
                <div class="text-muted small">Total Data Terpilih: <strong><?= count($all_bju) ?></strong> berkas pendaftar.</div>
                <div class="text-center" style="width: 200px; border-top: 1px solid #333; margin-top: 60px; padding-top: 5px;">
                    <small class="text-muted">Panitia Pendaftaran BJU</small>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($page === 'logout') {
    session_destroy();
    header("Location: ?p=login");
    exit;
}
?>

<?php if ($page === 'login'): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIM Beasiswa Jalur Undangan UBSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72, #2a5298); height: 100vh; display: flex; align-items: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-1 fw-bold text-primary">SIM-BJU 2026</h3>
                    <p class="text-muted text-center small mb-4">UBSI Kampus Tasikmalaya</p>
                    
                    <?php if ($error_msg): ?>
                        <div class="alert alert-danger p-2 text-center small"><?= $error_msg ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <input type="hidden" name="action_login" value="1">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="admin / operator" required autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">Masuk Aplikasi</button>
                    </form>
                    <div class="mt-4 text-center">
                        <small class="text-muted">Gunakan username & password <b>admin</b> atau <b>operator</b> untuk uji coba.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php else: ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi BJU 2026 - UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-info" href="?p=dashboard"><i class="fa-solid fa-graduation-cap me-2 text-warning"></i>UBSI TASIKMALAYA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= $page==='dashboard'?'active':'' ?>" href="?p=dashboard"><i class="fa-solid fa-chart-pie me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?= $page==='bju'?'active':'' ?>" href="?p=bju"><i class="fa-solid fa-folder-open me-1"></i> Data BJU</a></li>
                <li class="nav-item"><a class="nav-link" href="crm.php">📋 Manajemen CR</a></li>
                <li class="nav-item"><a class="nav-link" href="hasil_crm.php">📈 Hasil & Analisis CRM</a></li>
                <li class="nav-item"><a class="nav-link" href="generate.php?p=bju" target="_blank">📈 Laporan Data BJU</a></li>
                <li class="nav-item"><a class="nav-link" href="data-kuesioner.php" target="_blank">📈 Laporan Empowering BJU</a></li>
            </div>


                <?php if (is_admin()): ?>
                    <li class="nav-item"><a class="nav-link <?= $page==='users'?'active':'' ?>" href="?p=users"><i class="fa-solid fa-users-gear me-1"></i> Manajemen User</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text text-white me-3 small">
                <i class="fa-solid fa-user-tie text-warning me-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?> (<span class="badge bg-secondary"><?= ucfirst($_SESSION['role']) ?></span>)
            </span>
            <a href="?p=logout" class="btn btn-sm btn-danger px-3 rounded-pill" onclick="return confirm('Apakah Anda ingin keluar?')"><i class="fa-solid fa-power-off"></i> Keluar</a>
        </div>
    </div>
</nav>

<div class="container my-4" style="min-height: 75vh;">
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger">Data berhasil dihapus dari sistem!</div>
    <?php endif; ?>
    <?php if ($success_msg): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <?php 
    if ($page === 'dashboard'): 
        $wilayah_map = [
            'SMK YPC Tasikmalaya' => 'Kabupaten Tasikmalaya', 'SMKN 3 Tasikmalaya' => 'Kota Tasikmalaya',
            'SMAN 6 Tasikmalaya' => 'Kota Tasikmalaya', 'SMKN 1 Kawali' => 'Kabupaten Ciamis',
            'MA Ibadul Ghofur Rajadesa' => 'Kabupaten Ciamis', 'SMAN 10 Tasikmalaya' => 'Kota Tasikmalaya',
            'SMAN 8 Tasikmalaya' => 'Kota Tasikmalaya', "MA AS-SA'ADAH" => 'Kota Tasikmalaya',
            'SMK PLUS AN-NUUR' => 'Kabupaten Tasikmalaya', 'SMA Negeri 1 Baregbeg' => 'Kabupaten Ciamis',
            'SMK PGRI Cikoneng' => 'Kabupaten Ciamis', 'SMKN 1 Rajadesa' => 'Kabupaten Ciamis',
            'SMKN 1 Ciamis' => 'Kabupaten Ciamis', 'SMK Arrohmah Dadaha' => 'Kota Tasikmalaya',
            'SMK Igasar Pindad' => 'Kabupaten Tasikmalaya', 'SMK Sukapura' => 'Kota Tasikmalaya',
            'SMK Nurul Wafa' => 'Kabupaten Tasikmalaya', 'MAN 1 Kota Tasikmalaya' => 'Kota Tasikmalaya'
        ];

        $f_sekolah = isset($_GET['asal_sekolah']) ? trim($_GET['asal_sekolah']) : '';
        $f_status = isset($_GET['status']) ? trim($_GET['status']) : '';

        $sch_stmt = $pdo->query("SELECT DISTINCT asal_sekolah FROM bju_data ORDER BY asal_sekolah ASC");
        $all_schools = $sch_stmt->fetchAll(PDO::FETCH_COLUMN);

        $q_str = "SELECT * FROM bju_data WHERE 1=1";
        $p_list = [];
        if (!empty($f_sekolah)) { $q_str .= " AND asal_sekolah = ?"; $p_list[] = $f_sekolah; }
        if (!empty($f_status)) { $q_str .= " AND status = ?"; $p_list[] = $f_status; }
        
        $main_stmt = $pdo->prepare($q_str);
        $main_stmt->execute($p_list);
        $filtered = $main_stmt->fetchAll();

        $chart_counts = [];
        foreach ($filtered as $r) {
            $s = $r['asal_sekolah'];
            $chart_counts[$s] = isset($chart_counts[$s]) ? $chart_counts[$s] + 1 : 1;
        }
        arsort($chart_counts);

        $baseline = $pdo->query("SELECT * FROM bju_data")->fetchAll();
        $wil_stats = [
            'Kota Tasikmalaya' => ['total' => 0, 'closing' => 0],
            'Kabupaten Tasikmalaya' => ['total' => 0, 'closing' => 0],
            'Kabupaten Ciamis' => ['total' => 0, 'closing' => 0],
            'Kota Banjar' => ['total' => 0, 'closing' => 0],
            'Kabupaten Pangandaran' => ['total' => 0, 'closing' => 0],
            'Kota Garut' => ['total' => 0, 'closing' => 0],
            'Kabupaten Garut' => ['total' => 0, 'closing' => 0],
        ];
        foreach ($baseline as $b) {
            $sc = $b['asal_sekolah'];
            $w_label = isset($wilayah_map[$sc]) ? $wilayah_map[$sc] : 'Luar Wilayah';
            if (isset($wil_stats[$w_label])) {
                $wil_stats[$w_label]['total']++;
                if (strtolower($b['status']) === 'closing') { $wil_stats[$w_label]['closing']++; }
            }
        }
    ?>
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Dashboard Utama Analisis</h3>
                <p class="text-muted small">Statistik Beasiswa Jalur Undangan Berdasarkan Filter Sekolah & Status Real-time.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="p" value="dashboard">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary">Asal Sekolah Mitra</label>
                        <select name="asal_sekolah" class="form-select">
                            <option value="">-- Tampilkan Semua Sekolah --</option>
                            <?php foreach ($all_schools as $sch_opt): ?>
                                <option value="<?= htmlspecialchars($sch_opt) ?>" <?= $f_sekolah===$sch_opt?'selected':'' ?>><?= htmlspecialchars($sch_opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select name="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="Closing" <?= $f_status==='Closing'?'selected':'' ?>>Closing</option>
                            <option value="Belum Closing" <?= $f_status==='Belum Closing'?'selected':'' ?>>Belum Closing</option>
                            <option value="Tidak Melanjutkan" <?= $f_status==='Tidak Melanjutkan'?'selected':'' ?>>Tidak Melanjutkan</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-filter me-2"></i>Saring Grafik</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-chart-bar me-2"></i>Grafik Batang Jumlah Pendaftar Per Sekolah</h6>
                        <?php if (empty($chart_counts)): ?>
                            <div class="alert alert-light text-center py-5">Kombinasi filter tidak menghasilkan data.</div>
                        <?php else: ?>
                            <div style="position: relative; height: 320px;">
                                <canvas id="canvasBju"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card border-0 bg-dark text-white shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-warning mb-3"><i class="fa-solid fa-robot me-2"></i>Analisis Data Strategis AI</h6>
                        <div class="small lh-base">
                            <p>Berdasarkan konsolidasi berkas pendaftaran:</p>
                            <ul class="ps-3 text-warning">
                                <li><b>Sekolah Terbanyak:</b> <span class="text-white">SMKN 3 Tasikmalaya</span> (7 Siswa).</li>
                                <li><b>Mitra Paling Efektif:</b> <span class="text-white">SMKN 1 Kawali</span> (5 Siswa Masuk & 100% Status Closing).</li>
                                <li><b>Pusat Kluster Makro:</b> <span class="text-white">Kota Tasikmalaya</span> memimpin dengan total 20 berkas siswa pendaftar.</li>
                            </ul>
                            <p class="text-white-50 mt-2 small border-top border-secondary pt-2">
                                <i class="fa-solid fa-lightbulb text-info me-1"></i><b>Insight Gemini:</b> Fokus rekrutmen dapat dialihkan ke area <i>Kabupaten Tasikmalaya</i> & <i>Ciamis</i> yang terbukti responsif namun rasionya belum maksimal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-map-location-dot me-2"></i>Tabel Matriks Sebaran Wilayah Administratif Mandat</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle small">
                        <thead class="table-primary text-center">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th class="text-start">Wilayah Administratif</th>
                                <th>Jumlah LoA</th>
                                <th>Jumlah Closing</th>
                                <th>Rasio Keberhasilan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n=1; foreach($wil_stats as $w_name => $w_data): ?>
                                <tr class="text-center">
                                    <td><?= $n++ ?></td>
                                    <td class="text-start fw-bold text-secondary"><?= $w_name ?></td>
                                    <td><span class="badge bg-primary px-3 py-2"><?= $w_data['total'] ?></span></td>
                                    <td><span class="badge bg-success px-3 py-2"><?= $w_data['closing'] ?></span></td>
                                    <td>
                                        <?php $pct = $w_data['total'] > 0 ? round(($w_data['closing']/$w_data['total'])*100, 1) : 0; ?>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="progress w-50 me-2" style="height:6px;">
                                                <div class="progress-bar bg-success" style="width: <?= $pct ?>%"></div>
                                            </div>
                                            <span class="fw-bold"><?= $pct ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvasBjuEl = document.getElementById('canvasBju');
            if (canvasBjuEl) {
                const ctxBju = canvasBjuEl.getContext('2d');
                new Chart(ctxBju, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_keys($chart_counts)) ?>,
                        datasets: [{
                            label: 'Jumlah Siswa Calon',
                            data: <?= json_encode(array_values($chart_counts)) ?>,
                            backgroundColor: 'rgba(13, 110, 253, 0.8)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });
            }
        });
        </script>

    <?php elseif ($page === 'bju'): 
        $bju_rows = $pdo->query("SELECT * FROM bju_data ORDER BY id DESC")->fetchAll();
        $sel_status = isset($_GET['sel_status']) ? trim($_GET['sel_status']) : '';
        
        // Memfilter data tabel BJU yang tampil di layar sesuai opsi pilihan
        if (!empty($sel_status)) {
            $stmt = $pdo->prepare("SELECT * FROM bju_data WHERE status = ? ORDER BY id DESC");
            $stmt->execute([$sel_status]);
            $bju_rows = $stmt->fetchAll();
        }
    ?>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                
                <div class="row bg-light p-3 rounded-3 border mb-4 align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex align-items-center">
                            <input type="hidden" name="p" value="bju">
                            <label class="fw-bold text-secondary me-2 small text-nowrap">Filter Tampilan Berkas:</label>
                            <select name="sel_status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                <option value="">-- Semua Status Data --</option>
                                <option value="Closing" <?= $sel_status==='Closing'?'selected':'' ?>>Closing</option>
                                <option value="Belum Closing" <?= $sel_status==='Belum Closing'?'selected':'' ?>>Belum Closing</option>
                                <option value="Tidak Melanjutkan" <?= $sel_status==='Tidak Melanjutkan'?'selected':'' ?>>Tidak Melanjutkan</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <a href="?p=download_format&status=<?= urlencode($sel_status) ?>" class="btn btn-danger fw-bold"><i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF Sesuai Filter</a>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-table-list text-primary me-2"></i>Data Berkas Jalur Undangan</h4>
                    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#mdlAddBju"><i class="fa-solid fa-user-plus me-1"></i> Tambah Entri</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle small">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Formulir/NIM</th>
                                <th>Nama Siswa</th>
                                <th>Rangking</th>
                                <th>Asal Sekolah</th>
                                <th>Beasiswa</th>
                                <th>Status</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($bju_rows) === 0): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">Tidak ada data pendaftaran yang cocok dengan filter status saat ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php $idx=1; foreach($bju_rows as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $idx++ ?></td>
                                        <td class="fw-bold text-primary"><?= htmlspecialchars($row['formulir']) ?></td>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($row['rangking']) ?></span></td>
                                        <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                                        <td class="text-center fw-bold text-success"><?= htmlspecialchars($row['beasiswa']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= strtolower($row['status'])==='closing'?'success':'warning' ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#mdlEditBju<?= $row['id'] ?>"><i class="fa-solid fa-marker"></i></button>
                                            <a href="?p=bju&delete_bju=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus entri data siswa ini?')"><i class="fa-solid fa-trash-can"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php foreach($bju_rows as $row): ?>
            <div class="modal fade" id="mdlEditBju<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="action_bju" value="edit">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Ubah Data Pendaftaran BJU</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Formulir/NIM</label>
                                <input type="text" name="formulir" class="form-control" value="<?= htmlspecialchars($row['formulir']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Peringkat / Rangking</label>
                                <input type="text" name="rangking" class="form-control" value="<?= htmlspecialchars($row['rangking']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Asal Sekolah</label>
                                <input type="text" name="asal_sekolah" class="form-control" value="<?= htmlspecialchars($row['asal_sekolah']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Beasiswa</label>
                                <input type="text" name="beasiswa" class="form-control" value="<?= htmlspecialchars($row['beasiswa']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Closing" <?= $row['status']==='Closing'?'selected':'' ?>>Closing</option>
                                    <option value="Belum Closing" <?= $row['status']==='Belum Closing'?'selected':'' ?>>Belum Closing</option>
                                    <option value="Tidak Melanjutkan" <?= $row['status']==='Tidak Melanjutkan'?'selected':'' ?>>Tidak Melanjutkan</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="modal fade" id="mdlAddBju" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <input type="hidden" name="action_bju" value="add">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Data Pendaftar Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Formulir/NIM</label>
                            <input type="text" name="formulir" class="form-control" required placeholder="Contoh: 1202604001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Nama lengkap siswa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Rangking</label>
                            <input type="text" name="rangking" class="form-control" placeholder="Contoh: Peringkat 1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control" required placeholder="Contoh: SMKN 3 Tasikmalaya">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Cakupan Beasiswa</label>
                            <input type="text" name="beasiswa" class="form-control" placeholder="Contoh: 100%">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Closing">Closing</option>
                                <option value="Belum Closing">Belum Closing</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Entri</button>
                    </div>
                </form>
            </div>
        </div>

    <?php elseif ($page === 'users' && is_admin()): 
        $user_rows = $pdo->query("SELECT * FROM users ORDER BY role ASC")->fetchAll();
    ?>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-users-gear text-primary me-2"></i>Manajemen User & Hak Akses</h4>
                    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#mdlAddUser"><i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle small">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Pengguna</th>
                                <th>Role / Hak Akses</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $u_idx=1; foreach($user_rows as $u): ?>
                                <tr>
                                    <td class="text-center"><?= $u_idx++ ?></td>
                                    <td class="fw-bold text-secondary"><?= htmlspecialchars($u['username']) ?></td>
                                    <td><?= htmlspecialchars($u['nama']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $u['role']==='admin'?'danger':'info' ?> text-uppercase">
                                            <?= $u['role'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#mdlEditUser<?= $u['id'] ?>"><i class="fa-solid fa-user-pen"></i></button>
                                        <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                            <a href="?p=users&delete_user=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="fa-solid fa-user-xmark"></i></a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light text-muted" disabled><i class="fa-solid fa-lock"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php foreach($user_rows as $u): ?>
            <div class="modal fade" id="mdlEditUser<?= $u['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="action_user" value="edit">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Ubah Akun Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($u['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="form-control" placeholder="Password baru">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Role Hak Akses</label>
                                <select name="role" class="form-select">
                                    <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>Admin</option>
                                    <option value="operator" <?= $u['role']==='operator'?'selected':'' ?>>Operator</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="modal fade" id="mdlAddUser" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <input type="hidden" name="action_user" value="add">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Role Hak Akses</label>
                            <select name="role" class="form-select">
                                <option value="operator">Operator</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="text-center text-muted py-3 bg-white border-top mt-5 small">
    <div class="container">
        &copy; 2026 - Sistem Informasi Manajemen Beasiswa Jalur Undangan UBSI Kampus Tasikmalaya.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>