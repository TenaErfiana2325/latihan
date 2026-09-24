<?php 
require_once '../database/koneksi.php';
$id = @$_GET['id'];
$kode_kelas = @$_GET['kode_kelas'];
$hapus_pengguna = mysqli_query($con, "DELETE FROM tbl_peserta 
WHERE id = '$id'")or die (mysqli_error($con));

echo '<script>alert("Data mahasiswa matkul '.$id.' Berhasil Dihapus");
window.location.href = "../detail_kelas_matkul/?kode_kelas='.$kode_kelas.'";</script>';

?>
   