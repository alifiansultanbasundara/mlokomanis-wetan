<?php

require_once '../../../config/app.php';


// ===============================
// Hanya menerima POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// ===============================
// Ambil Data
// ===============================

$name = mysqli_real_escape_string(
    $conn,
    trim($_POST['name'])
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



$chairman = mysqli_real_escape_string(
    $conn,
    trim($_POST['chairman'])
);



$secretary = mysqli_real_escape_string(
    $conn,
    trim($_POST['secretary'])
);



$phone = mysqli_real_escape_string(
    $conn,
    trim($_POST['phone'])
);



$email = mysqli_real_escape_string(
    $conn,
    trim($_POST['email'])
);



$total_members = (int) $_POST['total_members'];



$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



$sort_order = (int) $_POST['sort_order'];



$created_by = $_SESSION['id'] ?? null;





// ===============================
// Validasi
// ===============================

if (empty($name) || empty($slug)) {


    $_SESSION['error'] =
        "Nama lembaga wajib diisi.";


    header("Location: create.php");
    exit;
}




// ===============================
// Validasi Kategori
// ===============================

$allowedCategory = [

    'BPD',
    'LPMD',
    'PKK',
    'Karang Taruna',
    'RT/RW',
    'Posyandu',
    'Kelompok Tani',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori lembaga tidak valid.";


    header("Location: create.php");
    exit;
}





// ===============================
// Validasi Status
// ===============================

$allowedStatus = [

    'Active',
    'Inactive'

];



if (!in_array($status, $allowedStatus)) {


    $_SESSION['error'] =
        "Status lembaga tidak valid.";


    header("Location: create.php");
    exit;
}





// ===============================
// Cek Slug
// ===============================

$check = mysqli_query(
    $conn,

    "
    SELECT id
    FROM village_institutions
    WHERE slug='$slug'
    LIMIT 1
    "

);



if (mysqli_num_rows($check) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location: create.php");
    exit;
}







// ===============================
// Upload Image
// ===============================

$image = null;



if (!empty($_FILES['image']['name'])) {



    $allowedImage = [

        'jpg',
        'jpeg',
        'png',
        'webp'

    ];



    $ext = strtolower(
        pathinfo(
            $_FILES['image']['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowedImage)) {


        $_SESSION['error'] =
            "Format gambar harus JPG, PNG atau WEBP.";


        header("Location: create.php");
        exit;
    }



    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran gambar maksimal 2 MB.";


        header("Location: create.php");
        exit;
    }





    $uploadPath = APP_PATH .
        "uploads/village/institutions/";



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }




    $image =
        uniqid("institution_", true)
        . "." . $ext;




    if (!move_uploaded_file(

        $_FILES['image']['tmp_name'],

        $uploadPath . $image

    )) {


        $_SESSION['error'] =
            "Upload gambar gagal.";


        header("Location: create.php");
        exit;
    }
}







// ===============================
// Upload Document
// ===============================

$document = null;



if (!empty($_FILES['document']['name'])) {



    $allowedDoc = [

        'pdf',
        'doc',
        'docx'

    ];



    $ext = strtolower(
        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowedDoc)) {


        $_SESSION['error'] =
            "Dokumen harus PDF, DOC atau DOCX.";


        header("Location: create.php");
        exit;
    }



    if ($_FILES['document']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location: create.php");
        exit;
    }




    $uploadPath = APP_PATH .
        "uploads/village/institutions/";



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }




    $document =
        uniqid("document_", true)
        . "." . $ext;




    if (!move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $document

    )) {


        $_SESSION['error'] =
            "Upload dokumen gagal.";


        header("Location: create.php");
        exit;
    }
}







// ===============================
// Insert Database
// ===============================

$sql = "

INSERT INTO village_institutions

(

name,
slug,
category,
description,
chairman,
secretary,
phone,
email,
total_members,
image,
document,
status,
sort_order,
created_by

)

VALUES

(

'$name',
'$slug',
'$category',
'$description',
'$chairman',
'$secretary',
'$phone',
'$email',
'$total_members',
" . ($image ? "'$image'" : "NULL") . ",
" . ($document ? "'$document'" : "NULL") . ",
'$status',
'$sort_order',
" . ($created_by ? "'$created_by'" : "NULL") . "

)

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Lembaga desa berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan lembaga desa.";
}






header("Location:index.php");
exit;
