<?php
session_start();
require_once '../database/koneksi.php';

if (isset($_GET['kode_pertemuan'])){
    $kode_pertemuan = $_GET['kode_pertemuan'];
    $nim = $_SESSION['username'];

    // 1. Perbaikan Query SELECT & nama kolom 'status'
    $query_ambil_status_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_pertemuan='$kode_pertemuan'") or die (mysqli_error($con));
    $data_pertemuan = mysqli_fetch_array($query_ambil_status_pertemuan);
    
    // Nama kolom di database adalah 'status'
    $status_pertemuan = isset($data_pertemuan['status']) ? $data_pertemuan['status'] : 0;

    if ($status_pertemuan == 0) {
        echo '<script>alert("Presensi Telah Ditutup");
        window.location.href = "../mahasiswa_kelas";
        </script>';

    } else {
        $query_status_kehadiran = mysqli_query($con, "SELECT status_kehadiran FROM tbl_presensi WHERE kode_pertemuan='$kode_pertemuan' AND nim='$nim'") or die (mysqli_error($con));
        $data_status_kehadiran = mysqli_fetch_array($query_status_kehadiran);
        $status_kehadiran = isset($data_status_kehadiran['status_kehadiran']) ? $data_status_kehadiran['status_kehadiran'] : '';

        if ($status_kehadiran == 'Hadir') {
            echo '<script>alert("Anda sudah melakukan presensi");
            window.location.href = "../mahasiswa_kelas";
            </script>';
        } else {
            // 2. Perbaikan Query UPDATE: Mengisi string 'Hadir' dan menambah WHERE spesifik
            $query_update_kehadiran = mysqli_query($con, "UPDATE tbl_presensi SET status_kehadiran = 'Hadir' WHERE kode_pertemuan = '$kode_pertemuan' AND nim = '$nim'") or die (mysqli_error($con));
            
            echo '<script>alert("Anda Berhasil Presensi");
            window.location.href = "../mahasiswa_kelas";
            </script>';
        }
    }
}
?>