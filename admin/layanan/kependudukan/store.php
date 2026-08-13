<?php

require_once '../../../config/app.php';

// ======================================================
// HANYA POST
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
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}


// ======================================================
// AMBIL DATA
// ======================================================

$nik = clean(
    $_POST['nik'] ?? ''
);

$no_kk = clean(
    $_POST['no_kk'] ?? ''
);

$name = clean(
    $_POST['name'] ?? ''
);

$head_of_family = clean(
    $_POST['head_of_family'] ?? ''
);

$birth_place = clean(
    $_POST['birth_place'] ?? ''
);

$birth_date = clean(
    $_POST['birth_date'] ?? ''
);

$gender = clean(
    $_POST['gender'] ?? 'Laki-laki'
);

$religion = clean(
    $_POST['religion'] ?? 'Islam'
);

$education = clean(
    $_POST['education'] ?? ''
);

$occupation = clean(
    $_POST['occupation'] ?? ''
);

$address = clean(
    $_POST['address'] ?? ''
);

$rt = clean(
    $_POST['rt'] ?? ''
);

$rw = clean(
    $_POST['rw'] ?? ''
);

$hamlet = clean(
    $_POST['hamlet'] ?? ''
);

$marital_status = clean(
    $_POST['marital_status'] ?? 'Belum Kawin'
);

$citizenship = clean(
    $_POST['citizenship'] ?? 'WNI'
);


// ======================================================
// VALIDASI WAJIB
// ======================================================

if (
    $nik === '' ||
    $name === ''
) {

    header(
        'Location: create.php?error=required'
    );

    exit;
}


// ======================================================
// VALIDASI NIK
// ======================================================

if (!preg_match('/^[0-9]{16}$/', $nik)) {

    header(
        'Location: create.php?error=nik'
    );

    exit;
}


// ======================================================
// VALIDASI NO. KK
// ======================================================

if (
    $no_kk !== '' &&
    !preg_match('/^[0-9]{16}$/', $no_kk)
) {

    header(
        'Location: create.php?error=no_kk'
    );

    exit;
}


// ======================================================
// VALIDASI GENDER
// ======================================================

$allowedGender = [
    'Laki-laki',
    'Perempuan'
];

if (!in_array($gender, $allowedGender, true)) {

    $gender = 'Laki-laki';
}


// ======================================================
// VALIDASI TANGGAL
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
            'Location: create.php?error=birth_date'
        );

        exit;
    }
}


// ======================================================
// CEK NIK DUPLIKAT
// ======================================================

$check = mysqli_query(
    $conn,
    "SELECT id
     FROM populations
     WHERE nik = '$nik'
     LIMIT 1"
);

if (!$check) {

    header(
        'Location: create.php?error=database'
    );

    exit;
}


if (mysqli_num_rows($check) > 0) {

    header(
        'Location: create.php?error=duplicate'
    );

    exit;
}


// ======================================================
// INSERT DATA
// ======================================================

$query = mysqli_query(
    $conn,
    "
    INSERT INTO populations (

        nik,
        no_kk,
        name,
        head_of_family,
        birth_place,
        birth_date,
        gender,
        religion,
        education,
        occupation,
        address,
        rt,
        rw,
        hamlet,
        marital_status,
        citizenship

    ) VALUES (

        '$nik',

        " .
        (
            $no_kk !== ''
            ? "'$no_kk'"
            : "NULL"
        ) . ",

        '$name',

        " .
        (
            $head_of_family !== ''
            ? "'$head_of_family'"
            : "NULL"
        ) . ",

        " .
        (
            $birth_place !== ''
            ? "'$birth_place'"
            : "NULL"
        ) . ",

        " .
        (
            $birth_date !== ''
            ? "'$birth_date'"
            : "NULL"
        ) . ",

        '$gender',

        " .
        (
            $religion !== ''
            ? "'$religion'"
            : "NULL"
        ) . ",

        " .
        (
            $education !== ''
            ? "'$education'"
            : "NULL"
        ) . ",

        " .
        (
            $occupation !== ''
            ? "'$occupation'"
            : "NULL"
        ) . ",

        " .
        (
            $address !== ''
            ? "'$address'"
            : "NULL"
        ) . ",

        " .
        (
            $rt !== ''
            ? "'$rt'"
            : "NULL"
        ) . ",

        " .
        (
            $rw !== ''
            ? "'$rw'"
            : "NULL"
        ) . ",

        " .
        (
            $hamlet !== ''
            ? "'$hamlet'"
            : "NULL"
        ) . ",

        '$marital_status',

        " .
        (
            $citizenship !== ''
            ? "'$citizenship'"
            : "NULL"
        ) . "

    )
    "
);


// ======================================================
// JIKA GAGAL
// ======================================================

if (!$query) {

    header(
        'Location: create.php?error=failed'
    );

    exit;
}


// ======================================================
// BERHASIL
// ======================================================

header(
    'Location: index.php?success=created'
);

exit;
