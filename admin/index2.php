<?php
include '../config.php';
if (!isset($_SESSION['role'])) { header("Location: ../login.php"); exit; }

$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'tempat_tinggal';
$allowed_filters = ['tempat_tinggal', 'sekolah', 'beasiswa', 'jenis_beasiswa', 'memilih_ubsi', 'mengetahui_ubsi', 'minat_kompetensi', 'aktivitas_organisasi'];
if (!in_array($filter, $allowed_filters)) { $filter = 'tempat_tinggal'; }

// 1. [PERBAIKAN] Query Agregasi Data Grafik & Tabel Menggunakan TRIM() Agar Tidak Duplikat Spasi
$query = "SELECT TRIM(`$filter`) AS label, COUNT(*) AS total 
          FROM tabel_quisoner 
          GROUP BY TRIM(`$filter`) 
          ORDER BY total DESC";
$result = mysqli_query($conn, $query);
$labels = []; $totals = []; $uraian_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Penanganan jika field bernilai kosong atau null
    $lbl = (!empty($row['label'])) ? $row['label'] : (($filter == 'beasiswa' || $filter == 'jenis_beasiswa') ? 'Bukan Penerima Beasiswa' : 'Tidak Mengisi');
    
    $labels[] = $lbl;
    $totals[] = (int)$row['total'];
    $uraian_data[] = ['label' => $lbl, 'total' => (int)$row['total']];
}

// 2. Query Komponen Laporan Inteligensi Dinamis Lintas AI dari tabel_quisoner
$res_wilayah = mysqli_query($conn, "SELECT tempat_tinggal, COUNT(*) as jml FROM tabel_quisoner GROUP BY tempat_tinggal ORDER BY jml DESC");
$wilayah_top = "Kota Tasikmalaya"; $wilayah_low = "Kabupaten Pangandaran";
$arr_wilayah = [];
while($r = mysqli_fetch_assoc($res_wilayah)) { $arr_wilayah[] = $r; }
if(count($arr_wilayah) > 0) {
    $wilayah_top = $arr_wilayah[0]['tempat_tinggal'];
    $wilayah_low = $arr_wilayah[count($arr_wilayah)-1]['tempat_tinggal'];
}

$res_info = mysqli_query($conn, "SELECT mengetahui_ubsi, COUNT(*) as jml FROM tabel_quisoner GROUP BY mengetahui_ubsi ORDER BY jml DESC LIMIT 1");
$info_top = ($res_info && mysqli_num_rows($res_info) > 0) ? mysqli_fetch_assoc($res_info)['mengetahui_ubsi'] : "Instagram UBSI Tasikmalaya";

$res_alasan = mysqli_query($conn, "SELECT memilih_ubsi, COUNT(*) as jml FROM tabel_quisoner GROUP BY memilih_ubsi ORDER BY jml DESC LIMIT 1");
$alasan_top = ($res_alasan && mysqli_num_rows($res_alasan) > 0) ? mysqli_fetch_assoc($res_alasan)['memilih_ubsi'] : "Biaya Terjangkau";

