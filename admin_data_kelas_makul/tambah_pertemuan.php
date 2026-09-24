<?php 
require_once '../database/koneksi.php';

if (isset($_POST['btn_tambah_pertemuan']) || isset($_POST['btn_tambah'])) {
    
    $kode_kelas       = isset($_POST['kode_kelas']) ? trim(mysqli_real_escape_string($con, $_POST['kode_kelas'])) : trim(mysqli_real_escape_string($con, $_POST['kode_akd']));
    $tgl              = isset($_POST['tanggal']) ? trim(mysqli_real_escape_string($con, $_POST['tanggal'])) : trim(mysqli_real_escape_string($con, $_POST['tgl']));
    $judul_pertemuan  = trim(mysqli_real_escape_string($con, $_POST['judul_pertemuan']));
    
    $tanggal_sekarang = date('Y-m-d');
    if ($tgl < $tanggal_sekarang) {
        echo '<script>
                alert("Data pertemuan minimal hari ini!");
                window.location.href = "../admin_data_kelas_makul/pertemuan.php?kode_kelas='.$kode_kelas.'";
              </script>';
        exit();
    }

    // 1. Cari urutan pertemuan terakhir
    $panggil_pertemuan = mysqli_query($con, "SELECT MAX(pertemuan_ke) AS pertemuan FROM tbl_pertemuan WHERE kode_kelas='$kode_kelas'") or die(mysqli_error($con));
    $data_pertemuan    = mysqli_fetch_array($panggil_pertemuan);
    
    // Tentukan nomor pertemuan selanjutnya
    $pertemuan_ke     = ($data_pertemuan['pertemuan'] ?? 0) + 1;
    $status_pertemuan = '1';

    // 2. Simpan ke tbl_pertemuan
    $simpan_pertemuan = mysqli_query($con, "INSERT INTO tbl_pertemuan (kode_kelas, tgl, judul_pertemuan, status, pertemuan_ke) VALUES ('$kode_kelas', '$tgl', '$judul_pertemuan', '$status_pertemuan', '$pertemuan_ke')") or die(mysqli_error($con));
    
    // Ambil ID Pertemuan yang baru saja dibuat
    $kode_pertemuan = mysqli_insert_id($con);

    // 3. Otomatis Generate/Masukkan Seluruh Mahasiswa dari tbl_peserta ke tbl_presensi
    $ambil_peserta = mysqli_query($con, "SELECT nim FROM tbl_peserta WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));

    if (mysqli_num_rows($ambil_peserta) > 0) {
        $status_kehadiran = 'Alfa'; // Sesuaikan enum db: 'Hadir','Alfa','Izin','Sakit'
        while ($data_peserta = mysqli_fetch_array($ambil_peserta)) {
            $nim = $data_peserta['nim'];
            mysqli_query($con, "INSERT INTO tbl_presensi (kode_pertemuan, nim, status_kehadiran) VALUES ('$kode_pertemuan', '$nim', '$status_kehadiran')") or die(mysqli_error($con));
        }
    }

    // 4. Redirect ke halaman presensi
    echo '<script>
            alert("Presensi Pertemuan ke '.$pertemuan_ke.' Berhasil Dibuat");
            window.location.href = "../admin_data_kelas_makul/presensi.php?kode_pertemuan='.$kode_pertemuan.'";
          </script>';
}
?>