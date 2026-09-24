<?php
require_once '../database/koneksi.php';
require('../aset_web/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        if (file_exists('../aset_web/img/Logo.png')) {
            $this->Image('../aset_web/img/Logo.png', 10, 10, 30);
        }
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(80);
        $this->Cell(30, 7, 'Fakultas Sains dan Teknologi', 0, 2, 'C');
        $this->Cell(30, 7, 'Prodi Informatika', 0, 2, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 5, 'Alamat : Jalan Raya Pagojengan KM 3, Kecamatan Paguyangan,', 0, 2, 'C');
        $this->Cell(30, 5, 'Kabupaten Brebes, Jawa Tengah 52276', 0, 1, 'C');
        $this->SetLineWidth(0.8);
        $this->Line(10, 36, 200, 36);
        $this->Ln(8);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Tangkap parameter kode_kelas dari URL
$kode_kelas = isset($_GET['kode_kelas']) ? $_GET['kode_kelas'] : '';

// Query Detail Kelas Mata Kuliah
$panggil_kelas = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
$data_kelas = mysqli_fetch_array($panggil_kelas);

$nama_kelas   = isset($data_kelas['nama_kelas']) ? $data_kelas['nama_kelas'] : '-';
$kode_akd     = isset($data_kelas['kode_akd']) ? $data_kelas['kode_akd'] : '';
$kode_matkul  = isset($data_kelas['kode_matkul']) ? $data_kelas['kode_matkul'] : '';
$kode_jurusan = isset($data_kelas['kode_jurusan']) ? $data_kelas['kode_jurusan'] : '';
$nik          = isset($data_kelas['nik']) ? $data_kelas['nik'] : '';

// Query Dosen
$query_dosen = mysqli_query($con, "SELECT nama FROM tbl_dosen WHERE nik = '$nik'");
$data_dosen = mysqli_fetch_array($query_dosen);
$nama_dosen = isset($data_dosen['nama']) ? $data_dosen['nama'] : '-';

// Query Akademik
$query_akademik = mysqli_query($con, "SELECT tahun, semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'");
$data_akademik = mysqli_fetch_array($query_akademik);
$periode = isset($data_akademik['tahun']) ? $data_akademik['tahun'] . ' - ' . ($data_akademik['semester'] == 'GN' ? 'Genap' : 'Ganjil') : '-';

// Query Jurusan
$query_jurusan = mysqli_query($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'");
$data_jurusan = mysqli_fetch_array($query_jurusan);
$nama_jurusan = isset($data_jurusan['nama_jurusan']) ? $data_jurusan['nama_jurusan'] : '-';

// Query Matkul
$query_matkul = mysqli_query($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'");
$data_matkul = mysqli_fetch_array($query_matkul);
$nama_matkul = isset($data_matkul['nama_matkul']) ? $data_matkul['nama_matkul'] : '-';

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Judul Dokumen
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 7, 'LAPORAN PRESENSI PERTEMUAN KELAS', 0, 1, 'C');
$pdf->Ln(3);

// Section Header Detail Data Kelas
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(190, 6, ' DETAIL DATA KELAS MATA KULIAH', 1, 1, 'L', true);

$pdf->SetFont('Arial', '', 9);
// Baris 1
$pdf->Cell(35, 6, ' NAMA KELAS', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_kelas, 0, 0, 'L');
$pdf->Cell(30, 6, 'DOSEN', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_dosen, 0, 1, 'L');

// Baris 2
$pdf->Cell(35, 6, ' PERIODE AKADEMIK', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $periode, 0, 0, 'L');
$pdf->Cell(30, 6, 'JURUSAN', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_jurusan, 0, 1, 'L');

// Baris 3
$pdf->Cell(35, 6, ' MATA KULIAH', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(150, 6, $nama_matkul, 0, 1, 'L');

$pdf->Ln(5);

// Looping Pertemuan
$q_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas' ORDER BY pertemuan_ke ASC");

if (mysqli_num_rows($q_pertemuan) > 0) {
    while ($p = mysqli_fetch_array($q_pertemuan)) {
        $kode_pertemuan = $p['kode_pertemuan'];

        // Header tiap Pertemuan
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(190, 7, ' Pertemuan Ke-' . $p['pertemuan_ke'] . ' : ' . $p['judul_pertemuan'] . ' (' . $p['tgl'] . ')', 1, 1, 'L', true);

        // Header Tabel Mahasiswa
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(15, 6, 'No', 1, 0, 'C');
        $pdf->Cell(45, 6, 'NIM', 1, 0, 'C');
        $pdf->Cell(90, 6, 'Nama Mahasiswa', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Status Presensi', 1, 1, 'C');

        // 1. Ambil Mahasiswa Peserta Kelas 
        $q_mahasiswa = mysqli_query($con, "
            SELECT tbl_mahasiswa.nim, tbl_mahasiswa.nama 
            FROM tbl_peserta, tbl_mahasiswa 
            WHERE tbl_peserta.nim = tbl_mahasiswa.nim 
            AND tbl_peserta.kode_kelas = '$kode_kelas' 
            ORDER BY tbl_mahasiswa.nim ASC
        ") or die(mysqli_error($con));

        $pdf->SetFont('Arial', '', 9);
        if (mysqli_num_rows($q_mahasiswa) > 0) {
            $no_mhs = 1;
            while ($mhs = mysqli_fetch_array($q_mahasiswa)) {
                $nim_mhs = $mhs['nim'];

                // 2. Cek status_kehadiran di tbl_presensi secara terpisah
                $q_presensi = mysqli_query($con, "
                    SELECT status_kehadiran 
                    FROM tbl_presensi 
                    WHERE nim = '$nim_mhs' AND kode_pertemuan = '$kode_pertemuan'
                ") or die(mysqli_error($con));
                
                $data_presensi = mysqli_fetch_array($q_presensi);
                
                // Jika data ada di tbl_presensi tampilkan statusnya, jika tidak ada -> 'Belum Presensi'
                if (isset($data_presensi['status_kehadiran']) && !empty($data_presensi['status_kehadiran'])) {
                    $text_status = ucfirst($data_presensi['status_kehadiran']);
                } else {
                    $text_status = 'Belum Presensi';
                }

                $pdf->Cell(15, 6, $no_mhs++, 1, 0, 'C');
                $pdf->Cell(45, 6, $mhs['nim'], 1, 0, 'C');
                $pdf->Cell(90, 6, $mhs['nama'], 1, 0, 'L');
                $pdf->Cell(40, 6, $text_status, 1, 1, 'C');
            }
        } else {
            $pdf->Cell(190, 6, 'Belum ada data mahasiswa pada kelas ini', 1, 1, 'C');
        }

        $pdf->Ln(4);
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 7, 'Belum ada data pertemuan untuk kelas ini.', 1, 1, 'C');
}

$pdf->Output();
?>