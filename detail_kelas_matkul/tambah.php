<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_tambah'])) {

    $kode_kelas = trim(mysqli_real_escape_string($con, $_POST['kode_kelas']));
    $nim        = trim(mysqli_real_escape_string($con, $_POST['nim']));

    // Ambil kode_matkul dari tbl_kelasmatkul
    $get_kelas   = mysqli_query($con, "SELECT kode_matkul FROM tbl_kelasmatkul WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
    $data_kelas  = mysqli_fetch_array($get_kelas);
    $kode_matkul = $data_kelas['kode_matkul'] ?? '';

    // Cek apakah mahasiswa sudah terdaftar di kelas ini
    $cek_kelas = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE kode_kelas = '$kode_kelas' AND nim = '$nim'") or die(mysqli_error($con));
    
    if (mysqli_num_rows($cek_kelas) > 0) {
        echo '<script>alert("Mahasiswa dengan NIM tersebut sudah ada di kelas ini!");
        window.location.href = "../detail_kelas_matkul/?kode_kelas='.$kode_kelas.'";</script>';
    } else {
        // 1. Simpan data peserta ke tbl_peserta
        $query_simpan = mysqli_query($con, "INSERT INTO tbl_peserta (kode_kelas, kode_matkul, nim) VALUES ('$kode_kelas', '$kode_matkul', '$nim')") or die(mysqli_error($con));
        
        // 2. OTOMATIS: Tambahkan mahasiswa ini ke tbl_presensi untuk setiap pertemuan yang sudah dibuat di kelas ini
        $get_pertemuan = mysqli_query($con, "SELECT kode_pertemuan FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
        
        while ($pertemuan = mysqli_fetch_array($get_pertemuan)) {
            $kode_pertemuan = $pertemuan['kode_pertemuan'];
            
            // Cek agar tidak double insert
            $cek_presensi = mysqli_query($con, "SELECT * FROM tbl_presensi WHERE kode_pertemuan = '$kode_pertemuan' AND nim = '$nim'") or die(mysqli_error($con));
            
            if (mysqli_num_rows($cek_presensi) == 0) {
                mysqli_query($con, "INSERT INTO tbl_presensi (kode_pertemuan, nim, status_kehadiran) VALUES ('$kode_pertemuan', '$nim', 'Alfa')") or die(mysqli_error($con));
            }
        }

        echo '<script>alert("Data Berhasil Disimpan");
        window.location.href = "../detail_kelas_matkul/?kode_kelas='.$kode_kelas.'";</script>';
    }   
}
?>