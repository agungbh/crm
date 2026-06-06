<?php
include '../config.php';
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit; }

// Query Data Grafik
$chart_query = mysqli_query($conn, "SELECT sumber_event, COUNT(*) as total FROM tabel_event GROUP BY sumber_event");
$events = []; $totals = [];
while ($row = mysqli_fetch_assoc($chart_query)) {
    $events[] = $row['sumber_event'];
    $totals[] = (int)$row['total'];
}

// Filter Data
$filter_sumber    = isset($_GET['f_sumber']) ? mysqli_real_escape_string($conn, $_GET['f_sumber']) : '';
$filter_dihubungi = isset($_GET['f_dihubungi']) ? mysqli_real_escape_string($conn, $_GET['f_dihubungi']) : '';

$where_clause = "WHERE 1=1";
if ($filter_sumber != '') { $where_clause .= " AND sumber_event = '$filter_sumber'"; }
if ($filter_dihubungi != '') { $where_clause .= " AND mau_dihubungi = '$filter_dihubungi'"; }

$list_query = mysqli_query($conn, "SELECT * FROM tabel_event $where_clause ORDER BY id DESC");
$sumber_options = mysqli_query($conn, "SELECT DISTINCT sumber_event FROM tabel_event");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CRM UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        @media print {
            .no-print, form, nav, .btn, .action-col { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            .print-header { display: block !important; text-align: center; margin-bottom: 25px; }
            body { background-color: #ffffff; }
        }
        .print-header { display: none; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark no-print shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">CRM UBSI TASIK</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a class="nav-link active" href="dashboard.php">📊 Dashboard</a>
                <a class="nav-link" href="crm.php">📋 Manajemen CR</a>
                <a class="nav-link" href="hasil_crm.php">📈 Hasil & Analisis CRM</a>
                <a class="nav-link" href="generate.php?p=bju" target="_blank">📈 Laporan Data BJU</a>
                <a class="nav-link" href="data-kuesioner.php" target="_blank">📈 Laporan Empowering BJU</a>    
            </div>
            <div class="navbar-nav">
                <span class="navbar-text me-3 text-light">Hello, <strong class="text-warning"><?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role'] ?>)</strong></span>
                <a class="btn btn-outline-danger btn-sm px-3" href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="print-header">
        <h2 class="fw-bold mb-1">LAPORAN DATA PESERTA EVENT</h2>
        <h4 class="text-muted">Universitas Bina Sarana Informatika Kampus Tasikmalaya</h4>
        <hr style="border: 2px solid #000;">
    </div>

    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-secondary py-3">📊 Grafik Total Peserta Berdasarkan Sumber Event</div>
                <div class="card-body d-flex justify-content-center">
                    <div style="width: 100%; max-width: 700px; height: 320px;">
                        <canvas id="eventChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">👥 Daftar Data Peserta Event</h5>
            <div class="no-print">
                <button class="btn btn-success btn-sm fw-semibold shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#addModal">➕ Tambah Peserta</button>
                <button onclick="window.print()" class="btn btn-light btn-sm fw-semibold shadow-sm">📄 Download PDF / Cetak</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4 no-print bg-light p-3 rounded-3 border">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary">Filter Sumber Event</label>
                    <select name="f_sumber" class="form-select">
                        <option value="">-- Semua Event --</option>
                        <?php while($opt = mysqli_fetch_assoc($sumber_options)): ?>
                            <option value="<?= htmlspecialchars($opt['sumber_event']) ?>" <?= $filter_sumber == $opt['sumber_event'] ? 'selected':'' ?>><?= htmlspecialchars($opt['sumber_event']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Filter Hubungi Admin</label>
                    <select name="f_dihubungi" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Ya" <?= $filter_dihubungi == 'Ya' ? 'selected':'' ?>>Ya</option>
                        <option value="Tidak" <?= $filter_dihubungi == 'Tidak' ? 'selected':'' ?>>Tidak</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100 fw-semibold">Terapkan Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama Lengkap</th>
                            <th>WhatsApp</th>
                            <th>Asal Sekolah</th>
                            <th>Kelas</th>
                            <th>Jurusan Pilihan</th>
                            <th>Rencana Kuliah</th>
                            <th>Beasiswa</th>
                            <th>Hubungi</th>
                            <th>Sumber Event</th>
                            <th class="action-col no-print" style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($list_query) == 0): ?>
                            <tr><td colspan="11" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>
                        <?php endif; ?>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($list_query)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($row['no_whatsapp']) ?></td>
                            <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kelas']) ?></span></td>
                            <td><?= htmlspecialchars($row['tertarik_jurusan']) ?></td>
                            <td><?= htmlspecialchars($row['rencana_kuliah']) ?></td>
                            <td><?= htmlspecialchars($row['tertarik_beasiswa']) ?></td>
                            <td><span class="badge bg-<?= $row['mau_dihubungi']=='Ya'?'success':'danger'?>"><?= htmlspecialchars($row['mau_dihubungi']) ?></span></td>
                            <td><span class="text-primary fw-medium"><?= htmlspecialchars($row['sumber_event']) ?></span></td>
                            <td class="action-col no-print text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>&from=dashboard" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data peserta ini?')">🗑️ Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BOX: TAMBAH DATA (CREATE) -->
<div class="modal fade no-print" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="create.php" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="addModalLabel">➕ Tambah Peserta Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No Whatsapp</label>
                        <input type="tel" name="no_whatsapp" class="form-control" placeholder="Contoh: 08xxxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <select name="kelas" class="form-select" required>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                            <option value="Sudah Lulus">Sudah Lulus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tertarik Pada Jurusan</label>
                        <select name="tertarik_jurusan" id="m_jurusan" class="form-select" onchange="checkModalJurusan(this.value)" required>
                            <option value="Manajemen">Manajemen</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                            <option value="Pariwisata">Pariwisata</option>
                            <option value="Jurusan Lainnya">Jurusan Lainnya</option>
                        </select>
                        <input type="text" name="jurusan_lainnya" id="m_jurusan_lainnya" class="form-control mt-2 d-none" placeholder="Tuliskan nama jurusan manual...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rencana Kuliah</label>
                        <select name="rencana_kuliah" class="form-select" required>
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sumber Event</label>
                        <select name="sumber_event" id="m_event" class="form-select" onchange="checkModalEvent(this.value)" required>
                            <option value="Seminar Digital Kreatif">Seminar Digital Kreatif</option>
                            <option value="Workshop Digital Kreatif">Workshop Digital Kreatif</option>
                            <option value="Event Lainnya">Event Lainnya</option>
                        </select>
                        <input type="text" name="event_lainnya" id="m_event_lainnya" class="form-control mt-2 d-none" placeholder="Tuliskan nama event manual...">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="save_peserta" class="btn btn-success px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ctx = document.getElementById('eventChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($events) ?>,
        datasets: [{
            label: 'Jumlah Peserta',
            data: <?= json_encode($totals) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.75)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1.5,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

function checkModalJurusan(val){
    var el = document.getElementById('m_jurusan_lainnya');
    if(val === 'Jurusan Lainnya') { el.classList.remove('d-none'); el.setAttribute('required', 'required'); }
    else { el.classList.add('d-none'); el.removeAttribute('required'); el.value=''; }
}
function checkModalEvent(val){
    var el = document.getElementById('m_event_lainnya');
    if(val === 'Event Lainnya') { el.classList.remove('d-none'); el.setAttribute('required', 'required'); }
    else { el.classList.add('d-none'); el.removeAttribute('required'); el.value=''; }
}
</script>
</body>
</html>