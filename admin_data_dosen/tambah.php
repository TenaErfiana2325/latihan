<?php
require_once ('../database/koneksi.php');

// Disesuaikan dengan name="btn_tambah" pada tombol submit HTML
if (isset($_POST['btn_tambah'])) {
    $nik           = trim(mysqli_real_escape_string($con, $_POST['nik']));
    $nama          = trim(mysqli_real_escape_string($con, $_POST['nama']));
    $kontak        = trim(mysqli_real_escape_string($con, $_POST['kontak']));
    $email         = trim(mysqli_real_escape_string($con, $_POST['email']));
    $jenis_kelamin = trim(mysqli_real_escape_string($con, $_POST['jenis_kelamin'])); 

    if ($jenis_kelamin == 'Laki-laki' || $jenis_kelamin == 'L') {
        $jenis_kelamin = 'L';
    } else if ($jenis_kelamin == 'Perempuan' || $jenis_kelamin == 'P') {
        $jenis_kelamin = 'P';
    }

    // Cek apakah NIK sudah ada
    $cek_user = mysqli_query($con, "SELECT nik FROM tbl_dosen WHERE nik = '$nik'") 
    or die(mysqli_error($con));
    $rv = mysqli_num_rows($cek_user);

    if ($rv > 0) {
        echo '<script> alert("NIK Sudah Terdaftar! Input Yang Lain");
        window.location.href="../admin_data_dosen"; </script>';
    } else {
        // Simpan ke tbl_dosen (ditambahkan kolom img dengan nilai '')
        $query_simpan = mysqli_query($con, "INSERT INTO tbl_dosen
         (nik, nama, kontak, email, jenis_kelamin, img) 
         VALUES
         ('$nik', '$nama', '$kontak', '$email', '$jenis_kelamin', '')") 
        or die(mysqli_error($con));

        // Simpan ke tbl_pengguna
        $username = $nik;
        $sandi    = sha1($nik);
        $peran    = 'D';
        $pin      = 696969;
        
        $query_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna 
         (username, sandi, peran, pin, nama) 
         VALUES 
         ('$username', '$sandi', '$peran', $pin, '$nama')") 
        or die(mysqli_error($con));

        echo '<script> alert("Data Berhasil Disimpan");
        window.location.href="../admin_data_dosen"; </script>';
    }
}
?>