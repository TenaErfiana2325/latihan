<?php
error_reporting(0);
ini_set('display_errors', 1);

require_once '../aset_web/phpexcel-xls/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require_once '../database/koneksi.php';

if (isset($_POST['impor_dosen'])) {
    $file = $_FILES['file_excel']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'file' . round(microtime(true)) . '.' . end($ekstensi);

    $alamat_tujuan = 'template/' . $nama_file;
    $file_alamat_sumber = $_FILES['file_excel']['tmp_name'];

    move_uploaded_file($file_alamat_sumber, $alamat_tujuan);
    $file_excel = PHPExcel_IOFactory::load($alamat_tujuan);

    $data_excel = $file_excel->getActiveSheet()->toArray(null, true, true, true);
    
    for ($i = 2; $i <= count($data_excel); $i++) {
        $nik           = trim($data_excel[$i]['B']);
        $nama          = trim($data_excel[$i]['C']);
        $kontak        = trim($data_excel[$i]['D']);
        $email         = trim($data_excel[$i]['E']);
        $jenis_kelamin = trim($data_excel[$i]['F']);

        // Lewati jika ada baris yang kosong
        if (empty($nik) || empty($nama) || empty($kontak) || empty($email) || empty($jenis_kelamin)) {
            continue;
        }

        $query_cek = mysqli_query($con, "SELECT nik FROM tbl_dosen WHERE nik = '$nik'") or die(mysqli_error($con));

        if (mysqli_num_rows($query_cek) == 0) {
    
            $query_simpan_mhs = mysqli_query($con, "INSERT INTO tbl_dosen VALUES ('$nik', '$nama', '$kontak', '$email', '$jenis_kelamin')") or die(mysqli_error($con));

           
            $username = $nik; 
            $peran    = "D";
            $sandi    = sha1($nik);
            $pin      = "696969";

            $query_simpan_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna VALUES (NULL, '$username', '$sandi', '$peran', '$pin', '$nama')") or die(mysqli_error($con));
        }
    }
    
    echo '<script>
        alert("Proses Impor Selesai!");
        window.location.href="../admin_data_dosen";
    </script>';
}
?>