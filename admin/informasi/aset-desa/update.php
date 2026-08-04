<?php

require_once '../../../config/app.php';


// =====================================
// Hanya POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");
    exit;
}



// =====================================
// Ambil Data
// =====================================

$old_slug = mysqli_real_escape_string(
    $conn,
    $_POST['old_slug']
);



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



$updated_by = $_SESSION['id'];






// =====================================
// Validasi
// =====================================

if (
    empty($title) ||
    empty($slug)
) {

    $_SESSION['error'] =
        "Nama aset wajib diisi.";

    header("Location:edit.php?slug=" . $old_slug);
    exit;
}







// =====================================
// Cek Slug
// =====================================

$checkSlug = mysqli_query(
    $conn,
    "
    SELECT id

    FROM village_assets

    WHERE slug='$slug'

    AND slug != '$old_slug'

    "
);



if (mysqli_num_rows($checkSlug) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}







// =====================================
// Ambil File Lama
// =====================================

$oldData = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "
        SELECT document_file

        FROM village_assets

        WHERE slug='$old_slug'

        LIMIT 1
        "
    )

);



$document = $oldData['document_file'];







// =====================================
// Upload PDF Baru
// =====================================


if (!empty($_FILES['document']['name'])) {


    $ext = strtolower(
        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($ext !== 'pdf') {


        $_SESSION['error'] =
            "Dokumen harus berupa PDF.";


        header("Location:edit.php?slug=" . $old_slug);
        exit;
    }





    if ($_FILES['document']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location:edit.php?slug=" . $old_slug);
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






    $newFile =
        uniqid(
            "aset_",
            true
        )
        .
        ".pdf";






    if (move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $newFile

    )) {


        // hapus file lama

        if (!empty($document)) {


            $oldFile =
                $uploadPath . $document;


            if (file_exists($oldFile)) {


                unlink($oldFile);
            }
        }



        $document = $newFile;
    }
}







// =====================================
// Update Database
// =====================================


$sql = "

UPDATE village_assets

SET

title='$title',

slug='$slug',

description='$description',

category='$category',

asset_code='$asset_code',

acquisition_year=$acquisition_year,

location='$location',

acquisition_value='$acquisition_value',

current_value='$current_value',

condition_status='$condition_status',

ownership_status='$ownership_status',

document_file=" .
    (
        $document
        ? "'$document'"
        : "NULL"
    )
    . ",

status='$status',

updated_by='$updated_by'


WHERE slug='$old_slug'

LIMIT 1


";






if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Data aset desa berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui data aset desa.";
}







header("Location:index.php");
exit;
