<?php

require_once '../../../config/app.php';
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ======================================================
// HANYA POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// ======================================================
// CEK FILE
// ======================================================

if (
    !isset($_FILES['excel_file']) ||
    $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK
) {
    header("Location: index.php?error=file");
    exit;
}

$file = $_FILES['excel_file'];

// ======================================================
// VALIDASI EXTENSION
// ======================================================

$extension = strtolower(
    pathinfo($file['name'], PATHINFO_EXTENSION)
);

$allowedExtensions = [
    'xlsx',
    'xls'
];

if (!in_array($extension, $allowedExtensions)) {
    header("Location: index.php?error=format");
    exit;
}

// ======================================================
// LOAD EXCEL
// ======================================================

try {

    $spreadsheet = IOFactory::load($file['tmp_name']);
} catch (Exception $e) {

    echo '<h3>File Excel tidak dapat dibaca.</h3>';

    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';

    exit;
}

$sheet = $spreadsheet->getActiveSheet();

// ======================================================
// HELPER
// ======================================================

function clean($data)
{
    return trim((string) ($data ?? ''));
}


// ======================================================
// KONVERSI NIK
// ======================================================

function cleanNik($value)
{
    $value = trim((string) ($value ?? ''));

    if ($value === '') {
        return '';
    }

    /*
     * Jika Excel membaca NIK sebagai scientific notation.
     *
     * Contoh:
     * 3.31205150185E+15
     *
     * akan dikonversi menjadi:
     * 3312051501850000
     */

    if (preg_match('/^[0-9.,]+E[+-]?[0-9]+$/i', $value)) {

        $normalized = str_replace(',', '.', $value);

        if (is_numeric($normalized)) {

            $number = sprintf(
                '%.0f',
                (float) $normalized
            );

            return $number;
        }
    }

    /*
     * Jika Excel memberikan angka biasa dalam bentuk
     * float/string.
     */

    if (is_numeric($value)) {

        /*
         * Jangan mengubah angka yang memang sudah 16 digit
         * secara sembarangan.
         */

        if (strpos($value, '.') !== false) {

            return sprintf(
                '%.0f',
                (float) $value
            );
        }
    }

    /*
     * Hilangkan karakter non angka.
     */

    return preg_replace('/[^0-9]/', '', $value);
}


// ======================================================
// KONVERSI TANGGAL EXCEL
// ======================================================

function excelDateToMysql($value)
{
    if ($value === null || $value === '') {
        return null;
    }

    // Jika objek DateTime
    if ($value instanceof \DateTimeInterface) {
        return $value->format('Y-m-d');
    }

    $value = trim((string) $value);

    if ($value === '') {
        return null;
    }

    // ==================================================
    // 1. SERIAL EXCEL
    // ==================================================

    if (is_numeric($value)) {

        try {

            $date =
                \PhpOffice\PhpSpreadsheet\Shared\Date
                ::excelToDateTimeObject((float) $value);

            return $date->format('Y-m-d');
        } catch (\Throwable $e) {

            return null;
        }
    }

    // ==================================================
    // 2. dd/mm/yyyy
    // ==================================================

    $date = \DateTime::createFromFormat(
        '!d/m/Y',
        $value
    );

    if ($date !== false) {

        return $date->format('Y-m-d');
    }

    // ==================================================
    // 3. dd-mm-yyyy
    // ==================================================

    $date = \DateTime::createFromFormat(
        '!d-m-Y',
        $value
    );

    if ($date !== false) {

        return $date->format('Y-m-d');
    }

    // ==================================================
    // 4. yyyy-mm-dd
    // ==================================================

    $date = \DateTime::createFromFormat(
        '!Y-m-d',
        $value
    );

    if ($date !== false) {

        return $date->format('Y-m-d');
    }

    return null;
}


// ======================================================
// BACA ROW EXCEL
// ======================================================

$rows = $sheet->toArray(
    null,
    true,
    true,
    true
);

// ======================================================
// CEK DATA
// ======================================================

if (count($rows) <= 1) {

    header("Location: index.php?error=empty");
    exit;
}

// ======================================================
// HASIL IMPORT
// ======================================================

$success = 0;
$failed = 0;
$skipped = 0;

$errors = [];

// ======================================================
// TRANSACTION
// ======================================================

mysqli_begin_transaction($conn);

