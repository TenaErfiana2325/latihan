<?php
require_once "../database/koneksi.php";
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_start();

$nama_file = "Data-M-" . date('Y-m-d');

$query_mahasiswa = mysqli_query($con, "SELECT * FROM tbl_mahasiswa")or die(mysqli_error($con));

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data mahasiswa');

// Set header cells
$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'Nim');
$sheet->setCellValue('C1', 'Nama');
$sheet->setCellValue('D1', 'Kontak');
$sheet->setCellValue('E1', 'Email');
$sheet->setCellValue('F1', 'Jenis Kelamin');


$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['rgb' => '808080'],
        ],
    ],
];
$sheet->getStyle('A1:F1')->applyFromArray($styleArray);
$sheet->getStyle('A1:F1')->getFont()->setBold(true);

foreach (array('B', 'C', 'D', 'E', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$no = 1;
$rowNumber = 2;
while ($data = mysqli_fetch_assoc($query_mahasiswa)) {
    $nim = $data['nim'];
    $nama = $data['nama'];
    $kontak = $data['kontak'];
    $email = $data['email'];
    $jenis_kelamin = $data['jenis_kelamin'];


    $sheet->setCellValue("A" . $rowNumber, $no);
    $sheet->setCellValue("B" . $rowNumber, $nim);
    $sheet->setCellValue("C" . $rowNumber, $nama);
     $sheet->setCellValue("D" . $rowNumber, $kontak);
    $sheet->setCellValue("E" . $rowNumber, $email);
    $sheet->setCellValue("F" . $rowNumber, $jenis_kelamin);
    $rowNumber++;
    $no++;
}

// Buat file excel
$filename = $nama_file . ".xlsx";
$writer = new Xlsx($spreadsheet);

ob_end_clean(); // Bersihkan output buffer

// Atur header untuk pengunduhan file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>