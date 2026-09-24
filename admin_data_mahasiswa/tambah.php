<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah'])) {
    $nim = trim(mysqli_real_escape_string($con, $_POST['nim']));
    $nama     = trim(mysqli_real_escape_string($con, $_POST['nama']));
    $kontak  = trim(mysqli_real_escape_string($con, $_POST['kontak']));
    $email   = trim(mysqli_real_escape_string($con, $_POST['email']));
    $jenis_kelamin  = trim(mysqli_real_escape_string($con, $_POST['jenis_kelamin'])); 

    if ($jenis_kelamin == 'Laki-laki' || $jenis_kelamin == 'L') {
        $jenis_kelamin = 'L';
    } else if ($jenis_kelamin == 'Perempuan' || $jenis_kelamin == 'P') {
        $jenis_kelamin = 'P';
    }

    $cek_user = mysqli_query($con, "SELECT nim FROM tbl_mahasiswa WHERE nim= '$nim' ")
    or die (mysqli_error($con));
    $rv = mysqli_num_rows($cek_user);

    if ($rv > 0) {
        echo '<script> alert("nim/Username Sudah Terdaftar! Input Yang Lain");
        window.location.href="../admin_data_mahasiswa" </script>';
    } else {
        $query_simpan = mysqli_query($con, "INSERT INTO tbl_mahasiswa
         (nim,
          nama, 
          kontak, 
          email, 
          jenis_kelamin) 
          VALUES
          ('$nim',
          '$nama',
          '$kontak',
          '$email',
          '$jenis_kelamin')
          ") or die (mysqli_error($con));
        
        $username = $nim;
        $sandi = sha1($nim);
        $peran = 'M';
        $pin = 123456;
        $query_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna (
            username, sandi, peran, pin, nama
        ) VALUES (
            '$username', '$sandi', '$peran', $pin, '$nama'
        )") or die(mysqli_error($con));

          echo '<script> alert("Data Berhasil Disimpan");
          window.location.href="../admin_data_mahasiswa" </script>';
    }
}
?>