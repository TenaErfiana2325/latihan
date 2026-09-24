<html>
    <head>
    </head>
        <body>
        <?php 
        require_once'../database/koneksi.php';
        $kode_matkul = @$_GET['kode_matkul'];
        $nama_matkul = @$_GET['nama_matkul'];
        $cek_admin = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'")or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($cek_admin);
        $jumlah = $data['jumlah'];

        if ($jumlah == 0) {
            echo '<script> alert ("DATA MATA KULIAH TIDAK DITEMUKAN!!!");
            window.location.href="../admin_mata_kuliah"
            </script>';

        } elseif (!empty($kode_matkul)) {
        $hapus_matkul = mysqli_query($con, "DELETE FROM tbl_matkul WHERE kode_matkul ='$kode_matkul'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$kode_matkul.' berhasil di hapus");
        window.location.href="../admin_mata_kuliah"
        </script>';

         } else {
        $hapus_matkul = mysqli_query($con, "DELETE FROM tbl_matkul WHERE nama_matkul ='$nama_matkul'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$nama_matkul.' berhasil di hapus");
        window.location.href="../admin_mata_kuliah"
        </script>';
        }
          ?>
</body>
</html> hapus