<?php
require_once '../database/koneksi.php';

if (isset ($_POST['btn-foto'])) {
    $nik = trim(mysqli_real_escape_string($con, $_POST['nik']));
    $file = $_FILES['file_foto']['name'];
    $extensi = explode('.',$file);
    $nama_file = 'foto-dosen'.round(microtime(true)).'.'.end($extensi);
    $alamat_sumber = $_FILES['file_foto']['tmp_name'];
    $alamat_tujuan = '../aset_web/img/'.$nama_file;
    move_uploaded_file($alamat_sumber, $alamat_tujuan);

    $query_edit_foto = mysqli_query($con, "UPDATE tbl_dosen SET img ='$alamat_tujuan' Where nik ='$nik' ")or die(mysqli_error($con));

    echo '<script> 
    alert ("foto dosen berhasil di ubah");
    window.location.href = "../data_dosen";
    </script>';
}
?>