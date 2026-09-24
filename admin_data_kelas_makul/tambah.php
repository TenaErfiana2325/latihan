<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_tambah'])) {

    $kode_akd       = trim(mysqli_real_escape_string($con, $_POST['kode_akd']));
    $kode_matkul    = trim(mysqli_real_escape_string($con, $_POST['kode_matkul']));
    $kode_jurusan   = trim(mysqli_real_escape_string($con, $_POST['kode_jurusan']));
    $nik            = trim(mysqli_real_escape_string($con, $_POST['nik']));
    $nama_kelas     = trim(mysqli_real_escape_string($con, $_POST['nama_kelas']));
    // 1. Tangkap input bobot_presensi
    $bobot_presensi = trim(mysqli_real_escape_string($con, $_POST['bobot_presensi']));

    $cek_kelas = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_akd = '$kode_akd' AND kode_matkul = '$kode_matkul' AND kode_jurusan = '$kode_jurusan' AND nik = '$nik' AND nama_kelas = '$nama_kelas'") or die(mysqli_error($con));
    $kelas = mysqli_num_rows($cek_kelas);

    if ($kelas > 0) {
        echo '<script>alert("Data Sudah Ada");
        window.location.href = "../admin_data_kelas_makul";</script>';
    } else {
        // 2. Tentukan nama kolom secara spesifik di query INSERT agar lebih aman
        $query_simpan_matkul = mysqli_query($con, "INSERT INTO tbl_kelasmatkul (kode_akd, kode_matkul, kode_jurusan, nik, nama_kelas, bobot_presensi) VALUES ('$kode_akd', '$kode_matkul', '$kode_jurusan', '$nik', '$nama_kelas', '$bobot_presensi')") or die(mysqli_error($con));
        
        echo '<script>alert("Data Berhasil Disimpan");
        window.location.href = "../admin_data_kelas_makul";</script>';
    }   
}
?>