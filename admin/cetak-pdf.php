<?php
include '../config.php';
if (!isset($_SESSION['role'])) { header("Location: ../login.php"); exit; }

$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'tempat_tinggal';
$allowed_filters = ['tempat_tinggal', 'sekolah', 'beasiswa', 'jenis_beasiswa', 'memilih_ubsi', 'mengetahui_ubsi', 'minat_kompetensi', 'aktivitas_organisasi'];
if (!in_array($filter, $allowed_filters)) { $filter = 'tempat_tinggal'; }

$query = "SELECT `$filter` AS label, COUNT(*) AS total FROM tabel_quisoner GROUP BY `$filter` ORDER BY total DESC";
$result = mysqli_query($conn, $query);
$labels = []; $totals = []; $uraian_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $lbl = $row['label'] ? $row['label'] : 'Bukan Penerima Beasiswa';
    $labels[] = $lbl;
    $totals[] = (int)$row['total'];
    $uraian_data[] = ['label' => $lbl, 'total' => (int)$row['total']];
}

$res_wilayah = mysqli_query($conn, "SELECT tempat_tinggal, COUNT(*) as jml FROM tabel_quisoner GROUP BY tempat_tinggal ORDER BY jml DESC");
$wilayah_top = "Kota Tasikmalaya"; $wilayah_low = "Kabupaten Pangandaran";
$arr_wilayah = [];
while($r = mysqli_fetch_assoc($res_wilayah)) { $arr_wilayah[] = $r; }
if(count($arr_wilayah) > 0) {
    $wilayah_top = $arr_wilayah[0]['tempat_tinggal'];
    $wilayah_low = $arr_wilayah[count($arr_wilayah)-1]['tempat_tinggal'];
}
$res_info = mysqli_query($conn, "SELECT mengetahui_ubsi, COUNT(*) as jml FROM tabel_quisoner GROUP BY mengetahui_ubsi ORDER BY jml DESC LIMIT 1");
$info_top = ($res_info && mysqli_num_rows($res_info) > 0) ? mysqli_fetch_assoc($res_info)['mengetahui_ubsi'] : "Media Sosial";

$res_alasan = mysqli_query($conn, "SELECT memilih_ubsi, COUNT(*) as jml FROM tabel_quisoner GROUP BY memilih_ubsi ORDER BY jml DESC LIMIT 1");
$alasan_top = ($res_alasan && mysqli_num_rows($res_alasan) > 0) ? mysqli_fetch_assoc($res_alasan)['memilih_ubsi'] : "Biaya Terjangkau";

