<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {
    $id             = trim(mysqli_real_escape_string($con, $_POST['id']));
    $kode_akd       = trim(mysqli_real_escape_string($con, $_POST['kode_akd']));
    $kode_matkul    = trim(mysqli_real_escape_string($con, $_POST['kode_matkul']));
    $kode_jurusan   = trim(mysqli_real_escape_string($con, $_POST['kode_jurusan']));
    $nik            = trim(mysqli_real_escape_string($con, $_POST['nik']));
    $nama_kelas     = trim(mysqli_real_escape_string($con, $_POST['nama_kelas']));
    $bobot_presensi = trim(mysqli_real_escape_string($con, $_POST['bobot_presensi']));

    $query_update = mysqli_query($con, "UPDATE tbl_kelasmatkul SET 
        kode_akd = '$kode_akd',
        kode_matkul = '$kode_matkul',
        kode_jurusan = '$kode_jurusan',
        nik = '$nik',
        nama_kelas = '$nama_kelas',
        bobot_presensi = '$bobot_presensi'
        WHERE id = '$id'") or die(mysqli_error($con));

    if ($query_update) {
        echo '<script>alert("Data Berhasil Diubah"); window.location.href="../admin_data_kelas_makul";</script>';
    } else {
        echo '<script>alert("Gagal Mengubah Data"); window.location.href="../admin_data_kelas_makul";</script>';
    }
}
?>