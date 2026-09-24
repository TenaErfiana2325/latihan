<html>
    <head>

    </head>
        <body>

        <?php 
        require_once'../database/koneksi.php';
        $kode_akd = @$_GET['kode_akd'];
        $semester = @$_GET['semester'];
        $cek_admin = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_akademik WHERE kode_akd = '$kode_akd'")or die(mysqli_error($con));
        $data = mysqli_fetch_assoc($cek_admin);
        $jumlah = $data['jumlah'];

        if ($jumlah == 0) {
            echo '<script> alert ("DATA MATA KULIAH TIDAK DITEMUKAN!!!");
            window.location.href="../admin_periode_akademik"
            </script>';

        } elseif (!empty($kode_akd)) {
        $hapus = mysqli_query($con, "DELETE FROM tbl_akademik WHERE kode_akd ='$kode_akd'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$kode_akd.' berhasil di hapus");
        window.location.href="../admin_periode_akademik"
        </script>';

         } else {
        $hapus = mysqli_query($con, "DELETE FROM tbl_akademik WHERE semester ='$semester'")or die (mysqli_error($con));
        echo '<script> alert ("data mata kuliah '.$semester.' berhasil di hapus");
        window.location.href="../admin_periode_akademik"
        </script>';
        }
          ?>
</body>
</html>