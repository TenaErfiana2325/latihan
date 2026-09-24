<?php
require_once '../database/koneksi.php';
require('../aset_web/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../aset_web/img/Logo.png', 10, 15, 40);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 7, 'Fakultas Sains dan Teknologi', 0, 2, 'C');
        $this->Cell(30, 7, 'Prodi Informatika', 0, 2, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 5, 'Alamat :Jalan Raya Pagojengan KM 3, Kecamatan Paguyangan,', 0, 2, 'C');
        $this->Cell(30, 5, 'Kabupaten Brebes, Jawa Tengah 52276', 0, 1, 'C');
        $this->SetLineWidth(1);
        $this->Line(10, 37, 200, 37);
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(80);
$pdf-> Cell(30, 7, 'Data Mahasiswa', 0, 1, 'C');
$pdf->Ln(7);
$pdf->SetFont('Times', '', 10);
$pdf-> Cell(12, 7, 'No', 1, 0, 'C');
$pdf-> Cell(30, 7, 'Periode Akademik', 1, 0, 'C');
$pdf-> Cell(53, 7, 'Matkul', 1, 0, 'C');
$pdf-> Cell(30, 7, 'Jurusan', 1, 0, 'C');
$pdf-> Cell(40, 7, 'Dosen', 1, 0, 'C');
$pdf-> Cell(25, 7, 'Nama Kelas', 1, 1, 'C');

$query_ambil_matkul = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul")or die(mysqli_error($con));
$rv = mysqli_num_rows($query_ambil_matkul);
if ($rv > 0) {
    $no = 1;
    while ($data = mysqli_fetch_array($query_ambil_matkul)) {
        $pdf-> Cell(12, 7, $no++, 1, 0, 'C');
        $pdf-> Cell(30, 7, $data['kode_akd'], 1, 0, 'C');
        $pdf-> Cell(53, 7, $data['kode_matkul'], 1, 0, 'L');
        $pdf-> Cell(30, 7, $data['kode_jurusan'], 1, 0, 'C');
        $pdf-> Cell(40, 7, $data['nik'], 1, 0, 'L');
        $pdf-> Cell(25, 7,$data['nama_kelas'], 1, 1, 'C');

    }
}
$pdf->Output();
?>