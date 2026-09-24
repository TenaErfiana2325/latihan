<?php
require_once '../database/koneksi.php';

// Cek apakah tombol submit btn_ubah_kehadiran diklik
if (isset($_POST['btn_ubah_kehadiran'])) {
    $id_presensi      = trim(mysqli_real_escape_string($con, $_POST['id_presensi']));
    $kode_pertemuan   = trim(mysqli_real_escape_string($con, $_POST['kode_pertemuan']));
    $status_kehadiran = trim(mysqli_real_escape_string($con, $_POST['status_kehadiran']));

    // Query Update ke database
    $query_edit_status = mysqli_query($con, "UPDATE tbl_presensi SET status_kehadiran = '$status_kehadiran' WHERE id_presensi = '$id_presensi'") or die(mysqli_error($con));

    if ($query_edit_status) {
        echo "<script>alert('Status Kehadiran Berhasil Diubah!'); window.location='presensi.php?kode_pertemuan=$kode_pertemuan';</script>";
    } else {
        echo "<script>alert('Gagal Mengubah Status!'); window.location='presensi.php?kode_pertemuan=$kode_pertemuan';</script>";
    }
} else {
    // Jika diakses langsung tanpa lewat form modal, kembalikan ke halaman sebelumnya
    echo "<script>window.history.back();</script>";
    exit();
}
?>