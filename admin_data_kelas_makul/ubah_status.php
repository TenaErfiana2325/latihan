<?php
// Samakan timezone ke Indonesia/Jakarta
date_default_timezone_set('Asia/Jakarta');

require_once '../database/koneksi.php';

if (!isset($con) && isset($koneksi)) {
    $con = $koneksi;
}

$kode_pertemuan = $_GET['kode_pertemuan'];
$status = $_GET['status'];

if ($status == '1') {
    // Memaksa PHP mengirimkan format waktu tepat saat tombol diklik
    $sekarang = date('Y-m-d H:i:s');
    $query = "UPDATE tbl_pertemuan SET status = '1', waktu_dibuka = '$sekarang' WHERE kode_pertemuan = '$kode_pertemuan'";
} else {
    $query = "UPDATE tbl_pertemuan SET status = '0', waktu_dibuka = NULL WHERE kode_pertemuan = '$kode_pertemuan'";
}

mysqli_query($con, $query) or die(mysqli_error($con));

header("Location: presensi.php?kode_pertemuan=" . $kode_pertemuan);
exit;
?>