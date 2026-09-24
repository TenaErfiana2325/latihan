<?php 
require_once'../database/koneksi.php';

if (isset($_POST['btn-tambah'])) {
    
        $kode_jurusan = trim(mysqli_real_escape_string($con, $_POST['kode_jurusan']));
        $nama_jurusan = trim(mysqli_real_escape_string($con, $_POST['nama_jurusan']));

        $cek_user = mysqli_query($con, "SELECT kode_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'")or die(mysqli_error($con));

        $rv = mysqli_num_rows($cek_user);
        if ($rv == 1) {
        echo '<script> alert ("kode_jurusan sudah terdaftar") </script>';
        }else {
            $query_simpan = mysqli_query($con, "INSERT INTO tbl_jurusan (kode_jurusan, nama_jurusan) VALUES ('$kode_jurusan','$nama_jurusan')")or die(mysqli_error($con));

            echo '<script> alert ("data berhasil di simpan");
            window.location.href = "../admin_jurusan/"</script>';
        }
}

?>