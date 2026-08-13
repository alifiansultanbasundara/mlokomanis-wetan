<?php

require_once '../../../config/app.php';
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;


// ======================================================
// VALIDASI REQUEST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}


// ======================================================
// AMBIL DATA POST
// ======================================================

$letter_id = isset($_POST['letter_id'])
    ? (int) $_POST['letter_id']
    : 0;

$population_id = isset($_POST['population_id'])
    ? (int) $_POST['population_id']
    : 0;

$purpose = trim($_POST['purpose'] ?? '');


// ======================================================
// VALIDASI
// ======================================================

if ($letter_id <= 0) {
    die('Jenis surat tidak valid.');
}

if ($population_id <= 0) {
    die('Data penduduk tidak valid.');
}

if ($purpose === '') {
    die('Keperluan surat wajib diisi.');
}

if (mb_strlen($purpose) > 2000) {
    die('Keperluan surat terlalu panjang.');
}


// ======================================================
// AMBIL JENIS SURAT
// ======================================================

$queryLetter = mysqli_query(
    $conn,
    "
    SELECT *
    FROM letter_types
    WHERE id = '$letter_id'
      AND status = 'Aktif'
    LIMIT 1
    "
);

if (!$queryLetter) {
    die("Query letter_types gagal: " .
        mysqli_error($conn));
}

if (mysqli_num_rows($queryLetter) === 0) {
    die('Jenis surat tidak ditemukan atau tidak aktif.');
}

$letter = mysqli_fetch_assoc($queryLetter);


// ======================================================
// AMBIL DATA PENDUDUK
// ======================================================

$queryPopulation = mysqli_query(
    $conn,
    "
    SELECT *
    FROM populations
    WHERE id = '$population_id'
    LIMIT 1
    "
);

if (!$queryPopulation) {
    die("Query populations gagal: " .
        mysqli_error($conn));
}

if (mysqli_num_rows($queryPopulation) === 0) {
    die('Data penduduk tidak ditemukan.');
}

$population = mysqli_fetch_assoc($queryPopulation);


// ======================================================
// VALIDASI TEMPLATE
// ======================================================

if (empty($letter['file_path'])) {
    die('Template surat belum tersedia.');
}

$templatePath = trim($letter['file_path']);


// ======================================================
// CARI FILE TEMPLATE
// ======================================================

if (!file_exists($templatePath)) {

    $possiblePaths = [

        __DIR__ . '/../../../' .
            ltrim($templatePath, '/\\'),

        __DIR__ . '/../../../../' .
            ltrim($templatePath, '/\\'),

        $_SERVER['DOCUMENT_ROOT'] .
            '/' .
            ltrim($templatePath, '/\\'),

    ];

    foreach ($possiblePaths as $path) {

        if (file_exists($path)) {
            $templatePath = $path;
            break;
        }
    }
}


// ======================================================
// CEK FILE TEMPLATE
// ======================================================

if (!file_exists($templatePath)) {

    die("Template DOCX tidak ditemukan.");
}


// ======================================================
// VALIDASI EXTENSION TEMPLATE
// ======================================================

$templateExtension = strtolower(
    pathinfo(
        $templatePath,
        PATHINFO_EXTENSION
    )
);

if ($templateExtension !== 'docx') {
    die('Template surat harus berupa file DOCX.');
}


// ======================================================
// FOLDER HASIL
// ======================================================

$outputDir =
    __DIR__ .
    '/../../../uploads/generated-letters/';

if (!is_dir($outputDir)) {

    if (!mkdir($outputDir, 0755, true)) {
        die('Folder generated-letters gagal dibuat.');
    }
}


// ======================================================
// NAMA FILE
// ======================================================

$slug = !empty($letter['slug'])
    ? $letter['slug']
    : 'surat';

$slug = preg_replace(
    '/[^a-zA-Z0-9\-_]/',
    '-',
    $slug
);

$nik = preg_replace(
    '/[^0-9]/',
    '',
    $population['nik'] ?? ''
);

$fileName =
    $slug .
    '-' .
    ($nik ?: 'penduduk') .
    '-' .
    date('YmdHis') .
    '-' .
    bin2hex(random_bytes(4)) .
    '.docx';

$outputPath =
    $outputDir .
    $fileName;


// ======================================================
// LOAD TEMPLATE DOCX
// ======================================================

try {

    $template = new TemplateProcessor(
        $templatePath
    );
} catch (Throwable $e) {

    die("Gagal membaca template DOCX.");
}


