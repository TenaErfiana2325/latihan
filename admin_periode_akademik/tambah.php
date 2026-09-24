<?php 
require_once'../database/koneksi.php';

if (isset($_POST['btn-tambah'])) {
    
        $kode_akd = trim(mysqli_real_escape_string($con, $_POST['kode_akd'] ?? ''));
        
        // Menyesuaikan nilai semester ke ENUM 'GN' atau 'GL'
        $semester_raw = $_POST['semester'] ?? '';
        $semester = ($semester_raw == 'Ganjil' || $semester_raw == 'GN') ? 'GN' : 'GL';
        $semester = trim(mysqli_real_escape_string($con, $semester));

        $tahun = trim(mysqli_real_escape_string($con, $_POST['tahun'] ?? ''));
        
        // Menyesuaikan nilai is_active ke ENUM '1' atau '0'
        $is_active_raw = $_POST['is_active'] ?? '0';
        $is_active = ($is_active_raw == '1' || $is_active_raw == 'Aktif') ? '1' : '0';
        $is_active = trim(mysqli_real_escape_string($con, $is_active));

        $cek_user = mysqli_query($con, "SELECT kode_akd FROM tbl_akademik WHERE kode_akd = '$kode_akd'")or die(mysqli_error($con));

        $rv = mysqli_num_rows($cek_user);
        if ($rv == 1) {
        echo '<script> alert ("kode_akd sudah terdaftar") </script>';
        }else {
            $query_simpan = mysqli_query($con, "INSERT INTO tbl_akademik (kode_akd, semester, tahun, is_active) VALUES ('$kode_akd','$semester','$tahun','$is_active')")or die(mysqli_error($con));

            echo '<script> alert ("data berhasil di simpan");
            window.location.href = "../admin_periode_akademik/"</script>';
        }
}

?>