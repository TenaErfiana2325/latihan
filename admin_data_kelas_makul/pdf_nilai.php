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

// Function helper diletakkan DI LUAR perulangan
if (!function_exists('hitungStatus')) {
    function hitungStatus($con, $kode_kelas, $nim, $status) {
        $q = mysqli_query($con, "
            SELECT COUNT(*) as total 
            FROM tbl_presensi, tbl_pertemuan 
            WHERE tbl_presensi.kode_pertemuan = tbl_pertemuan.kode_pertemuan 
            AND tbl_pertemuan.kode_kelas = '$kode_kelas' 
            AND tbl_presensi.nim = '$nim' 
            AND LOWER(tbl_presensi.status_kehadiran) = '$status'
        ");
        $d = mysqli_fetch_array($q);
        return $d['total'];
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

// Bobot Presensi ditetapkan 15%
$bobot_presensi = 15; 

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

// Hitung Total Pertemuan Kelas Ini
$q_total_pertemuan = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas'");
$d_total_pertemuan = mysqli_fetch_array($q_total_pertemuan);
$total_pertemuan   = $d_total_pertemuan['total'] > 0 ? $d_total_pertemuan['total'] : 1; 

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Judul Dokumen
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 7, 'REKAPITULASI PRESENSI & NILAI KEHADIRAN', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 9);
// Baris 1
$pdf->Cell(35, 6, ' NAMA KELAS', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_kelas, 0, 0, 'L');
$pdf->Cell(35, 6, 'DOSEN', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_dosen, 0, 1, 'L');

// Baris 2
$pdf->Cell(35, 6, ' PERIODE AKADEMIK', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $periode, 0, 0, 'L');
$pdf->Cell(35, 6, 'JURUSAN', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_jurusan, 0, 1, 'L');

// Baris 3
$pdf->Cell(35, 6, ' MATA KULIAH', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_matkul, 0, 0, 'L');
$pdf->Cell(35, 6, 'TOTAL PERTEMUAN', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $total_pertemuan . ' Pertemuan', 0, 1, 'L');

$pdf->Ln(5);

// SECTION REKAPITULASI TABEL MAHASISWA
$pdf->SetFont('Arial', 'B', 8);

// Header Tabel (Polos tanpa fill)
$pdf->Cell(10, 7, 'No', 1, 0, 'C', false);
$pdf->Cell(25, 7, 'NIM', 1, 0, 'C', false);
$pdf->Cell(55, 7, 'Nama Mahasiswa', 1, 0, 'C', false);
$pdf->Cell(15, 7, 'Hadir', 1, 0, 'C', false);
$pdf->Cell(15, 7, 'Izin', 1, 0, 'C', false);
$pdf->Cell(15, 7, 'Sakit', 1, 0, 'C', false);
$pdf->Cell(15, 7, 'Alfa', 1, 0, 'C', false);
$pdf->Cell(22, 7, 'Kehadiran (%)', 1, 0, 'C', false);
$pdf->Cell(18, 7, 'Nilai', 1, 1, 'C', false);

// Query Data Mahasiswa di Kelas Ini
$q_rekap_mhs = mysqli_query($con, "
    SELECT tbl_mahasiswa.nim, tbl_mahasiswa.nama 
    FROM tbl_peserta, tbl_mahasiswa 
    WHERE tbl_peserta.nim = tbl_mahasiswa.nim 
    AND tbl_peserta.kode_kelas = '$kode_kelas' 
    ORDER BY tbl_mahasiswa.nim ASC
");

$pdf->SetFont('Arial', '', 8);
if (mysqli_num_rows($q_rekap_mhs) > 0) {
    $no_rekap = 1;
    while ($rm = mysqli_fetch_array($q_rekap_mhs)) {
        $nim_rekap = $rm['nim'];

        // Panggil fungsi hitung status
        $jml_hadir = hitungStatus($con, $kode_kelas, $nim_rekap, 'hadir');
        $jml_izin  = hitungStatus($con, $kode_kelas, $nim_rekap, 'izin');
        $jml_sakit = hitungStatus($con, $kode_kelas, $nim_rekap, 'sakit');
        $jml_alfa  = hitungStatus($con, $kode_kelas, $nim_rekap, 'alfa');

        // Rumus Perhitungan
        $persentase_kehadiran = ($jml_hadir / $total_pertemuan) * 100;
        $nilai_kehadiran      = $persentase_kehadiran * ($bobot_presensi / 100);

        // Output Baris Tabel Polos
        $pdf->Cell(10, 6, $no_rekap++, 1, 0, 'C');
        $pdf->Cell(25, 6, $rm['nim'], 1, 0, 'C');
        $pdf->Cell(55, 6, $rm['nama'], 1, 0, 'L');
        $pdf->Cell(15, 6, $jml_hadir, 1, 0, 'C');
        $pdf->Cell(15, 6, $jml_izin, 1, 0, 'C');
        $pdf->Cell(15, 6, $jml_sakit, 1, 0, 'C');
        $pdf->Cell(15, 6, $jml_alfa, 1, 0, 'C');
        $pdf->Cell(22, 6, number_format($persentase_kehadiran, 1) . '%', 1, 0, 'C');
        $pdf->Cell(18, 6, number_format($nilai_kehadiran, 2), 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 7, 'Belum ada data mahasiswa pada kelas ini.', 1, 1, 'C');
}

$pdf->Output();
?>