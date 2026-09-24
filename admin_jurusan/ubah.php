<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $kode_jurusan = trim(mysqli_real_escape_string($con, $_POST['kode_jurusan']));
    $nama_jurusan = trim(mysqli_real_escape_string($con, $_POST['nama_jurusan']));

    // Jalankan query update
    $query_edit = mysqli_query($con, "UPDATE tbl_jurusan SET
        nama_jurusan = '$nama_jurusan',
        WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));

    // Cek apakah ada data yang benar-benar berubah di database
    if (mysqli_affected_rows($con) > 0) {
        echo '<script>alert("Data Berhasil Diedit"); window.location.href = "../admin_jurusan";</script>';
    } else {
        echo '<script>alert("Data tidak ada yang berubah / Kode jurusan tidak ditemukan!"); window.location.href = "../admin_jurusan";</script>';
    }
}
?>