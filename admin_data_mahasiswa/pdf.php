<?php
require_once '../database/koneksi.php';
require('../aset_web/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../aset_web/img/logo.png', 10, 15, 40);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 7, 'FAKULTAS SAINS DAN TEKNOLOGI', 0, 2, 'C');
        // Title
        $this->Cell(30, 5, 'PRODI INFORMATIKA', 0, 2, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 5, 'alamat:Jl.Raya Pagojengan KM 3, Kecamatan Paguyangan,', 0, 2, 'C');
        $this->Cell(30, 5, 'Kabupaten Brebes, Provinsi Jawa Tengah, kode pos 52276', 0, 1, 'C');
        $this->Setlinewidth(1);
        $this->Line(10, 35, 200, 35);
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
$pdf->Cell(30, 7, 'Data Mahasiswa', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Times', '', 12);
$pdf->Cell(12, 7, 'No', 1, 0, 'C');
$pdf->Cell(23, 7, 'Nim', 1, 0, 'C');
$pdf->Cell(55, 7, 'Nama', 1, 0, 'C');
$pdf->Cell(30, 7, 'Kontak', 1, 0, 'C');
$pdf->Cell(40, 7, 'Email', 1, 0, 'C');
$pdf->Cell(30, 7, 'Jenis_Kelamin', 1, 1, 'C');

$query_ambil_mhs = mysqli_query($con, "SELECT * FROM tbl_mahasiswa")or die(mysqli_error($con));
$rv = mysqli_num_rows ($query_ambil_mhs);
if ($rv > 0) {
    $no=1;
    while ($data = mysqli_fetch_array($query_ambil_mhs)) {
        $pdf->Cell(12, 7, $no++, 1, 0, 'C');
        $pdf->Cell(23, 7, $data ['nim'], 1, 0, 'L');
        $pdf->Cell(55, 7, $data['nama'], 1, 0, 'l');
        $pdf->Cell(30, 7, $data ['kontak'], 1, 0, 'L');
        $pdf->Cell(40, 7, $data ['email'], 1, 0, 'L');
        $pdf->Cell(30, 7,( $data ['jenis_kelamin'] == 'L') ? 'Laki-Laki' : 'Perempuan', 1, 1, 'L');
    }
}
$pdf->Output();
?>