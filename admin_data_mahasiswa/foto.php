<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn-foto'])) {
    $nim= trim(mysqli_real_escape_string($con,$_POST['nim']));
    $file= $_FILES['file_foto']['name'];
    $extensi= explode('.', $file);

    $nama_file ='foto-mhs'.round(microtime(true)).'.'.end($extensi);
    $alamat_sumber=$_FILES['file_foto']['tmp_name'];
    $alamat_tujuan='../aset_web/img/'.$nama_file;
    move_uploaded_file($alamat_sumber, $alamat_tujuan);

    $query_edit_foto= mysqli_query ($con, "UPDATE tbl_mahasiswa SET img='$alamat_tujuan' WHERE nim='$nim'") or die (mysqli_error($con));
    
    echo'<script>
        alert("Foto Mahasiswa Berhasil Di Ubah");
        window.location.href="../admin_data_mahasiswa"</script>';

}
?>