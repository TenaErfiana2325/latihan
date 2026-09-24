<?php
require_once '../database/koneksi.php';
$query_reset = mysqli_query($con, "TRUNCATE TABLE tbl_mahasiswa") or die (mysqli_error($con));

$query_reset_pengguna = mysqli_query($con,"DELETE FROM tbl_pengguna WHERE peran = 'M'") or die (mysqli_query($con));


echo '<script>alert("Data Mahasiswa Berhasil Direset");
window.location.href="../admin_data_mahasiswa"
</script>'

?>