// ======================================================
// DATA PENDUDUK
// ======================================================

$data = [

    // Identitas
    'nik' =>
    $population['nik'] ?? '',

    'name' =>
    $population['name'] ?? '',

    'birth_place' =>
    $population['birth_place'] ?? '',

    'birth_date' =>
    !empty($population['birth_date'])
        ? date(
            'd-m-Y',
            strtotime(
                $population['birth_date']
            )
        )
        : '',

    'gender' =>
    $population['gender'] ?? '',

    'religion' =>
    $population['religion'] ?? '',

    'occupation' =>
    $population['occupation'] ?? '',


    // Alamat
    'address' =>
    $population['address'] ?? '',

    'rt' =>
    $population['rt'] ?? '',

    'rw' =>
    $population['rw'] ?? '',

    'hamlet' =>
    $population['hamlet'] ?? '',


    // Data Keluarga
    'no_kk' =>
    $population['no_kk'] ?? '',

    'head_of_family' =>
    $population['head_of_family'] ?? '',


    // Pendidikan
    'education' =>
    $population['education'] ?? '',


    // Status
    'marital_status' =>
    $population['marital_status'] ?? '',

    'citizenship' =>
    $population['citizenship'] ?? '',


    // Data khusus surat
    'purpose' =>
    $purpose,
];

// ======================================================
// DATA TAMBAHAN SURAT
// ======================================================

// ======================================================
// TANGGAL INDONESIA
// ======================================================

$bulanIndonesia = [
    1  => 'Januari',
    2  => 'Februari',
    3  => 'Maret',
    4  => 'April',
    5  => 'Mei',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'Agustus',
    9  => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];

$hari = date('d');
$bulan = (int) date('m');
$tahun = date('Y');

$data['letter_name'] =
    $letter['name'] ?? '';

$data['letter_description'] =
    $letter['description'] ?? '';

$data['today'] =
    $hari . ' ' .
    $bulanIndonesia[$bulan] . ' ' .
    $tahun;

$data['year'] =
    $tahun;

// ======================================================
// ISI PLACEHOLDER DOCX
// ======================================================

foreach ($data as $key => $value) {

    $template->setValue(
        $key,
        (string) $value
    );
}


// ======================================================
// SIMPAN DOCX
// ======================================================

try {

    $template->saveAs(
        $outputPath
    );
} catch (Throwable $e) {

    die("Gagal menyimpan surat.");
}


// ======================================================
// CEK FILE
// ======================================================

if (!is_file($outputPath)) {
    die('File surat gagal dibuat.');
}


// ======================================================
// SIMPAN RIWAYAT
// ======================================================

$filePath =
    'uploads/generated-letters/' .
    $fileName;


// Escape untuk SQL
$fileNameEscaped =
    mysqli_real_escape_string(
        $conn,
        $fileName
    );

$filePathEscaped =
    mysqli_real_escape_string(
        $conn,
        $filePath
    );

$purposeEscaped =
    mysqli_real_escape_string(
        $conn,
        $purpose
    );


// ======================================================
// CREATED BY
// ======================================================

$createdBySql = 'NULL';

if (
    isset($_SESSION['user_id']) &&
    is_numeric($_SESSION['user_id'])
) {

    $createdBy =
        (int) $_SESSION['user_id'];

    $createdBySql =
        (string) $createdBy;
}


// ======================================================
// INSERT RIWAYAT
// ======================================================

$historyQuery = mysqli_query(
    $conn,
    "
    INSERT INTO generated_letters
    (
        letter_type_id,
        population_id,
        purpose,
        file_name,
        file_path,
        created_by
    )
    VALUES
    (
        '$letter_id',
        '$population_id',
        '$purposeEscaped',
        '$fileNameEscaped',
        '$filePathEscaped',
        $createdBySql
    )
    "
);


// ======================================================
// JIKA INSERT GAGAL
// ======================================================

if (!$historyQuery) {

    // Hapus file hasil agar tidak ada
    // file yatim tanpa riwayat database.

    if (file_exists($outputPath)) {
        unlink($outputPath);
    }

    die("Surat gagal dicatat ke riwayat: " .
        mysqli_error($conn));
}


// ======================================================
// REDIRECT PREVIEW
// ======================================================

header(
    "Location: preview.php?" .
        "file=" . urlencode($fileName) .
        "&letter_id=" . $letter_id .
        "&population_id=" . $population_id
);

exit;
