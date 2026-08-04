<?php

require_once '../../../config/app.php';


// ===============================
// Hanya POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// ===============================
// Helper
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
// Ambil Data
// ===============================

$name       = clean($_POST['name']);
$position   = clean($_POST['position']);
$category   = clean($_POST['category']);

$nip        = clean($_POST['nip']);
$education  = clean($_POST['education']);

$gender     = clean($_POST['gender']);

$birth_date = !empty($_POST['birth_date'])
    ? clean($_POST['birth_date'])
    : NULL;


$address    = clean($_POST['address']);


$parent_id = !empty($_POST['parent_id'])
    ? (int) $_POST['parent_id']
    : NULL;


$sort_order = (int) ($_POST['sort_order'] ?? 0);


$status = clean($_POST['status']);





// ===============================
// Validasi
// ===============================

if (empty($name) || empty($position)) {


    $_SESSION['error'] =
        "Nama dan jabatan wajib diisi.";


    header("Location: create.php");
    exit;
}




$allowedCategory = [

    'Kepala Desa',
    'Sekretariat Desa',
    'Kepala Urusan',
    'Kepala Seksi',
    'Kepala Dusun',
    'Staf Desa',
    'BPD',
    'Lainnya'

];


if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori jabatan tidak valid.";


    header("Location: create.php");
    exit;
}





// ===============================
// Upload Foto
// ===============================

$photo = NULL;


if (!empty($_FILES['photo']['name'])) {


    $file = $_FILES['photo'];


    $allowedImage = [

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



    if (!in_array($ext, $allowedImage)) {


        $_SESSION['error'] =
            "Foto harus JPG, JPEG, PNG atau WEBP.";


        header("Location: create.php");
        exit;
    }




    if ($file['size'] > 2 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran foto maksimal 2 MB.";


        header("Location: create.php");
        exit;
    }




    $folder = APP_PATH .
        "uploads/village/officials/";



    if (!is_dir($folder)) {


        mkdir(
            $folder,
            0777,
            true
        );
    }




    $photo =
        uniqid(
            "official_",
            true
        )
        .
        "."
        .
        $ext;



    if (!move_uploaded_file(
        $file['tmp_name'],
        $folder . $photo
    )) {


        $_SESSION['error'] =
            "Upload foto gagal.";


        header("Location: create.php");
        exit;
    }
}





// ===============================
// Simpan Database
// ===============================

$sql = "

INSERT INTO village_officials

(

    name,
    position,
    category,

    nip,
    education,

    gender,

    birth_date,

    address,

    photo,

    level,

    sort_order,

    status,

    parent_id


)

VALUES

(

    '$name',

    '$position',

    '$category',


    '$nip',

    '$education',


    '$gender',


    " .
    ($birth_date ? "'$birth_date'" : "NULL")
    . ",


    '$address',


    " .
    ($photo ? "'$photo'" : "NULL")
    . ",


    0,


    '$sort_order',


    '$status',


    " .
    ($parent_id ? $parent_id : "NULL")
    . "


)

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Struktur organisasi berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan struktur organisasi.";
}



header("Location: index.php");
exit;
