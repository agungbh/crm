<?php
session_start();
// Proteksi Hak Akses Login
if (!isset($_SESSION['login'])) { 
    header("Location: ../login.php"); 
    exit; 
}
include '../config.php';

// Menangkap kiriman single parameter filter dari halaman crm.php
$f_status  = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

// Menyusun Query pencarian data berdasarkan filter status akhir
$q = "SELECT * FROM tabel_event WHERE 1=1";
if ($f_status != '') {
    $q .= " AND status_crm = '$f_status'";
}
$q .= " ORDER BY id DESC"; 
$res = mysqli_query($conn, $q);

// Mengonversi path ke URL Absolut agar gambar lokal bisa dimuat lintas platform (Windows & Apple)
$base_url_gambar = "http://localhost/event-web/uploads/";

// 1. Pengaturan Header Lintas Platform (Mendukung Windows Excel & Apple Numbers / Mac Office)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Laporan_Manajemen_CRM_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 2. Mengirimkan UTF-8 BOM (Byte Order Mark) khusus agar Apple macOS/iOS mendeteksi encoding dengan benar (Mencegah teks berantakan)
echo "\xEF\xBB\xBF";
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <style>
        /* CSS Standard Lintas Platform */
        body { font-family: Arial, sans-serif; margin: 20px; color: #333333; }
        h2 { color: #003366; margin-bottom: 5px; font-size: 18px; font-family: Arial, sans-serif; }
        p { font-size: 13px; margin-top: 0; color: #555555; font-family: Arial, sans-serif; }
        
        /* Desain Tabel Adaptif */
        .table-laporan { width: 100%; border-collapse: collapse; font-size: 12px; }
        .table-laporan th { background-color: #003366; color: #ffffff; text-align: center; font-weight: bold; padding: 8px; border: 1px solid #dddddd; }
        .table-laporan td { padding: 6px; border: 1px solid #dddddd; vertical-align: middle; }
        
        /* Utilitas Format Konten */
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        /* Proteksi Dimensi Gambar Prospek Lintas Device (Windows, Android, Mac, iPhone) */
        .img-prospek { 
            display: block; 
            margin: 0 auto; 
            width: 70px; 
            height: 70px; 
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>LAPORAN DATA MANAGEMENT CRM - UBSI TASIKMALAYA</h2>
<p>
    Status Akhir Terpilih: <b><?= $f_status ? htmlspecialchars($f_status) : 'Semua Status (Global)'; ?></b> | 
    Tanggal Unduh: <b><?= date('d-m-Y H:i'); ?> WIB</b>
</p>

<table class="table-laporan" border="1">
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th style="width: 200px;">Nama Lengkap</th>
            <th style="width: 140px;">No Whatsapp</th>
            <th style="width: 200px;">Asal Sekolah</th>
            <th style="width: 60px;">Kelas</th>
            <th style="width: 180px;">Sumber Event</th>
            <th style="width: 110px;">Tanggal Follow</th>
            <th style="width: 100px;">Tampilan Gambar Prospek</th>
            <th style="width: 110px; background-color: #28a745; color: #ffffff;">Status Akhir CRM</th>
            <th style="width: 220px; background-color: #ffc107; color: #000000;">Alasan / Keterangan</th>
            <th style="width: 130px; background-color: #6c757d; color: #ffffff;">Di Follow Up Oleh</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        while($row = mysqli_fetch_assoc($res)): 
            // Pewarnaan baris indikatif adaptif dokumen (Menggunakan Hex Warna Standar Excel)
            $bg_style = '';
            if($row['status_crm'] == 'Closing') $bg_style = 'background-color: #e2f0d9;';
            elseif($row['status_crm'] == 'Minat') $bg_style = 'background-color: #deeaf7;';
            elseif($row['status_crm'] == 'Ragu') $bg_style = 'background-color: #fff2cc;';
            elseif($row['status_crm'] == 'Nolak') $bg_style = 'background-color: #fce4d6;';
        ?>
        <tr style="<?= $bg_style; ?>">
            <td class="text-center"><?= $no++; ?></td>
            <td class="bold"><?= htmlspecialchars($row['nama_lengkap']); ?></td>
            
            <td style="mso-number-format:'\@';" class="text-center"><?= htmlspecialchars($row['no_whatsapp']); ?></td>
            
            <td><?= htmlspecialchars($row['asal_sekolah']); ?></td>
            <td class="text-center"><?= htmlspecialchars($row['kelas']); ?></td>
            <td><?= htmlspecialchars($row['sumber_event']); ?></td>
            
            <td class="text-center"><?= $row['tanggal_follow'] ? date('d-m-Y', strtotime($row['tanggal_follow'])) : '-'; ?></td>
            
            <td class="text-center">
                <?php if (!empty($row['upload_gambar'])): ?>
                    <img src="<?= $base_url_gambar . $row['upload_gambar']; ?>" width="70" height="70" class="img-prospek" alt="Bukti">
                <?php else: ?>
                    <span style="color: #999999; font-style: italic;">Tidak Ada</span>
                <?php endif; ?>
            </td>
            
            <td class="text-center bold"><?= htmlspecialchars($row['status_crm']); ?></td>
            <td><?= htmlspecialchars($row['alasan'] ? $row['alasan'] : '-'); ?></td>

            <td class="text-center bold" style="text-transform: uppercase; font-size: 11px;">
                <?= !empty($row['di_follow_up_oleh']) ? htmlspecialchars($row['di_follow_up_oleh']) : '-'; ?>
            </td>
        </tr>
        <?php endwhile; if(mysqli_num_rows($res) == 0): ?>
            <tr><td colspan="11" class="text-center bold" style="padding: 15px; color: #666666;">Data kosong atau tidak ditemukan rekor yang cocok dengan filter status akhir.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>