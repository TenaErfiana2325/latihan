<?php
require_once '../database/koneksi.php';
require '../aset_web/phpexcel-xls/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
error_reporting(0);

if (isset($_POST['btn_impor'])) { //cek apakah button sudah di klik

    $kode_kelas  = trim(mysqli_real_escape_string($con, $_POST['kode_kelas']) );

    $file = $_FILES['file_excel']['name']; //nampung nama file yang diupload
    $ekstensi = explode('.', $file); //pisahkan ekstensi dari nama
    
    $nama_file = 'file'.round(microtime(true)).'.'.end($ekstensi);//buat nama file baru
    $alamat_tujuan = 'template/'.$nama_file; //buat alamat tujuan untuk menyimpan file upload
    $file_alamat_sumber = $_FILES['file_excel']['tmp_name']; //alamat sumber file yang di upload

    move_uploaded_file($file_alamat_sumber,$alamat_tujuan); //pindah file ke projek
    $file_excel = PHPExcel_IOFactory::load($alamat_tujuan); //baca excel file

    $data_excel = $file_excel->getActiveSheet()->toArray(null, true, true, true); //baca data excel dibuat menjadi array
    for ($i=2; $i <= count($data_excel) ; $i++) { //perulangan 
    //tampung data dari excel
        $nim = $data_excel[$i]['B'];
        // echo $kode_kelas;
    
    if ( $nim == '') {
        continue;
    }
    //cek kode mahasiswa di database    
    $query_cek = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE nim = '$nim' AND kode_kelas = '$kode_kelas'")or die(mysqli_error($con));
    if (mysqli_num_rows($query_cek)==0) { //ketika data kode jurusan tidak ada
        $query_insert = mysqli_query($con, "INSERT INTO tbl_peserta VALUES (null,'$kode_kelas','$nim')")or die(mysqli_error($con)); // memasukan data ke database
    
    }else {
        continue;
    }

    }
    echo '<script>alert("Impor Data berhasil");
window.location.href = "../detail_kelas_matkul/?kode_kelas='.$kode_kelas.'";</script>';

    
}
?>