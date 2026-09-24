<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $kode_matkul = trim(mysqli_real_escape_string($con, $_POST['kode_matkul']));
    $nama_matkul = trim(mysqli_real_escape_string($con, $_POST['nama_matkul']));
    $jml_sks     = trim(mysqli_real_escape_string($con, $_POST['jml_sks']));
    $jml_cpmk    = trim(mysqli_real_escape_string($con, $_POST['jml_cpmk']));

    // Jalankan query update
    $query_edit = mysqli_query($con, "UPDATE tbl_matkul SET
        nama_matkul = '$nama_matkul',
        jml_sks     = '$jml_sks',
        jml_cpmk    = '$jml_cpmk' 
        WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));

    // Cek apakah ada data yang benar-benar berubah di database
    if (mysqli_affected_rows($con) > 0) {
        echo '<script>alert("Data Berhasil Diedit"); window.location.href = "../admin_mata_kuliah";</script>';
    } else {
        echo '<script>alert("Data tidak ada yang berubah / Kode Matkul tidak ditemukan!"); window.location.href = "../admin_mata_kuliah";</script>';
    }
}
?>