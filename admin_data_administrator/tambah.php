<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah'])) {
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $nama     = trim(mysqli_real_escape_string($con, $_POST['nama']));
    $peran    = trim(mysqli_real_escape_string($con, $_POST['peran']));
    $password = Sha1($username);
    $pin      = '1234';

    $cek_user = mysqli_query($con, "SELECT username FROM tbl_pengguna WHERE username= '$username' ")
    or die (mysqli_error($con));
    $rv = mysqli_num_rows($cek_user);

    if ($rv == 1) {
        echo '<script> alert("Username Sudah Terdaftar! Input Yang Lain");
        window.location.href="../admin_data_administrator" </script>';
    } else {
        $query_simpan = mysqli_query($con, "INSERT INTO tbl_pengguna
         (username,
          sandi, 
          peran, 
          pin, 
          nama) 
          VALUES
          ('$username',
          '$password',
          '$peran',
          '$pin',
          '$nama')
          ") or die (mysqli_error($con));

          echo '<script> alert("Data Berhasil Disimpan");
          window.location.href="../admin_data_administrator" </script>';
    }
}
?>