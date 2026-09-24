<?php
require_once "../database/koneksi.php";
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_start();

$nama_file = "Data-kelas-" . date('Y-m-d');

$query_kelas = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul")or die(mysqli_error($con));

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Kelas');

// Set header cells
$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'NAMA KELAS');
$sheet->setCellValue('C1', 'KODE KELAS');
$sheet->setCellValue('D1', 'KODE AKADEMIK');
$sheet->setCellValue('E1', 'KODE MATKUL');
$sheet->setCellValue('F1', 'KODE JURUSAN');
$sheet->setCellValue('G1', 'NIK');

$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['rgb' => '808080'],
        ],
    ],
];
$sheet->getStyle('A1:G1')->applyFromArray($styleArray);
$sheet->getStyle('A1:G1')->getFont()->setBold(true);

foreach (array('B', 'C','D','E','F','G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$no = 1;
$rowNumber = 2;
while ($data = mysqli_fetch_assoc($query_kelas)) {
    $nama = $data['nama_kelas'];
    $code = $data['kode_kelas'];
    $akademik = $data['kode_akd'];
    $matkul = $data['kode_matkul'];
    $jurusan = $data['kode_jurusan'];
    $dosen = $data['nik'];
    
    

    $sheet->setCellValue("A" . $rowNumber, $no);
    $sheet->setCellValue("B" . $rowNumber, $nama);
    $sheet->setCellValue("C" . $rowNumber, $code);
    $sheet->setCellValue("D" . $rowNumber, $akademik);
    $sheet->setCellValue("E" . $rowNumber, $matkul);
    $sheet->setCellValue("F" . $rowNumber, $jurusan);
    $sheet->setCellValue("G" . $rowNumber, $dosen);
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