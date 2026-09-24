<?php 
require_once'../database/koneksi.php';

if (isset($_POST['btn-tambah'])) {
    
        $kode_matkul = trim(mysqli_real_escape_string($con, $_POST['kode_matkul']));
        $nama_matkul = trim(mysqli_real_escape_string($con, $_POST['nama_matkul']));
        $jml_sks = trim(mysqli_real_escape_string($con, $_POST['jml_sks']));
        $jml_cpmk= trim(mysqli_real_escape_string($con, $_POST['jml_cpmk']));

        $cek_user = mysqli_query($con, "SELECT kode_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'")or die(mysqli_error($con));

        $rv = mysqli_num_rows($cek_user);
        if ($rv == 1) {
        echo '<script> alert ("kode_matkul sudah terdaftar") </script>';
        }else {
            $query_simpan = mysqli_query($con, "INSERT INTO tbl_matkul (kode_matkul, nama_matkul, jml_sks, jml_cpmk) VALUES ('$kode_matkul','$nama_matkul','$jml_sks','$jml_cpmk')")or die(mysqli_error($con));

            echo '<script> alert ("data berhasil di simpan");
            window.location.href = "../admin_mata_kuliah/"</script>';
        }
}

?>