$res_minat = mysqli_query($conn, "SELECT minat_kompetensi, COUNT(*) as jml FROM tabel_quisoner GROUP BY minat_kompetensi ORDER BY jml DESC LIMIT 1");
$minat_top = ($res_minat && mysqli_num_rows($res_minat) > 0) ? mysqli_fetch_assoc($res_minat)['minat_kompetensi'] : "Editing Video";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Eksekutif Admin - UBSI Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .table-pesaing { font-size: 11px; vertical-align: middle; color: #333; }
        .bg-main-competitor { background-color: #f8d7da !important; font-weight: bold; }
        .bg-ubsi-baseline { background-color: #cff4fc !important; font-weight: bold; }
        .sticky-header th { position: sticky; top: 0; background-color: #212529; color: white; z-index: 10; }
        .table-responsive-scroll { max-height: 520px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">Dashboard Eksekutif UBSI [<?= strtoupper($_SESSION['role']); ?>]</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="index2.php">Grafik Analisis</a>
            <a class="nav-link" href="data-kuesioner.php">Data Kuesioner (CRUD)</a>
            <a class="nav-link btn btn-danger btn-sm text-white ms-3 px-3" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid my-4 px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-secondary">📊 Analisis Kuantitatif Variabel Kuesioner</h5>
                    <form method="GET" action="" class="d-flex gap-2">
                        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="tempat_tinggal" <?= $filter == 'tempat_tinggal' ? 'selected' : ''; ?>>Tempat Tinggal</option>
                            <option value="sekolah" <?= $filter == 'sekolah' ? 'selected' : ''; ?>>Asal Sekolah</option>
                            <option value="beasiswa" <?= $filter == 'beasiswa' ? 'selected' : ''; ?>>Status Beasiswa</option>
                            <option value="jenis_beasiswa" <?= $filter == 'jenis_beasiswa' ? 'selected' : ''; ?>>Jenis Beasiswa</option>
                            <option value="memilih_ubsi" <?= $filter == 'memilih_ubsi' ? 'selected' : ''; ?>>Alasan Memilih UBSI</option>
                            <option value="mengetahui_ubsi" <?= $filter == 'mengetahui_ubsi' ? 'selected' : ''; ?>>Mengetahui UBSI</option>
                            <option value="minat_kompetensi" <?= $filter == 'minat_kompetensi' ? 'selected' : ''; ?>>Minat & Kompetensi</option>
                            <option value="aktivitas_organisasi" <?= $filter == 'aktivitas_organisasi' ? 'selected' : ''; ?>>Aktivitas Organisasi</option>
                        </select>
                    </form>
                </div>
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-7 border-end text-center">
                            <div style="max-height: 280px; position: relative; margin: 0 auto;">
                                <canvas id="chartKuesioner"></canvas>
                            </div>
                        </div>
                        <div class="col-md-5 ps-4">
                            <h6 class="fw-bold text-dark mb-2">🔢 Uraian Akurat Nilai Angka Responden</h6>
                            <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-striped align-middle mb-0" style="font-size: 12px;">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>Variabel Nilai / Opsi Pilihan Konten</th>
                                            <th width="35%">Total Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($uraian_data) > 0): ?>
                                            <?php foreach($uraian_data as $ud): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($ud['label']); ?></strong></td>
                                                <td class="text-center fw-bold text-primary"><?= $ud['total']; ?> Mahasiswa</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data pendaftar.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-white border-top py-2">
                    <a href="cetak-pdf.php?filter=<?= $filter; ?>" target="_blank" class="btn btn-success btn-sm fw-bold px-4">Cetak Laporan PDF Eksekutif</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">🏢 Analisa Pesaing Wilayah Terurut & Rencana Strategis PMB 2027</h5>
                </div>
                <div class="card-body p-4">
                    <nav class="mb-3">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active fw-bold" id="nav-pesaing-tab" data-bs-toggle="tab" data-bs-target="#nav-pesaing" type="button" role="tab">1. Analisa Pesaing Wilayah Terurut (TA 2025/2026)</button>
                            <button class="nav-link fw-bold" id="nav-strategi-tab" data-bs-toggle="tab" data-bs-target="#nav-strategi" type="button" role="tab">2. Strategi Marketing 2027</button>
                            <button class="nav-link fw-bold" id="nav-swot-tab" data-bs-toggle="tab" data-bs-target="#nav-swot" type="button" role="tab">3. Metode SWOT</button>
                        </div>
                    </nav>
                    
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-pesaing" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Sumber Data: Web Perguruan Tinggi & PDDIKTI TA 2025/2026 (Analisis Lintas AI)</span>
                                <span class="badge bg-secondary p-2">Basis Responden Tertinggi: <?= $wilayah_top; ?></span>
                            </div>
                            
                            <div class="table-responsive table-responsive-scroll">
                                <table class="table table-sm table-bordered table-hover table-pesaing text-dark mb-0">
                                    <thead class="table-dark text-center sticky-header">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Perguruan Tinggi</th>
                                            <th>Wilayah Administrasi</th>
                                            <th>Program Beasiswa Utama Lapangan</th>
                                            <th>Nama Prodi Rumpun IT / Komputer</th>
                                            <th>Mhs Aktif Rumpun IT (SI/TI)</th>
                                            <th>Prodi Terpadat (Dominan)</th>
                                            <th>Penjelasan Singkat & Pola Gerakan Strategis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-ubsi-baseline">
                                            <td>-</td>
                                            <td><b>UNIVERSITAS BINA SARANA INFORMATIKA KAMPUS TASIKMALAYA</b></td>
                                            <td>Kota Tasikmalaya</td>
                                            <td>Beasiswa Jalur Undangan, Beasiswa Talenta Digital, Beasiswa Prestasi.</td>
                                            <td>Hanya Memiliki 1 Prodi Yaitu: S1+ Sistem Informasi</td>
                                            <td><b>~250+ Mahasiswa</b></td>
                                            <td>S1+ Sistem Informasi</td>
                                            <td><b>Baseline Pembanding Utama.</b> Fokus vokasional praktis siap kerja dunia digital kreatif. Efektifitas sebaran marcom bertumpu kuat pada instrumen: <b><?= $info_top; ?></b>.</td>
                                        </tr>

                                        <tr class="bg-main-competitor">
                                            <td class="text-center">1</td>
                                            <td>Universitas Siliwangi (UNSIL) <span class="badge bg-danger">PTN Utama</span></td>
                                            <td>Kota Tasikmalaya</td>
                                            <td>KIP-K Pemerintah murni, Beasiswa BI, JFLS Jabar, KSE, Djarum, Baznas.</td>
                                            <td>S1 Sistem Informasi & S1 Informatika</td>
                                            <td>~420 Mahasiswa</td>
                                            <td>S1 Manajemen & S1 Akuntansi</td>
                                            <td>Poros penentu market Priangan Timur. Menyerap lulusan SMA/SMK klaster akademik atas via SNBP/SNBT terpusat.</td>
                                        </tr>
                                        <tr class="bg-main-competitor">
                                            <td class="text-center">2</td>
                                            <td>Universitas Perjuangan (UNPER) <span class="badge bg-danger">PTS Utama</span></td>
                                            <td>Kota Tasikmalaya</td>
                                            <td>KIP-K Swasta, Beasiswa internal Yayasan Unsil, JFLS Jabar, Potongan DPP.</td>
                                            <td>S1 Informatika & S1 Sistem Informasi</td>
                                            <td>~500 Mahasiswa</td>
                                            <td>S1 Manajemen & S1 PGSD</td>
                                            <td>Kompetitor terdekat geografis. Agresif melakukan sosialisasi *door-to-door* dengan paket kuota klaim KIP-K dan kelonggaran dana bangunan.</td>
                                        </tr>
                                        <tr><td class="text-center">3</td><td>Universitas Muhammadiyah Tasikmalaya (UMTAS)</td><td>Kota Tasikmalaya</td><td>Beasiswa Persyarikatan, Lazismu, KIP-K Swasta.</td><td>S1 Teknik Informatika</td><td>~160 Mahasiswa</td><td>S1 Keperawatan & Kebidanan</td><td>PTS penyerap maba rumpun ilmu medis kesehatan, mengunci market via internal sekolah Muhammadiyah.</td></tr>
                                        <tr><td class="text-center">4</td><td>Universitas Bakti Tunas Husada (BTH)</td><td>Kota Tasikmalaya</td><td>Beasiswa Prestasi, KIP-K Swasta.</td><td>S1 Teknologi Informasi</td><td>~110 Mahasiswa</td><td>S1 Farmasi & D3 Medis</td><td>Klaster kesehatan murni yang merambah pembukaan laboratorium sistem IT modern.</td></tr>
                                        <tr><td class="text-center">5</td><td>Universitas Mayasari Bakti</td><td>Kota Tasikmalaya</td><td>Beasiswa Korporasi Mayasari, KIP-K Swasta.</td><td>S1 Sistem Telekomunikasi</td><td>~60 Mahasiswa</td><td>S1 Teknik Logistik & Manajemen</td><td>Sokongan finansial kuat grup Mayasari, mengamankan pasar utusan instansi/karyawan lokal.</td></tr>
                                        <tr><td class="text-center">6</td><td>STMIK DCI Tasikmalaya</td><td>Kota Tasikmalaya</td><td>Beasiswa Yayasan, KIP-K Swasta.</td><td>S1 Sistem Informasi & S1 TI</td><td>~140 Mahasiswa</td><td>S1 Sistem Informasi</td><td>Fokus mengandalkan program kuliah reguler sore hibrida khusus segmen pekerja.</td></tr>
                                        <tr><td class="text-center">7</td><td>STMIK Tasikmalaya (Restrukturisasi)</td><td>Kota Tasikmalaya</td><td>Beasiswa Ikatan Alumni, KIP-K Swasta.</td><td>S1 Sistem Informasi & S1 TI</td><td>~190 Mahasiswa</td><td>S1 Teknik Informatika</td><td>Mengunggulkan skema tarif angsuran SPP berkala tetap bagi kelas ekonomi menengah bawah.</td></tr>
                                        <tr><td class="text-center">8</td><td>STISIP Tasikmalaya</td><td>Kota Tasikmalaya</td><td>Beasiswa Aspirasi, KIP-K Swasta.</td><td>Non-IT (Sosial Politik)</td><td>0 Mahasiswa</td><td>S1 Ilmu Admin Negara</td><td>Membidik ceruk pasar peningkatan karir aparatur desa dan instansi daerah.</td></tr>
                                        <tr><td class="text-center">9</td><td>STIE Yasa Anggana (Kampus Tasik)</td><td>Kota Tasikmalaya</td><td>Beasiswa Kemitraan Dagang, KIP-K.</td><td>Non-IT (Ekonomi)</td><td>0 Mahasiswa</td><td>S1 Manajemen Bisnis</td><td>Fokus mengmas kampanye kewirausahaan praktis bagi pelaku UMKM.</td></tr>
                                        <tr><td class="text-center">10</td><td>STAI Tasikmalaya</td><td>Kota Tasikmalaya</td><td>Beasiswa Tahfiz, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Menyerap lulusan MA swasta pinggiran perkotaan lewat jalur keagamaan.</td></tr>
                                        <tr><td class="text-center">11</td><td>STAI Sabili Tasikmalaya</td><td>Kota Tasikmalaya</td><td>Beasiswa Lembaga, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Hukum Keluarga Islam</td><td>Mengamankan ceruk peminat profesi peradilan agama/syariah lokal.</td></tr>

                                        <tr><td class="text-center">12</td><td>Universitas Cipasung (UNCIP)</td><td>Kab. Tasikmalaya</td><td>Beasiswa KIP-K Swasta, Beasiswa Santri.</td><td>Rumpun Bisnis Digital (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Manajemen & Akuntansi</td><td>Sektor strategis jalur Singaparna. Dominan menjaring santri lokal kedaerahan.</td></tr>
                                        <tr><td class="text-center">13</td><td>STIE Latifah Mubarokah (Suryalaya)</td><td>Kab. Tasikmalaya</td><td>Beasiswa Ponpes Suryalaya, KIP-K.</td><td>Rumpun Ekonomi Bisnis (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Manajemen</td><td>Berpusat di Pesantren Suryalaya, mengunci loyalitas ikatan keluarga besar thariqah pondok.</td></tr>
                                        <tr><td class="text-center">14</td><td>IAI Cipasung (IAIC)</td><td>Kab. Tasikmalaya</td><td>Beasiswa Santri, KIP-K Kemenag.</td><td>Rumpun Komunikasi Islam (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Kuat pada segmen pemenuhan tenaga pengajar agama tradisional Tasikmalaya barat.</td></tr>
                                        <tr><td class="text-center">15</td><td>STIKES Respati Singaparna</td><td>Kab. Tasikmalaya</td><td>Beasiswa Yayasan, KIP-K Swasta.</td><td>Non-IT (Ilmu Medis)</td><td>0 Mahasiswa</td><td>S1 Kesehatan Masyarakat</td><td>Menargetkan pasar tenaga penyuluh kesehatan di pusat instansi kabupaten.</td></tr>
                                        <tr><td class="text-center">16</td><td>STAI Al-Hidayah Tasikmalaya</td><td>Kab. Tasikmalaya</td><td>Beasiswa Kitab Kuning, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Ahwal Al-Syakhsiyah</td><td>Penerimaan bertumpu pada madrasah aliyah pondok pedesaan sekitar.</td></tr>
                                        <tr><td class="text-center">17</td><td>STAI Sukapura Tasikmalaya</td><td>Kab. Tasikmalaya</td><td>Beasiswa Kemitraan Desa, KIP-K.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Tarbiyah Islamiyah</td><td>PTS penopang pemenuhan kebutuhan linieritas guru agama tingkat kecamatan.</td></tr>

                                        <tr class="bg-main-competitor">
                                            <td class="text-center">18</td>
                                            <td>Universitas Galuh (UNIGAL) <span class="badge bg-danger">PTS Utama</span></td>
                                            <td>Kabupaten Ciamis</td>
                                            <td>KIP-K Swasta, Beasiswa Unggulan Kemendikbud, Kemitraan Pemda Ciamis.</td>
                                            <td>S1 Sistem Informasi (FT)</td>
                                            <td>~220 Mahasiswa</td>
                                            <td>S1 Manajemen & S1 Hukum</td>
                                            <td>PTS penguasa mutlak teritori Ciamis-Banjar. Menjual kedekatan akses teritori dan loyalitas daerah.</td>
                                        </tr>
                                        <tr><td class="text-center">19</td><td>IAI Darussalam Ciamis (IAID)</td><td>Kabupaten Ciamis</td><td>Beasiswa Santri Berprestasi, KIP-K Kemenag.</td><td>Rumpun Komputer (TI Syariah)</td><td>~120 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Berakar di ekosistem Pesantren Darussalam, menjaring luaran MA zona Ciamis utara.</td></tr>
                                        <tr><td class="text-center">20</td><td>STIKES Muhammadiyah Ciamis</td><td>Kabupaten Ciamis</td><td>Beasiswa Persyarikatan, KIP-K Swasta.</td><td>Rumpun Kesehatan Terpadu (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Keperawatan & D3 Farmasi</td><td>Fokus menguasai sektor bursa tenaga medis di wilayah perbatasan Ciamis.</td></tr>
                                        <tr><td class="text-center">21</td><td>STAI Al-Ma'arif Ciamis</td><td>Kabupaten Ciamis</td><td>Beasiswa Yayasan, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 PIAUD (Pendidikan Guru RA)</td><td>PTS keagamaan kecil penyuplai guru prasekolah madrasah kecamatan.</td></tr>
                                        <tr><td class="text-center">22</td><td>STAI PUI Ciamis</td><td>Kabupaten Ciamis</td><td>Beasiswa Ormas PUI, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Islam</td><td>Menjaring basis lulusan dari lembaga pendidikan di bawah naungan ormas PUI.</td></tr>
                                        <tr><td class="text-center">23</td><td>STAI Syarif Hidayatullah Ciamis</td><td>Kabupaten Ciamis</td><td>Beasiswa Yayasan Pondok, KIP-K.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Muamalah Syariah</td><td>Menyerap segmen santri mukim mukallaf pedesaan sekitar Ciamis.</td></tr>

                                        <tr><td class="text-center">24</td><td>STIKES Bina Putera Banjar</td><td>Kota Banjar</td><td>Beasiswa Korporat, KIP-K Swasta.</td><td>Non-IT (Ilmu Medis)</td><td>0 Mahasiswa</td><td>S1 Keperawatan & D3 Kebidanan</td><td>PTS bidang kesehatan utama penguasa area administratif Kota Banjar.</td></tr>
                                        <tr><td class="text-center">25</td><td>STIT Muhammadiyah Banjar</td><td>Kota Banjar</td><td>Beasiswa Kader Muhammadiyah, KIP-K Swasta.</td><td>Non-IT (Pendidikan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Tarbiyah</td><td>Klaster tarbiyah kecil guna efisiensi biaya kos/akomodasi siswa lokal Banjar.</td></tr>
                                        <tr><td class="text-center">26</td><td>STAI Diponegoro Banjar</td><td>Kota Banjar</td><td>Beasiswa Yayasan, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Hukum Ekonomi Syariah</td><td>Menjaring lulusan madrasah aliyah area perbatasan lintas Jawa Tengah (Cilacap).</td></tr>
                                        <tr><td class="text-center">27</td><td>STAI Miftahul Huda Al-Azhar Banjar</td><td>Kota Banjar</td><td>Beasiswa Pesantren, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Mengunci pasar luaran santri di perbatasan kota Banjar timur.</td></tr>

                                        <tr class="bg-main-competitor">
                                            <td class="text-center">28</td>
                                            <td>Universitas Garut (UNIGA) <span class="badge bg-danger">PTS Utama</span></td>
                                            <td>Kabupaten Garut</td>
                                            <td>KIP-K Swasta, BPI Kemendikbud, Beasiswa Hafiz, Pemprov Jabar.</td>
                                            <td>S1 Sistem Informasi (FT)</td>
                                            <td>~280 Mahasiswa</td>
                                            <td>S1 Manajemen & S1 Farmasi</td>
                                            <td>PTS terbesar penguasa pangsa teritori Garut. Kuat pada integrasi pemasaran jaringan pedesaan.</td>
                                        </tr>
                                        <tr><td class="text-center">29</td><td>Institut Teknologi Garut (ITG)</td><td>Kabupaten Garut</td><td>KIP-K Swasta, Beasiswa Yayasan ITG, JFLS Jabar.</td><td>S1 Teknik Informatika / SI</td><td>~450 Mahasiswa</td><td>S1 Teknik Informatika</td><td>Kompetitor langsung UBSI dalam membidik lulusan SMK RPL/TKJ di wilayah Garut murni.</td></tr>
                                        <tr><td class="text-center">30</td><td>Universitas Medina Garut (UNMED)</td><td>Kabupaten Garut</td><td>Beasiswa Yayasan Medina, KIP-K Swasta.</td><td>Rumpun Komputer Grafis (Non-IT murni)</td><td>0 Mahasiswa</td><td>S1 Ilmu Keperawatan & Manajemen</td><td>Mengkombinasikan penawaran prodi ekonomi bisnis dan dominasi keperawatan medis.</td></tr>
                                        <tr><td class="text-center">31</td><td>STIE Kebangsaan Garut</td><td>Kabupaten Garut</td><td>Beasiswa Kurang Mampu, KIP-K.</td><td>Rumpun Manajemen Keuangan (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Manajemen Bisnis</td><td>Mengamankan pasar siswa lokal rumpun akuntansi perkotaan Garut.</td></tr>
                                        <tr><td class="text-center">32</td><td>Sekolah Tinggi Hukum (STH) Garut</td><td>Kabupaten Garut</td><td>Beasiswa Yudisial, KIP-K Swasta.</td><td>Non-IT (Ilmu Hukum)</td><td>0 Mahasiswa</td><td>S1 Ilmu Hukum</td><td>Spesialis rumpun hukum tertua rujukan pegawai instansi keamanan kedinasan lokal.</td></tr>
                                        <tr><td class="text-center">33</td><td>STAI Kharisma Garut</td><td>Kabupaten Garut</td><td>Beasiswa Tahfiz, KIP-K Kemenag.</td><td>Non-IT (Pendidikan)</td><td>0 Mahasiswa</td><td>S1 Tarbiyah Islamiyah</td><td>Penerimaan terfokus pada madrasah aliyah swasta kawasan Garut tengah.</td></tr>
                                        <tr><td class="text-center">34</td><td>STIE Yasa Anggana Garut</td><td>Kabupaten Garut</td><td>Beasiswa Kemitraan Usaha, KIP-K Swasta.</td><td>Rumpun Akuntansi (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Akuntansi Bisnis</td><td>Kompetitor bidang tata kelola administrasi keuangan regional Garut kota.</td></tr>
                                        <tr><td class="text-center">35</td><td>STKIP Muhammadiyah Garut</td><td>Kabupaten Garut</td><td>Beasiswa Kader, KIP-K Swasta.</td><td>Rumpun Keguruan (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Bahasa</td><td>Menjaring calon guru honorer di teritori Garut utara.</td></tr>
                                        <tr><td class="text-center">36</td><td>AMIK Garut</td><td>Kabupaten Garut</td><td>Beasiswa Yayasan, KIP-K Swasta.</td><td>D3 Manajemen Informatika</td><td>~90 Mahasiswa</td><td>D3 Manajemen Informatika</td><td>Akademi komputer lokal kecil, menyerap segmen pekerja kelas karyawan hibrida lokal.</td></tr>
                                        <tr><td class="text-center">37</td><td>STAI Al-Musaddadiyah Garut</td><td>Kabupaten Garut</td><td>Beasiswa Pesantren Musaddadiyah, KIP-K.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Mengunci loyalitas pondok pesantren besar perkotaan Garut.</td></tr>
                                        <tr><td class="text-center">38</td><td>STAI Al-Anwar Garut</td><td>Kabupaten Garut</td><td>Beasiswa Yayasan, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 KPI (Komunikasi Penyiaran)</td><td>Menjaring lulusan madrasah aliyah kawasan Garut selatan.</td></tr>
                                        <tr><td class="text-center">39</td><td>STIKES Karsa Husada Garut</td><td>Kabupaten Garut</td><td>Beasiswa Medis Korporat, KIP-K Swasta.</td><td>Non-IT (Ilmu Kesehatan)</td><td>0 Mahasiswa</td><td>S1 Keperawatan & D3 Kebidanan</td><td>Fokus menguasai sektor penyerapan tenaga klinik kesehatan di Garut.</td></tr>
                                        <tr><td class="text-center">40</td><td>STAI Siliwangi Garut</td><td>Kabupaten Garut</td><td>Beasiswa Kemitraan Teritorial, KIP-K.</td><td>Non-IT (Pendidikan Islam)</td><td>0 Mahasiswa</td><td>S1 PGMI (Pendidikan Madrasah)</td><td>Membidik ceruk pasar calon guru sekolah dasar islam pedesaan.</td></tr>
                                        <tr><td class="text-center">41</td><td>STAI Darul Arqam Garut</td><td>Kabupaten Garut</td><td>Beasiswa Kader Muhammadiyah, KIP-K.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Al-Ahwal Al-Syakhsiyah</td><td>Mengamankan basis lulusan pesantren Muhammadiyah Garut.</td></tr>
                                        <tr><td class="text-center">42</td><td>STIE Indonesia Membangun (Satgas Garut)</td><td>Kabupaten Garut</td><td>Beasiswa Korporasi, KIP-K Swasta.</td><td>Rumpun Manajemen Terapan (Non-IT)</td><td>0 Mahasiswa</td><td>S1 Manajemen Perusahaan</td><td>Kampanye fokus pada segmen kelas karyawan pekerja pabrik/industri.</td></tr>

                                        <tr><td class="text-center">43</td><td>STKIP Muhammadiyah Kuningan (Kampus Pangandaran)</td><td>Kab. Pangandaran</td><td>KIP-K Swasta, Beasiswa Pemda Pangandaran.</td><td>S1 Pendidikan Teknologi Informasi</td><td>~80 Mahasiswa</td><td>S1 PGSD & S1 Olahraga</td><td>Lintas teritori yang aktif menjaring guru honorer di pesisir Pangandaran.</td></tr>
                                        <tr><td class="text-center">44</td><td>STIT NU Al-Farabi Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Lembaga NU, KIP-K Kemenag.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Berbasis massa keagamaan lokal kultural NU di wilayah Pangandaran.</td></tr>
                                        <tr><td class="text-center">45</td><td>STIE Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Wisata, KIP-K Swasta.</td><td>Rumpun Tata Niaga Pariwisata</td><td>0 Mahasiswa</td><td>S1 Manajemen Pariwisata</td><td>Membidik segmen SDM industri pariwisata perhotelan pesisir pantai.</td></tr>
                                        <tr><td class="text-center">46</td><td>STAI Dinamika Bangsa Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Kemitraan, KIP-K Kemenag.</td><td>Non-IT (Sosial Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Komunikasi Penyiaran Islam</td><td>Menjaring segmen madrasah aliyah daerah perbatasan pesisir selatan.</td></tr>
                                        <tr><td class="text-center">47</td><td>STAI Al-Aziz Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Internal Pesantren, KIP-K.</td><td>Non-IT (Keagamaan)</td><td>0 Mahasiswa</td><td>S1 Pendidikan Agama Islam</td><td>Menyerap luaran santri lokal tingkat kecamatan Parigi.</td></tr>
                                        <tr><td class="text-center">48</td><td>STIKES Pangandaran (Kemitraan Klinik)</td><td>Kab. Pangandaran</td><td>Beasiswa Pemda Swasta, KIP-K.</td><td>Non-IT (Medis Terapan)</td><td>0 Mahasiswa</td><td>D3 Keperawatan Pedesaan</td><td>Membidik tenaga perawat puskesmas daerah pesisir selatan.</td></tr>
                                        <tr><td class="text-center">49</td><td>STAI Ma'arif Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa LP Ma'arif, KIP-K Kemenag.</td><td>Non-IT (Pendidikan Islam)</td><td>0 Mahasiswa</td><td>S1 PGMI (Guru Madrasah)</td><td>Mengamankan basis guru nahdliyin di wilayah Pangandaran barat.</td></tr>
                                        <tr><td class="text-center">50</td><td>STIE Pariwisata Pesisir Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Korporat Hotel, KIP-K.</td><td>Rumpun Tata Kelola Destinasi</td><td>0 Mahasiswa</td><td>D3 Perhotelan & Wisata</td><td>Fokus mencetak tenaga operasional penginapan wisata pantai selatan.</td></tr>
                                        <tr><td class="text-center">51</td><td>STAI Al-Muhtadin Pangandaran</td><td>Kab. Pangandaran</td><td>Beasiswa Kemitraan Desa, KIP-K Kemenag.</td><td>Non-IT (Hukum Keluarga Islam)</td><td>0 Mahasiswa</td><td>S1 Hukum Keluarga Islam</td><td>Menyerap segmen madrasah aliyah daerah pelosok Pangandaran utara.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-strategi" role="tabpanel">
                            <h5 class="text-primary mb-3 fw-bold">Blueprint Perencanaan Pemasaran Taktis PMB 2027</h5>
                            <div class="list-group list-group-flush small">
                                <div class="list-group-item px-0 py-3">
                                    🎯 <strong>1. Dominasi Anggaran Saluran Informasi Efektif (70% Budget Allocation):</strong><br>
                                    Berdasarkan rekapitulasi data kuesioner riil pendaftar, saluran publikasi penentu konversi tertinggi diraih oleh kanal <strong>"<?= htmlspecialchars($info_top); ?>"</strong>. Pimpinan direkomendasikan menginstruksikan tim Marcom untuk memotong anggaran pembuatan media fisik (Baliho/Spanduk jalanan) sebesar 50%, lalu mengalihkan anggarannya menjadi 70% porsi penuh untuk optimasi iklan digital berbayar serta penguatan konten kreatif pada media penentu tersebut.
                                </div>
                                <div class="list-group-item px-0 py-3">
                                    ⚔️ <strong>2. Taktik Pembendungan Pasar Berbasis Kompetensi Kreatif (Inbound Event Counter):</strong><br>
                                    Guna membendung laju serapan prodi rumpun IT PTN (UNSIL) dan pergerakan lapangan agresif dari PTS pesaing utama (UNPER, UNIGAL, UNIGA) di wilayah basis pendaftar terbanyak kita (**<?= htmlspecialchars($wilayah_top); ?>**), UBSI harus menyerang lewat keunggulan segmentasi minat. Karena minat terbesar mahasiswa baru terfokus pada sub-sektor <strong>"<?= htmlspecialchars($minat_top); ?>"</strong>, UBSI wajib menyelenggarakan kompetisi digital kreatif rutin skala regional berhadiah voucher *Beasiswa Talenta Digital* langsung di kampus demi mengunci komitmen pendaftaran maba sebelum didahului kompetitor.
                                </div>
                                <div class="list-group-item px-0 py-3">
                                    📍 <strong>3. Kampanye Gerilya Wilayah Satelit Lemah (Geomarketing Penetration):</strong><br>
                                    Data mendeteksi kontribusi pendaftaran maba paling tipis dan rentan berada di kawasan geografis <strong>"<?= htmlspecialchars($wilayah_low); ?>"</strong>. Strategi taktis PMB 2027 adalah menerjunkan satuan tugas marketing khusus untuk penetrasi ke sekolah-sekolah di wilayah satelit tersebut dengan membawa produk diferensiasi unggulan kita yaitu *Beasiswa Talenta Digital* dan beasiswa *Jalur Undangan* yang belum diterapkan secara masif oleh PTS konvensional setempat.
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-swot" role="tabpanel">
                            <h5 class="text-primary mb-3 fw-bold">Kerangka Analisis SWOT Lintas Data</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border-start border-success border-4 h-100">
                                        <strong class="text-success">💪 Strengths (Kekuatan)</strong>
                                        <p class="small text-muted mt-2 mb-0">Variabel pendorong utama eksternal mahasiswa menentukan pilihan kuliah di UBSI bersandar pada alasan <strong>"<?= htmlspecialchars($alasan_top); ?>"</strong>. Narasi keunggulan ini wajib dijadikan tajuk utama pada setiap halaman brosur digital promosi.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border-start border-danger border-4 h-100">
                                        <strong class="text-danger">⚠️ Weaknesses (Kelemahan)</strong>
                                        <p class="small text-muted mt-2 mb-0">Adanya area hampa kontribusi sebaran maba baru di wilayah geografis <strong>"<?= htmlspecialchars($wilayah_low); ?>"</strong>. Mengindikasikan tim publikasi belum memiliki jejaring relasi emosional yang solid bersama institusi sekolah menengah atas di kawasan tersebut.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border-start border-info border-4 h-100">
                                        <strong class="text-info">🚀 Opportunities (Peluang)</strong>
                                        <p class="small text-muted mt-2 mb-0">Klaster minat terbesar responden tertuju kuat pada sub-kompetensi <strong>"<?= htmlspecialchars($minat_top); ?>"</strong>. Peluang emas bagi perguruan tinggi untuk mengonversi hobi digital anak muda ini menjadi angka pendaftar prodi rumpun IT terpadu.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border-start border-warning border-4 h-100">
                                        <strong class="text-warning">💣 Threats (Ancaman)</strong>
                                        <p class="small text-muted mt-2 mb-0">Manuver beasiswa KIP-K penuh dari PTN (UNSIL) serta skema cicilan SPP bulanan fleksibel dari PTS kompetitor utama (UNPER, UNIGA, UNIGAL) yang membidik target sekolah mitra yang sama.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ctx = document.getElementById('chartKuesioner').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            data: <?= json_encode($totals); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>