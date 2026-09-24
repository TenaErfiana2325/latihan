<?php
// Fix path dan nama file koneksi
require_once '../database/koneksi.php';

// Reset tabel dosen
$query_reset = mysqli_query($con, "TRUNCATE TABLE tbl_dosen") or die(mysqli_error($con));

// Fix variabel $con dan fungsi mysqli_error
$query_reset_pengguna = mysqli_query($con, "DELETE FROM tbl_pengguna WHERE peran = 'D'") or die(mysqli_error($con));

// Fix pesan alert, semicolon, dan redirect
echo '<script>
    alert("Data Dosen Berhasil Direset!");
    window.location.href="../admin_data_dosen";
</script>';
?>