$res_minat = mysqli_query($conn, "SELECT minat_kompetensi, COUNT(*) as jml FROM tabel_quisoner GROUP BY minat_kompetensi ORDER BY jml DESC LIMIT 1");
$minat_top = ($res_minat && mysqli_num_rows($res_minat) > 0) ? mysqli_fetch_assoc($res_minat)['minat_kompetensi'] : "Editing Video";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Intelijen Eksekutif PMB 2027</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 5px; background-color: #fff; font-size: 8.5px; }
            .page-break { page-break-before: always; }
        }
        .table-pdf { font-size: 8.5px; color: #000; vertical-align: middle; }
        .highlight-row { background-color: #f8d7da !important; font-weight: bold; }
        .ubsi-row { background-color: #cff4fc !important; font-weight: bold; }
    </style>
</head>
<body class="bg-white">
<div class="container-fluid my-1">
    <div class="text-center mb-2">
        <h5 class="fw-bold mb-0">UNIVERSITAS BINA SARANA INFORMATIKA</h5>
        <h6 class="text-uppercase text-secondary fw-bold mb-0" style="font-size: 10px;">Laporan Eksekutif Hasil Analisa Pasar Kompetitor Terurut & Manifes Kuesioner</h6>
        <hr style="border: 1.5px solid #000; opacity: 1; margin-top: 2px; margin-bottom: 4px;">
    </div>

    <div class="row mb-3">
        <div class="col-7 border-end">
            <h6 class="fw-bold text-dark mb-1">A. Visualisasi Grafik Batang Kuesioner</h6>
            <div class="p-1 border rounded bg-white text-center"><canvas id="chartPrint" style="max-height: 150px;"></canvas></div>
        </div>
        <div class="col-5">
            <h6 class="fw-bold text-dark mb-1">B. Uraian Angka Kuantitatif Akurat</h6>
            <table class="table table-sm table-bordered table-striped table-pdf mb-0">
                <thead class="table-secondary text-center"><tr><th>Variabel Kandungan</th><th>Total Jwban</th></tr></thead>
                <tbody>
                    <?php foreach($uraian_data as $ud): ?>
                    <tr><td><b><?= htmlspecialchars($ud['label']); ?></b></td><td class="text-center fw-bold text-primary"><?= $ud['total']; ?> Orang</td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row page-break pt-2">
        <div class="col-md-12">
            <h6 class="fw-bold mb-2 text-center text-decoration-underline text-uppercase" style="font-size: 9px;">C. EXECUTIVE SUMMARY INTELLIGENCE REPORT FOR LEADERSHIP (TA 2025/2026)</h6>
            <table class="table table-bordered table-sm align-middle table-pdf mb-3">
                <thead class="table-dark text-center"><tr><th>Nama Perguruan Tinggi</th><th>Wilayah Teritori</th><th>Beasiswa Unggulan</th><th>Status Rumpun IT</th><th>Pola Gerakan Lapangan</th></tr></thead>
                <tbody>
                    <tr class="ubsi-row"><td><b>UBSI TASIKMALAYA</b></td><td>Kota Tasikmalaya</td><td>Jalur Undangan, Talenta Digital.</td><td>D3 SI / SIA (~250+ aktif).</td><td>Baseline Acuan. Vokasional digital terpadu via media <b><?= $info_top; ?></b>.</td></tr>
                    <tr class="highlight-row"><td><b>UNSIL (PTN)</b></td><td>Kota Tasikmalaya</td><td>KIP-K Pemerintah murni, BI, JFLS.</td><td>S1 Sistem Informasi (~420 mhs).</td><td>Menyerap mayoritas input maba berprestasi tinggi via SNBP/SNBT. Poros market lokal.</td></tr>
                    <tr class="highlight-row"><td><b>UNPER (PTS)</b></td><td>Kota Tasikmalaya</td><td>KIP-K Swasta, Beasiswa internal.</td><td>S1 Informatika & SI (~500+ aktif).</td><td>PTS kompetitor terdekat. Agresif klaim garansi KIP-K kuota maba sekolah mitra.</td></tr>
                    <tr class="highlight-row"><td><b>UNIGAL (PTS)</b></td><td>Kab. Ciamis</td><td>KIP-K Swasta, Kemitraan Pemda Ciamis.</td><td>S1 Sistem Informasi (~220 mhs).</td><td>Penguasa pasar Ciamis. Memanfaatkan kedekatan lokasi teritori lokal kedaerahan.</td></tr>
                    <tr class="highlight-row"><td><b>UNIGA (PTS)</b></td><td>Kab. Garut</td><td>KIP-K Swasta, Beasiswa Hafiz Al-Qur'an.</td><td>S1 Sistem Informasi (~280 mhs).</td><td>PTS terkuat Garut. Infiltrasi massal ke sekolah pedesaan & pesantren lokal.</td></tr>
                </tbody>
            </table>

            <h6 class="fw-bold text-primary mb-1" style="font-size: 9px;">2. Formulasi Rencana Strategi Marketing PMB 2027</h6>
            <table class="table table-sm table-borderless table-pdf mb-2">
                <tr><td width="2%" valign="top">✔</td><td><strong>Relokasi Anggaran Optimal Saluran Terbukti:</strong> Fokuskan anggaran promosi marcom digital sebesar 70% berfokus pada media <b>"<?= $info_top; ?>"</b> yang terbukti di database menduduki urutan konversi teratas.</td></tr>
                <tr><td valign="top">✔</td><td><strong>Inbound Counter Pesaing Utama:</strong> Guna mengunci komitmen pendaftaran di basis pendaftar tertinggi kita (<b><?= $wilayah_top; ?></b>), gelar event kompetisi hobi terfavorit responden: <b>"<?= $minat_top; ?>"</b> berskala Priangan Timur.</td></tr>
            </table>

            <h6 class="fw-bold text-primary mb-1" style="font-size: 9px;">3. Kerangka Ringkasan SWOT Matriks Evaluasi</h6>
            <table class="table table-bordered table-sm table-pdf mb-0">
                <tr><td width="50%"><strong>Strengths (Kekuatan):</strong> Nilai jual utama bertumpu penuh pada daya tarik alasan <b>"<?= $alasan_top; ?>"</b>.</td><td width="50%"><strong>Weaknesses (Kelemahan):</strong> Terdeteksinya ruang hampa promosi masif di area teritori satelit <b>"<?= $wilayah_low; ?>"</b>.</td></tr>
            </table>
        </div>
    </div>
</div>
<script>
const ctx = document.getElementById('chartPrint').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            data: <?= json_encode($totals); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        animation: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>