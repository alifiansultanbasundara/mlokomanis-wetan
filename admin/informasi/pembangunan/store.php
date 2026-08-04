<?php

require_once '../../../config/app.php';


// =====================================
// Hanya POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// =====================================
// Ambil Data
// =====================================


$title = mysqli_real_escape_string(
    $conn,
    trim($_POST['title'])
);


$slug = mysqli_real_escape_string(
    $conn,
    strtolower(trim($_POST['slug']))
);


$description = mysqli_real_escape_string(
    $conn,
    trim($_POST['description'])
);


$category = mysqli_real_escape_string(
    $conn,
    $_POST['category']
);


$location = mysqli_real_escape_string(
    $conn,
    trim($_POST['location'])
);


$year = (int) $_POST['year'];


$budget = !empty($_POST['budget'])
    ? (float) $_POST['budget']
    : 0;


$funding_source = mysqli_real_escape_string(
    $conn,
    trim($_POST['funding_source'])
);


$volume = mysqli_real_escape_string(
    $conn,
    trim($_POST['volume'])
);



$start_date = !empty($_POST['start_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['start_date']) . "'"
    : "NULL";



$end_date = !empty($_POST['end_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['end_date']) . "'"
    : "NULL";



$progress = (int) $_POST['progress'];


$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



$created_by = $_SESSION['id'];




// =====================================
// Validasi
// =====================================


if (
    empty($title) ||
    empty($slug) ||
    empty($year)
) {


    $_SESSION['error'] =
        "Data wajib belum lengkap.";


    header("Location:create.php");
    exit;
}




// =====================================
// Validasi Kategori
// =====================================

$allowedCategory = [

    'Infrastruktur',
    'Sarana Prasarana',
    'Pemberdayaan',
    'Pemerintahan',
    'Lainnya'

];


if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori pembangunan tidak valid.";


    header("Location:create.php");
    exit;
}




// =====================================
// Validasi Status
// =====================================

$allowedStatus = [

    'Perencanaan',
    'Berjalan',
    'Selesai',
    'Ditunda'

];


if (!in_array($status, $allowedStatus)) {


    $_SESSION['error'] =
        "Status pembangunan tidak valid.";


    header("Location:create.php");
    exit;
}




// =====================================
// Validasi Progress
// =====================================

if ($progress < 0 || $progress > 100) {


    $_SESSION['error'] =
        "Progress harus antara 0-100%.";


    header("Location:create.php");
    exit;
}




// =====================================
// Cek Slug
// =====================================


$check = mysqli_query(
    $conn,
    "
    SELECT id
    FROM constructions
    WHERE slug='$slug'
    "
);



if (mysqli_num_rows($check) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location:create.php");
    exit;
}




// =====================================
// Upload Thumbnail
// =====================================


$thumbnail = NULL;



if (!empty($_FILES['thumbnail']['name'])) {



    $allowedImage = [

        'jpg',
        'jpeg',
        'png',
        'webp'

    ];



    $ext = strtolower(
        pathinfo(
            $_FILES['thumbnail']['name'],
            PATHINFO_EXTENSION
        )
    );




    if (!in_array($ext, $allowedImage)) {


        $_SESSION['error'] =
            "Format gambar harus JPG, PNG, atau WEBP.";


        header("Location:create.php");
        exit;
    }





    if ($_FILES['thumbnail']['size'] > (2 * 1024 * 1024)) {


        $_SESSION['error'] =
            "Ukuran gambar maksimal 2 MB.";


        header("Location:create.php");
        exit;
    }






    $uploadPath = APP_PATH .
        "uploads/informasi/pembangunan/";




    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }





    $thumbnail = uniqid(
        "pembangunan_",
        true
    ) . "." . $ext;





    if (!move_uploaded_file(
        $_FILES['thumbnail']['tmp_name'],
        $uploadPath . $thumbnail
    )) {


        $_SESSION['error'] =
            "Upload gambar gagal.";


        header("Location:create.php");
        exit;
    }
}






// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO constructions

(

title,

slug,

description,

category,

location,

year,

budget,

funding_source,

volume,

start_date,

end_date,

progress,

status,

thumbnail,

created_by

)

VALUES

(

'$title',

'$slug',

'$description',

'$category',

'$location',

'$year',

'$budget',

'$funding_source',

'$volume',

$start_date,

$end_date,

'$progress',

'$status',

" .
    (
        $thumbnail
        ? "'$thumbnail'"
        : "NULL"
    )
    . ",

'$created_by'

)

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Pembangunan berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan pembangunan.";
}





header("Location:index.php");
exit;
