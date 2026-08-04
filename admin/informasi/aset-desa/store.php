<?php

require_once '../../../config/app.php';


// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");
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



$asset_code = mysqli_real_escape_string(
    $conn,
    trim($_POST['asset_code'])
);



$acquisition_year = !empty($_POST['acquisition_year'])
    ? (int)$_POST['acquisition_year']
    : "NULL";



$location = mysqli_real_escape_string(
    $conn,
    trim($_POST['location'])
);



$acquisition_value = !empty($_POST['acquisition_value'])
    ? (int)$_POST['acquisition_value']
    : 0;



$current_value = !empty($_POST['current_value'])
    ? (int)$_POST['current_value']
    : 0;




$condition_status = mysqli_real_escape_string(
    $conn,
    $_POST['condition_status']
);



$ownership_status = mysqli_real_escape_string(
    $conn,
    $_POST['ownership_status']
);



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
    empty($slug)
) {


    $_SESSION['error'] =
        "Nama aset wajib diisi.";


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Kategori
// =====================================


$allowedCategory = [

    'Tanah',
    'Bangunan',
    'Kendaraan',
    'Peralatan',
    'Fasilitas Umum',
    'Infrastruktur',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori aset tidak valid.";


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Kondisi
// =====================================


$allowedCondition = [

    'Baik',
    'Rusak Ringan',
    'Rusak Berat'

];



if (!in_array($condition_status, $allowedCondition)) {


    $_SESSION['error'] =
        "Kondisi aset tidak valid.";


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Status Kepemilikan
// =====================================


$allowedOwnership = [

    'Milik Desa',
    'Sewa',
    'Pinjam Pakai',
    'Lainnya'

];



if (!in_array($ownership_status, $allowedOwnership)) {


    $_SESSION['error'] =
        "Status kepemilikan tidak valid.";


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Publish
// =====================================


$allowedStatus = [

    'Published',
    'Draft'

];



if (!in_array($status, $allowedStatus)) {


    $_SESSION['error'] =
        "Status publikasi tidak valid.";


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

    FROM village_assets

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
// Upload Dokumen PDF
// =====================================


$document = NULL;



if (!empty($_FILES['document']['name'])) {


    $extension = strtolower(
        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($extension !== 'pdf') {


        $_SESSION['error'] =
            "Dokumen harus berupa PDF.";


        header("Location:create.php");
        exit;
    }





    if ($_FILES['document']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location:create.php");
        exit;
    }






    $uploadPath =
        APP_PATH .
        "uploads/informasi/aset-desa/";




    if (!is_dir($uploadPath)) {

        mkdir(
            $uploadPath,
            0777,
            true
        );
    }





    $document =
        uniqid(
            "aset_",
            true
        )
        .
        ".pdf";






    if (!move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $document

    )) {


        $_SESSION['error'] =
            "Upload dokumen gagal.";


        header("Location:create.php");
        exit;
    }
}







// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO village_assets

(

title,

slug,

description,

category,

asset_code,

acquisition_year,

location,

acquisition_value,

current_value,

condition_status,

ownership_status,

document_file,

status,

created_by

)

VALUES

(

'$title',

'$slug',

'$description',

'$category',

'$asset_code',

$acquisition_year,

'$location',

'$acquisition_value',

'$current_value',

'$condition_status',

'$ownership_status',

" .
    (
        $document
        ? "'$document'"
        : "NULL"
    )
    . ",

'$status',

'$created_by'

)

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Aset desa berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan aset desa.";
}






header("Location:index.php");
exit;
