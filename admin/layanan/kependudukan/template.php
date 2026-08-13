<?php

require_once '../../../config/app.php';

// ======================================================
// CEK LIBRARY
// ======================================================

require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ======================================================
// BUAT SPREADSHEET
// ======================================================

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Data Penduduk');

// ======================================================
// HEADER
// ======================================================

$headers = [

    'A1' => 'NIK',
    'B1' => 'Nama',
    'C1' => 'Tempat Lahir',
    'D1' => 'Tanggal Lahir',
    'E1' => 'Jenis Kelamin',
    'F1' => 'Agama',
    'G1' => 'Pekerjaan',
    'H1' => 'Alamat',
    'I1' => 'RT',
    'J1' => 'RW',
    'K1' => 'Dusun',

    // FIELD BARU
    'L1' => 'No. KK',
    'M1' => 'Kepala Keluarga',
    'N1' => 'Pendidikan',

    'O1' => 'Status Perkawinan',
    'P1' => 'Kewarganegaraan',

];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// ======================================================
// CONTOH DATA
// ======================================================

$example = [

    'A2' => '3207342601020003',
    'B2' => 'Nama Penduduk',
    'C2' => 'Cianjur',
    'D2' => '2000-01-01',
    'E2' => 'Laki-laki',
    'F2' => 'Islam',
    'G2' => 'Petani',
    'H2' => 'Dusun Krajan',
    'I2' => '001',
    'J2' => '002',
    'K2' => 'Dusun Krajan',

    // FIELD BARU
    'L2' => '3207342601020001',
    'M2' => 'Nama Kepala Keluarga',
    'N2' => 'SMA',

    'O2' => 'Belum Kawin',
    'P2' => 'WNI',

];

foreach ($example as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// ======================================================
// STYLE HEADER
// ======================================================

$headerStyle = [

    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FFFFFF'
        ],
    ],

    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '0F766E'
        ],
    ],

    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],

    'borders' => [

        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => [
                'rgb' => 'D1D5DB'
            ],
        ],

    ],

];

$sheet
    ->getStyle('A1:P1')
    ->applyFromArray($headerStyle);

// ======================================================
// STYLE DATA
// ======================================================

$sheet
    ->getStyle('A2:P2')
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

$sheet
    ->getStyle('A2:P2')
    ->getBorders()
    ->getAllBorders()
    ->getColor()
    ->setRGB('E5E7EB');

// ======================================================
// FORMAT TEXT
// ======================================================

// NIK
$sheet
    ->getStyle('A:A')
    ->getNumberFormat()
    ->setFormatCode('@');

// No. KK
$sheet
    ->getStyle('L:L')
    ->getNumberFormat()
    ->setFormatCode('@');

// RT & RW
$sheet
    ->getStyle('I:J')
    ->getNumberFormat()
    ->setFormatCode('@');

// ======================================================
// FORMAT TANGGAL
// ======================================================

$sheet
    ->getStyle('D:D')
    ->getNumberFormat()
    ->setFormatCode('yyyy-mm-dd');

// ======================================================
// FREEZE HEADER
// ======================================================

$sheet->freezePane('A2');

// ======================================================
// FILTER
// ======================================================

$sheet->setAutoFilter('A1:P2');

// ======================================================
// LEBAR KOLOM
// ======================================================

$widths = [

    'A' => 22,
    'B' => 30,
    'C' => 20,
    'D' => 16,
    'E' => 18,
    'F' => 18,
    'G' => 25,
    'H' => 35,
    'I' => 10,
    'J' => 10,
    'K' => 20,

    // FIELD BARU
    'L' => 22,
    'M' => 30,
    'N' => 25,

    'O' => 22,
    'P' => 20,

];

foreach ($widths as $column => $width) {

    $sheet
        ->getColumnDimension($column)
        ->setWidth($width);
}

// ======================================================
// TINGGI HEADER
// ======================================================

$sheet
    ->getRowDimension(1)
    ->setRowHeight(25);

// ======================================================
// DOWNLOAD
// ======================================================

$filename = 'template-import-penduduk.xlsx';

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment; filename="' . $filename . '"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);

$writer->save('php://output');

exit;
