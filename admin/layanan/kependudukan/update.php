<?php

require_once '../../../config/app.php';

// ======================================================
// HANYA IZINKAN POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ======================================================
// HELPER
// ======================================================

function clean($data)
{
    return trim($data ?? '');
}

// ======================================================
// AMBIL ID
// ======================================================

$id = (int) ($_POST['id'] ?? 0);

// ======================================================
// VALIDASI ID
// ======================================================

if ($id <= 0) {
    header('Location: index.php?error=invalid_id');
    exit;
}

// ======================================================
// CEK DATA PENDUDUK
// ======================================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT id
     FROM populations
     WHERE id = ?
     LIMIT 1"
);

if (!$stmt) {
    die('Prepare statement gagal: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {

    mysqli_stmt_close($stmt);

    header('Location: index.php?error=not_found');
    exit;
}

mysqli_stmt_close($stmt);

// ======================================================
// AMBIL DATA FORM
// ======================================================

$nik = clean($_POST['nik'] ?? '');
$name = clean($_POST['name'] ?? '');
$birth_place = clean($_POST['birth_place'] ?? '');
$birth_date = clean($_POST['birth_date'] ?? '');
$gender = clean($_POST['gender'] ?? '');
$religion = clean($_POST['religion'] ?? '');
$occupation = clean($_POST['occupation'] ?? '');
$address = clean($_POST['address'] ?? '');
$rt = clean($_POST['rt'] ?? '');
$rw = clean($_POST['rw'] ?? '');
$hamlet = clean($_POST['hamlet'] ?? '');

// ======================================================
// FIELD BARU
// ======================================================

$no_kk = clean($_POST['no_kk'] ?? '');
$head_of_family = clean($_POST['head_of_family'] ?? '');
$education = clean($_POST['education'] ?? '');

$marital_status = clean($_POST['marital_status'] ?? '');
$citizenship = clean($_POST['citizenship'] ?? '');

// ======================================================
// VALIDASI WAJIB
// ======================================================

if ($nik === '' || $name === '') {

    header(
        "Location: edit.php?id={$id}&error=required"
    );

    exit;
}

// ======================================================
// VALIDASI NIK
// ======================================================

if (!preg_match('/^[0-9]{16}$/', $nik)) {

    header(
        "Location: edit.php?id={$id}&error=invalid_nik"
    );

    exit;
}

// ======================================================
// VALIDASI NO KK
// ======================================================

// Jika No. KK diisi, harus 16 digit
if (
    $no_kk !== '' &&
    !preg_match('/^[0-9]{16}$/', $no_kk)
) {

    header(
        "Location: edit.php?id={$id}&error=invalid_no_kk"
    );

    exit;
}

// ======================================================
// VALIDASI TANGGAL LAHIR
// ======================================================

if ($birth_date !== '') {

    $date = DateTime::createFromFormat(
        'Y-m-d',
        $birth_date
    );

    if (
        !$date ||
        $date->format('Y-m-d') !== $birth_date
    ) {

        header(
            "Location: edit.php?id={$id}&error=invalid_birth_date"
        );

        exit;
    }
}

// ======================================================
// VALIDASI JENIS KELAMIN
// ======================================================

$allowedGender = [
    'Laki-laki',
    'Perempuan'
];

if (
    $gender !== '' &&
    !in_array($gender, $allowedGender, true)
) {

    header(
        "Location: edit.php?id={$id}&error=invalid_gender"
    );

    exit;
}

// ======================================================
// VALIDASI STATUS PERKAWINAN
// ======================================================

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
        $allowedMaritalStatus,
        true
    )
) {

    header(
        "Location: edit.php?id={$id}&error=invalid_marital_status"
    );

    exit;
}

// ======================================================
// CEK NIK DUPLIKAT
// ======================================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT id
     FROM populations
     WHERE nik = ?
     AND id != ?
     LIMIT 1"
);

if (!$stmt) {
    die('Prepare statement gagal: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    'si',
    $nik,
    $id
);

mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_close($stmt);

    header(
        "Location: edit.php?id={$id}&error=nik_exists"
    );

    exit;
}

mysqli_stmt_close($stmt);

// ======================================================
// QUERY UPDATE
// ======================================================

$query = "
    UPDATE populations SET

        nik = ?,
        name = ?,
        birth_place = NULLIF(?, ''),
        birth_date = NULLIF(?, ''),
        gender = NULLIF(?, ''),
        religion = NULLIF(?, ''),
        occupation = NULLIF(?, ''),
        address = NULLIF(?, ''),
        rt = NULLIF(?, ''),
        rw = NULLIF(?, ''),
        hamlet = NULLIF(?, ''),

        no_kk = NULLIF(?, ''),
        head_of_family = NULLIF(?, ''),
        education = NULLIF(?, ''),

        marital_status = NULLIF(?, ''),
        citizenship = NULLIF(?, '')

    WHERE id = ?
";

// ======================================================
// PREPARE UPDATE
// ======================================================

$stmt = mysqli_prepare(
    $conn,
    $query
);

if (!$stmt) {
    die('Prepare update gagal: ' . mysqli_error($conn));
}

// ======================================================
// BIND PARAMETER
// ======================================================
//
// 16 field string + 1 integer ID
//
// s = string
// i = integer
//
// ======================================================

// ======================================================
// BIND PARAMETER
// ======================================================

mysqli_stmt_bind_param(
    $stmt,
    'ssssssssssssssssi',

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
    $citizenship,

    $id
);

// ======================================================
// EXECUTE UPDATE
// ======================================================

if (!mysqli_stmt_execute($stmt)) {

    $error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);

    echo '<!DOCTYPE html>';
    echo '<html lang="id">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<title>Gagal Memperbarui Data</title>';
    echo '</head>';
    echo '<body>';

    echo '<h3 style="color:red;">UPDATE GAGAL</h3>';

    echo '<p><strong>ID:</strong> ' . htmlspecialchars($id) . '</p>';

    echo '<p><strong>Error MySQL:</strong></p>';

    echo '<pre>';
    echo htmlspecialchars($error);
    echo '</pre>';

    echo '<p>';
    echo '<a href="edit.php?id=' . htmlspecialchars($id) . '">';
    echo 'Kembali ke halaman edit';
    echo '</a>';
    echo '</p>';

    echo '</body>';
    echo '</html>';

    exit;
}

// ======================================================
// SELESAI
// ======================================================

mysqli_stmt_close($stmt);

header('Location: index.php?success=updated');
exit;
