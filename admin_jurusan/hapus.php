<html>
    <head>
    </head>
        <body>
        <?php 
        require_once'../database/koneksi.php';
        $kode_jurusan = @$_GET['kode_jurusan'];
        $nama_jurusan = @$_GET['nama_jurusan'];
        $cek_admin = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'")or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($cek_admin);
        $jumlah = $data['jumlah'];

        if ($jumlah == 0) {
            echo '<script> alert ("DATA MATA KULIAH TIDAK DITEMUKAN!!!");
            window.location.href="../admin_jurusan"
            </script>';

        } elseif (!empty($kode_jurusan)) {
        $hapus_jurusan = mysqli_query($con, "DELETE FROM tbl_jurusan WHERE kode_jurusan ='$kode_jurusan'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$kode_jurusan.' berhasil di hapus");
        window.location.href="../admin_jurusan"
        </script>';

         } else {
        $hapus_jurusan = mysqli_query($con, "DELETE FROM tbl_jurusan WHERE nama_jurusan ='$nama_jurusan'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$nama_jurusan.' berhasil di hapus");
        window.location.href="../admin_jurusan"
        </script>';
        }
          ?>
</body>
</html> hapus