try {

    foreach ($rows as $rowNumber => $row) {

        // ==================================================
        // LEWATI HEADER
        // ==================================================

        if ($rowNumber == 1) {
            continue;
        }

        // ==================================================
        // AMBIL DATA
        // ==================================================

        $nik = cleanNik(
            $row['A'] ?? ''
        );

        $name = clean(
            $row['B'] ?? ''
        );

        $birth_place = clean(
            $row['C'] ?? ''
        );

        $birth_date = excelDateToMysql(
            $row['D'] ?? ''
        );

        $gender = clean(
            $row['E'] ?? ''
        );

        $religion = clean(
            $row['F'] ?? ''
        );

        $occupation = clean(
            $row['G'] ?? ''
        );

        $address = clean(
            $row['H'] ?? ''
        );

        $rt = clean(
            $row['I'] ?? ''
        );

        $rw = clean(
            $row['J'] ?? ''
        );

        $hamlet = clean(
            $row['K'] ?? ''
        );

        // ==================================================
        // FIELD BARU
        // ==================================================

        $no_kk = clean(
            $row['L'] ?? ''
        );

        $head_of_family = clean(
            $row['M'] ?? ''
        );

        $education = clean(
            $row['N'] ?? ''
        );

        $marital_status = clean(
            $row['O'] ?? ''
        );

        $citizenship = clean(
            $row['P'] ?? ''
        );


        // ==================================================
        // CEK BARIS KOSONG
        // ==================================================

        if (
            $nik === '' &&
            $name === '' &&
            $birth_place === '' &&
            $birth_date === null
        ) {

            $skipped++;

            continue;
        }


        // ==================================================
        // VALIDASI NIK
        // ==================================================

        if ($nik === '') {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' => 'NIK wajib diisi.'
            ];

            continue;
        }


        if (!preg_match('/^[0-9]{16}$/', $nik)) {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                "NIK harus terdiri dari 16 digit. Nilai terbaca: {$nik}"
            ];

            continue;
        }


        // ==================================================
        // VALIDASI NAMA
        // ==================================================

        if ($name === '') {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' => 'Nama wajib diisi.'
            ];

            continue;
        }


        // ==================================================
        // VALIDASI TANGGAL
        // ==================================================

        if (
            isset($row['D']) &&
            trim((string) $row['D']) !== '' &&
            $birth_date === null
        ) {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                'Format tanggal lahir tidak valid.'
            ];

            continue;
        }


        // ==================================================
        // VALIDASI JENIS KELAMIN
        // ==================================================

        if (
            $gender !== '' &&
            !in_array(
                $gender,
                [
                    'Laki-laki',
                    'Perempuan'
                ]
            )
        ) {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                'Jenis kelamin harus Laki-laki atau Perempuan.'
            ];

            continue;
        }


        // ==================================================
        // VALIDASI STATUS PERKAWINAN
        // ==================================================

        $allowedMaritalStatus = [
            'Belum Kawin',
            'Kawin',
            'Cerai Hidup',
            'Cerai Mati'
        ];

        if (
            $marital_status !== '' &&
            !in_array(
                $marital_status,
                $allowedMaritalStatus
            )
        ) {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                "Status perkawinan tidak valid: {$marital_status}"
            ];

            continue;
        }


        // ==================================================
        // VALIDASI KEWARGANEGARAAN
        // ==================================================

        if (
            $citizenship !== '' &&
            !in_array(
                $citizenship,
                [
                    'WNI',
                    'WNA'
                ]
            )
        ) {

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                "Kewarganegaraan harus WNI atau WNA."
            ];

            continue;
        }


        // ==================================================
        // CEK NIK DUPLIKAT
        // ==================================================

        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM populations
             WHERE nik = ?
             LIMIT 1"
        );

        if (!$checkStmt) {
            throw new Exception(
                mysqli_error($conn)
            );
        }

        mysqli_stmt_bind_param(
            $checkStmt,
            's',
            $nik
        );

        mysqli_stmt_execute($checkStmt);

        mysqli_stmt_store_result(
            $checkStmt
        );

        if (
            mysqli_stmt_num_rows($checkStmt) > 0
        ) {

            mysqli_stmt_close($checkStmt);

            $skipped++;

            $errors[] = [
                'row' => $rowNumber,
                'message' =>
                "NIK {$nik} sudah terdaftar."
            ];

            continue;
        }

        mysqli_stmt_close($checkStmt);


        // ==================================================
        // INSERT
        // ==================================================

        $query = "
            INSERT INTO populations (

                nik,
                name,
                birth_place,
                birth_date,
                gender,
                religion,
                occupation,
                address,
                rt,
                rw,
                hamlet,
                no_kk,
                head_of_family,
                education,
                marital_status,
                citizenship

            ) VALUES (

                ?,
                ?,
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, ''),
                NULLIF(?, '')

            )
        ";

        $stmt = mysqli_prepare(
            $conn,
            $query
        );

        if (!$stmt) {
            throw new Exception(
                mysqli_error($conn)
            );
        }


        // 16 string + 1? Tidak.
        // Semua field di atas adalah string.
        // Total parameter = 16.

        mysqli_stmt_bind_param(
            $stmt,
            'ssssssssssssssss',

            $nik,
            $name,
            $birth_place,
            $birth_date,
            $gender,
            $religion,
            $occupation,
            $address,
            $rt,
            $rw,
            $hamlet,
            $no_kk,
            $head_of_family,
            $education,
            $marital_status,
            $citizenship
        );


        if (!mysqli_stmt_execute($stmt)) {

            $error = mysqli_stmt_error($stmt);

            mysqli_stmt_close($stmt);

            $failed++;

            $errors[] = [
                'row' => $rowNumber,
                'message' => $error
            ];

            continue;
        }


        mysqli_stmt_close($stmt);

        $success++;
    }


    // ==================================================
    // COMMIT
    // ==================================================

    mysqli_commit($conn);
} catch (Exception $e) {

    // ==================================================
    // ROLLBACK
    // ==================================================

    mysqli_rollback($conn);

    echo "<h3 style='color:red;'>Import gagal.</h3>";

    echo "<pre>";
    echo htmlspecialchars(
        $e->getMessage()
    );
    echo "</pre>";

    exit;
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Hasil Import Penduduk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-50">

    <div class="max-w-5xl mx-auto px-6 py-10">

        <!-- HEADER -->

        <div class="mb-8">

            <h1 class="text-2xl font-bold text-slate-800">
                Hasil Import Data Penduduk
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Proses import data Excel telah selesai.
            </p>

        </div>


        <!-- SUMMARY -->

        <div class="grid gap-4 sm:grid-cols-3 mb-8">

            <!-- BERHASIL -->

            <div class="rounded-xl bg-white border border-slate-200 p-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">

                        <i class="bi bi-check-lg text-xl"></i>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Berhasil
                        </p>

                        <p class="text-2xl font-bold text-slate-800">
                            <?= $success ?>
                        </p>

                    </div>

                </div>

            </div>


            <!-- GAGAL -->

            <div class="rounded-xl bg-white border border-slate-200 p-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg bg-red-100 text-red-600">

                        <i class="bi bi-x-lg text-xl"></i>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Gagal
                        </p>

                        <p class="text-2xl font-bold text-slate-800">
                            <?= $failed ?>
                        </p>

                    </div>

                </div>

            </div>


            <!-- SKIP -->

            <div class="rounded-xl bg-white border border-slate-200 p-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-100 text-amber-600">

                        <i class="bi bi-skip-forward text-xl"></i>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Dilewati
                        </p>

                        <p class="text-2xl font-bold text-slate-800">
                            <?= $skipped ?>
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- ERROR -->

        <?php if (!empty($errors)): ?>

            <div
                class="rounded-xl border border-slate-200 bg-white overflow-hidden mb-8">

                <div class="px-5 py-4 border-b border-slate-200">

                    <h2 class="font-semibold text-slate-800">
                        Detail Data yang Tidak Diproses
                    </h2>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50">

                            <tr>

                                <th
                                    class="px-5 py-3 text-left font-semibold text-slate-600">
                                    Baris
                                </th>

                                <th
                                    class="px-5 py-3 text-left font-semibold text-slate-600">
                                    Keterangan
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            <?php foreach ($errors as $error): ?>

                                <tr>

                                    <td
                                        class="px-5 py-3 font-medium text-slate-700">

                                        <?= (int) $error['row'] ?>

                                    </td>

                                    <td
                                        class="px-5 py-3 text-slate-600">

                                        <?= htmlspecialchars(
                                            $error['message']
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        <?php endif; ?>


        <!-- ACTION -->

        <div class="flex flex-wrap gap-3">

            <a
                href="index.php"
                class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">

                <i class="bi bi-arrow-left"></i>

                Kembali ke Data Penduduk

            </a>

            <a
                href="template.php"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">

                <i class="bi bi-file-earmark-excel"></i>

                Download Template

            </a>

        </div>

    </div>

</body>

</html>