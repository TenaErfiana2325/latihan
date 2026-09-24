<?php
error_reporting(0);

require_once '../aset_web/phpexcel-xls/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once '../database/koneksi.php';

if (isset($_POST['impor_mhs'])) {
    $file = $_FILES['file_excel']['name'];
    $ekstensi = explode('.',$file);
    $nama_file = 'file' . round(microtime(true)) . '.' . end($ekstensi);

    $alamat_tujuan = 'template/' . $nama_file;
    $file_alamat_sumber = $_FILES['file_excel']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);
    $file_excel = PHPExcel_IOFactory::load($alamat_tujuan);

    $data_excel = $file_excel->getActiveSheet()->toArray(null, true, true, true);
    for ($i = 2; $i <= count($data_excel); $i++) {
        $nim = $data_excel[$i]['B'];
        $nama = $data_excel[$i]['C'];
        $kontak  = $data_excel[$i]['D'];
        $email = $data_excel[$i]['E'];
        $jenis_kelamin = $data_excel[$i]['F'];

        // Lewati jika baris kosong
        if ($nim == ''|| $nama == '' || $kontak == '' || $email == '' || $jenis_kelamin == '') {
            continue;
        }
        
        $query_cek = mysqli_query($con, "SELECT nim FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
        
        // PERBAIKAN: Hanya gunakan 1 blok insert yang valid & bersih
        if (mysqli_num_rows($query_cek) == 0) {
            // 1. Simpan ke tabel mahasiswa
            $query_simpan_mhs = mysqli_query($con, "INSERT INTO tbl_mahasiswa VALUES ('$nim', '$nama', '$kontak', '$email', '$jenis_kelamin')") or die(mysqli_error($con));
            
            // 2. Simpan ke tabel pengguna
            $username = $nim; // Didefinisikan agar tidak undefined
            $peran = "M";
            $sandi = sha1($nim);
            $pin = "123456";
            
            $query_simpan_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna VALUES (NULL, '$username', '$sandi', '$peran', '$pin', '$nama')") or die(mysqli_error($con));
        }
    }

    echo '<script>
        alert("Proses Impor Selesai!");
        window.location.href="../admin_data_mahasiswa";
    </script>';
}
?>