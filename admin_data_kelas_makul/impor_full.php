<?php
require_once '../database/koneksi.php';
require '../aset_web/phpexcel-xls/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
error_reporting(0);

if (isset($_POST['btn_impor_full'])) { //cek apakah button sudah di klik
    $file = $_FILES['file_excel']['name']; //nampung nama file yang diupload
    $ekstensi = explode('.', $file); //pisahkan ekstensi dari nama
    
    $nama_file = 'file_full'.round(microtime(true)).'.'.end($ekstensi);//buat nama file baru
    $alamat_tujuan = 'template/'.$nama_file; //buat alamat tujuan untuk menyimpan file upload
    $file_alamat_sumber = $_FILES['file_excel']['tmp_name']; //alamat sumber file yang di upload

    move_uploaded_file($file_alamat_sumber,$alamat_tujuan); //pindah file ke projek
    $file_excel = PHPExcel_IOFactory::load($alamat_tujuan); //baca excel file

    $data_excel = $file_excel->getActiveSheet()->toArray(null, true, true, true); //baca data excel dibuat menjadi array
    for ($i=2; $i <= count($data_excel) ; $i++) { //perulangan 
    //tampung data dari excel
        $akademik = $data_excel[$i]['B'];
        $matkul = $data_excel[$i]['C'];
        $jurusan = $data_excel[$i]['D'];
        $dosen = $data_excel[$i]['E'];
        $nama_kelas = $data_excel[$i]['F'];
        $mahasiswa = $data_excel[$i]['G'];
        
    
        $cek_kelas_mk = mysqli_query($con, "SELECT * FROM tbl_kelas WHERE kode_akd = '$akademik' 
        AND kode_matkul = '$matkul' 
        AND kode_jurusan = '$jurusan' 
        AND nik = '$dosen' 
        AND nama_kelas = '$nama_kelas'")or die (mysqli_error($con));
        $rv = mysqli_num_rows($cek_kelas_mk);
        
        if ($akademik=='' || $matkul=='' || $jurusan=='' || $dosen=='' || $nama_kelas=='' || $mahasiswa=='') {
            continue;
        }else{
            $cek_mahasiswa = mysqli_query($con, "SELECT nim FROM tbl_mahasiswa WHERE nim = '$mahasiswa'")or die(mysqli_error($con));
            $rv_mahasiswa = mysqli_num_rows($cek_mahasiswa);
            if ($rv_mahasiswa > 0) {
                 if ($rv == 0) {
                    $tambah_kelas_mk = mysqli_query($con, "INSERT INTO tbl_kelas VALUES
                    (null, 
                    '$akademik',
                    '$matkul',
                    '$jurusan',
                    '$dosen',
                    '$nama_kelas')
                    ")or die (mysqli_error($con));
                    $kode_kls = mysqli_insert_id($con);
                   
                    $insert_mahasiswa = mysqli_query($con, "INSERT INTO tbl_peserta VALUES
                    (null,
                    '$kode_kls',
                    '$mahasiswa')")or die(mysqli_error($con));
                    
                    
                }else {
                    $dt = mysqli_fetch_assoc($cek_kelas_mk);
                    $kode_kelas = $dt['kode_kelas'];
                    $cek_peserta = mysqli_query($con, "SELECT nim,kode_kelas FROM tbl_peserta WHERE kode_kelas = '$kode_kelas' AND nim = '$mahasiswa'")or die(mysqli_error($con));
                    $rv_peserta = mysqli_num_rows($cek_peserta);
                    if ($rv_peserta > 0) {
                        continue;
                    }else{
                    $insert_mahasiswa = mysqli_query($con, "INSERT INTO tbl_peserta VALUES
                    (null,
                    '$kode_kelas',
                    '$mahasiswa')")or die(mysqli_error($con));
                    }
                
                }
            }
           
   
    }
    }
    echo '<script>alert("Impor Data berhasil");
window.location.href = "../admin_kelas_matkul/";</script>';

    
}
?>