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
        $this->Cell(30, 7, 'UNIVERSITAS PERADABAN', 0, 2, 'C');
        $this->Cell(30, 7, 'FAKULTAS SAINS DAN TEKNOLOGI', 0, 2, 'C');
        // Title
        $this->Cell(30, 5, 'PRODI INFORMATIKA', 0, 2, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 5, 'alamat:Jl.Raya Pagojengan KM 3, Kecamatan Paguyangan,Kabupaten Brebes, Provinsi Jawa Tengah, kode pos 52276', 0, 2, 'C');
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
$pdf->Cell(30, 7, 'Data Mata Kuliah', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Times', '', 12);
$pdf->Cell(12, 7, 'No', 1, 0, 'C');
$pdf->Cell(50, 7, 'Kode Matkul', 1, 0, 'C');
$pdf->Cell(87, 7, 'Nama Matkul', 1, 0, 'C');
$pdf->Cell(20, 7, 'SKS', 1, 0, 'C');
$pdf->Cell(20, 7, 'CPMK', 1, 1, 'C');

$query_ambil_matkul = mysqli_query($con, "SELECT * FROM tbl_matkul")or die(mysqli_error($con));
$rv = mysqli_num_rows ($query_ambil_matkul);
if ($rv > 0) {
    $no=1;
    while ($data = mysqli_fetch_array($query_ambil_matkul)) {
        $pdf->Cell(12, 7, $no++, 1, 0, 'C');
        $pdf->Cell(50, 7, $data['kode_matkul'], 1, 0, 'L');
        $pdf->Cell(87, 7, $data['nama_matkul'], 1, 0, 'L');
        $pdf->Cell(20, 7, $data['jml_sks'], 1, 0, 'C');
        $pdf->Cell(20, 7, $data['jml_cpmk'], 1, 1, 'C');
    }
}
$pdf->Output();
?>