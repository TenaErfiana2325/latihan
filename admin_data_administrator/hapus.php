<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/koneksi.php';
        $pengguna_login = $_SESSION['username'];
        $pengguna = @$_GET['user'];
        $cek_admin = mysqli_query($con, "SELECT COUNT(*) AS jumlah
        FROM tbl_pengguna WHERE peran='A'")or die(mysqli_error($con));//ngecek jumlah admin
        $data = mysqli_fetch_assoc($cek_admin);
        $jumlah = $data['jumlah'];
        if ($pengguna_login == $pengguna && $jumlah == 1) {
            echo '<script>alert("Anda Tidak Dapat Menghapus Akun Diri Anda Sendiri Atau Akun Admin Tinggal 1");
            window.location.href="../admin_data_administrator"
            </script>';

        }elseif ($pengguna_login != $pengguna && $jumlah == 0) {
        $hapus_pengguna = mysqli_query($con, "DELETE FROM tbl_pengguna 
        WHERE username = '$pengguna'")or die (mysqli_error($con));
        echo '<script>alert("Data Pengguna '.$pengguna.' Berhasil Dihapus");
        window.location.href="../admin_data_administrator"
        </script>';
        
        }else {
        $hapus_pengguna = mysqli_query($con, "DELETE FROM tbl_pengguna 
        WHERE username = '$pengguna'")or die (mysqli_error($con));
        echo '<script>alert("Data Pengguna '.$pengguna.' Berhasil Dihapus");
        window.location.href="../admin_data_administrator"
        </script>';
        }
        ?>
    </body>
</html>