<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $nim      = trim(mysqli_real_escape_string($con, $_POST['nim']) );
    $nama          = trim(mysqli_real_escape_string($con, $_POST['nama']) );
    $kontak        = trim(mysqli_real_escape_string($con, $_POST['kontak']) );
    $email         = trim(mysqli_real_escape_string($con, $_POST['email']) );
    $jenis_kelamin = trim(mysqli_real_escape_string($con, $_POST['jenis_kelamin']) );

    if ($jenis_kelamin == 'Laki-laki' || $jenis_kelamin == 'L') {
        $jenis_kelamin = 'L';
    } else if ($jenis_kelamin == 'Perempuan' || $jenis_kelamin == 'P') {
        $jenis_kelamin = 'P';
    }

    $query_edit = mysqli_query($con,"UPDATE tbl_mahasiswa SET
    nama = '$nama',
    kontak = '$kontak',
    email = '$email',
    jenis_kelamin = '$jenis_kelamin' 
    WHERE nim = '$nim'
     ")or die(mysqli_error($con));

      $query_edit_pengguna = mysqli_query($con, "UPDATE tbl_pengguna SET
        nama = '$nama' WHERE username = '$nim'") or die(mysqli_error($con));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_data_mahasiswa"</script>';
    
}
?>