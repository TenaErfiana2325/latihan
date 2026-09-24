<html>
    <head>
    </head>
    <body>
       <?php
       session_start();
       require_once '../database/koneksi.php';
      
        $query_reset = mysqli_query($con, "TRUNCATE TABLE tbl_akademik")or die(mysqli_error($con));


        echo '<script> alert("Data Mahasiswa '.$pengguna.' beserta akun loginnya Berhasil Dihapus!!!!!");
            window.location.href="../admin_periode_akademik";
        </script>';
       ?> 
    </body>
</html>