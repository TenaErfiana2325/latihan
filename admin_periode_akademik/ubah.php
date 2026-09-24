<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $kode_akd          = trim(mysqli_real_escape_string($con, $_POST['kode_akd']) );
    $semester          = trim(mysqli_real_escape_string($con, $_POST['semester']) );
    $tahun             = trim(mysqli_real_escape_string($con, $_POST['tahun']) );
    $is_active         = trim(mysqli_real_escape_string($con, $_POST['is_active']) );

    if ($is_active == 'aktif' || $is_active == '1') {
        $is_active = '1';
    } else if ($is_active == 'tidak aktif' || $is_active == '0') {
        $is_active = '0';
    }

    $query_edit = mysqli_query($con, "UPDATE tbl_akademik SET
    kode_akd = '$kode_akd',
    semester = '$semester',
    tahun = '$tahun',
    is_active = '$is_active' 
    WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_periode_akademik";</script>';
    
}
?>