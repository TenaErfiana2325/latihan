<?php 
require_once '../database/koneksi.php';
$kelas = @$_GET['kode_kelas'];

$hapus_pengguna = mysqli_query($con, "DELETE FROM tbl_kelasmatkul
WHERE kode_kelas = '$kelas'")or die (mysqli_error($con));

echo '<script>alert("Data matkul '.$kelas.' Berhasil Dihapus");
window.location.href="../admin_data_kelas_makul/"
</script>';

?>
   