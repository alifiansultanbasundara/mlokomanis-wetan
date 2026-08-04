<?php

require_once '../../../config/app.php';


// ===============================
// POST ONLY
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// ===============================
// Helper Escape
// ===============================

function clean($data)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}



// ===============================
// Ambil Data Form
// ===============================

$village_name = clean($_POST['village_name']);
$village_head = clean($_POST['village_head']);

$description = clean($_POST['description']);
$history     = clean($_POST['history']);

$vision  = clean($_POST['vision']);
$mission = clean($_POST['mission']);

$office_address = clean($_POST['office_address']);

$latitude  = clean($_POST['latitude']);
$longitude = clean($_POST['longitude']);

$google_maps = clean($_POST['google_maps']);

$total_areas      = (int) $_POST['total_areas'];
$total_hamlets    = (int) $_POST['total_hamlets'];
$total_rw          = (int) $_POST['total_rw'];
$total_rt          = (int) $_POST['total_rt'];
$total_population = (int) $_POST['total_population'];


$north_boundary = clean($_POST['north_boundary']);
$east_boundary  = clean($_POST['east_boundary']);
$south_boundary = clean($_POST['south_boundary']);
$west_boundary  = clean($_POST['west_boundary']);



// ===============================
// Validasi
// ===============================

if (empty($village_name)) {

    $_SESSION['error'] = "Nama desa wajib diisi.";

    header("Location: edit.php");
    exit;
}





// ===============================
// Ambil Data Lama
// ===============================

$old = mysqli_query($conn, "

    SELECT *
    FROM village_profiles
    LIMIT 1

");


$existing = mysqli_fetch_assoc($old);


$office_photo = $existing['office_photo'] ?? '';





// ===============================
// Upload Foto
// ===============================

if (!empty($_FILES['office_photo']['name'])) {


    $file = $_FILES['office_photo'];


    $allowed = [

        'jpg',
        'jpeg',
        'png',
        'webp'

    ];


    $ext = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowed)) {


        $_SESSION['error'] =
            "Foto harus JPG, JPEG, PNG atau WEBP.";


        header("Location: edit.php");
        exit;
    }



    if ($file['size'] > 5 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran foto maksimal 5 MB.";


        header("Location: edit.php");
        exit;
    }




    $folder = APP_PATH .
        "uploads/village/";



    if (!is_dir($folder)) {

        mkdir(
            $folder,
            0777,
            true
        );
    }



    $filename =
        uniqid('village_', true)
        . '.'
        . $ext;



    if (
        move_uploaded_file(
            $file['tmp_name'],
            $folder . $filename
        )
    ) {

        $office_photo = $filename;



        // hapus foto lama

        if (
            !empty($existing['office_photo'])
            &&
            file_exists(
                $folder . $existing['office_photo']
            )
        ) {

            unlink(
                $folder . $existing['office_photo']
            );
        }
    }
}





// ===============================
// Cek Apakah Data Sudah Ada
// ===============================


$count = mysqli_query(
    $conn,
    "
    SELECT id
    FROM village_profiles
    LIMIT 1
    "
);



if (mysqli_num_rows($count) > 0) {



    // ===============================
    // UPDATE
    // ===============================


    $sql = "

    UPDATE village_profiles SET

        village_name='$village_name',
        village_head='$village_head',
        office_photo='$office_photo',

        description='$description',
        history='$history',

        vision='$vision',
        mission='$mission',

        office_address='$office_address',

        latitude='$latitude',
        longitude='$longitude',

        google_maps='$google_maps',


        total_areas='$total_areas',
        total_hamlets='$total_hamlets',
        total_rw='$total_rw',
        total_rt='$total_rt',
        total_population='$total_population',


        north_boundary='$north_boundary',
        east_boundary='$east_boundary',
        south_boundary='$south_boundary',
        west_boundary='$west_boundary'


    LIMIT 1

    ";
} else {


    // ===============================
    // INSERT
    // ===============================


    $sql = "

    INSERT INTO village_profiles

    (

        village_name,
        village_head,
        office_photo,

        description,
        history,

        vision,
        mission,

        office_address,

        latitude,
        longitude,

        google_maps,


        total_areas,
        total_hamlets,
        total_rw,
        total_rt,
        total_population,


        north_boundary,
        east_boundary,
        south_boundary,
        west_boundary

    )


    VALUES

    (

        '$village_name',
        '$village_head',
        '$office_photo',

        '$description',
        '$history',

        '$vision',
        '$mission',

        '$office_address',

        '$latitude',
        '$longitude',

        '$google_maps',


        '$total_areas',
        '$total_hamlets',
        '$total_rw',
        '$total_rt',
        '$total_population',


        '$north_boundary',
        '$east_boundary',
        '$south_boundary',
        '$west_boundary'

    )

    ";
}





// ===============================
// Execute
// ===============================


if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Profil desa berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal menyimpan profil desa.";
}



header("Location: index.php");
exit;
