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



$updated_by = $_SESSION['id'];





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


    header("Location:edit.php?slug=$old_slug");
    exit;
}





// =====================================
// Cek Slug Duplikat
// =====================================


$check = mysqli_query(
    $conn,
    "
    SELECT id
    FROM constructions
    WHERE slug='$slug'
    AND slug != '$old_slug'
    "
);



if (mysqli_num_rows($check) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location:edit.php?slug=$old_slug");
    exit;
}




// =====================================
// Ambil Thumbnail Lama
// =====================================


$oldData = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT thumbnail
        FROM constructions
        WHERE slug='$old_slug'
        LIMIT 1
        "
    )
);



$thumbnail = $oldData['thumbnail'];




// =====================================
// Upload Thumbnail Baru
// =====================================


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
            "Format gambar harus JPG, PNG atau WEBP.";


        header("Location:edit.php?slug=$old_slug");
        exit;
    }




    if ($_FILES['thumbnail']['size'] > (2 * 1024 * 1024)) {


        $_SESSION['error'] =
            "Ukuran gambar maksimal 2 MB.";


        header("Location:edit.php?slug=$old_slug");
        exit;
    }





    $uploadPath = APP_PATH .
        "uploads/informasi/pembangunan/";




    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }




    $newThumbnail = uniqid(
        "pembangunan_",
        true
    ) . "." . $ext;




    if (!move_uploaded_file(
        $_FILES['thumbnail']['tmp_name'],
        $uploadPath . $newThumbnail
    )) {


        $_SESSION['error'] =
            "Upload gambar gagal.";


        header("Location:edit.php?slug=$old_slug");
        exit;
    }




    // hapus gambar lama

    if (!empty($thumbnail)) {


        $oldFile = $uploadPath . $thumbnail;


        if (file_exists($oldFile)) {

            unlink($oldFile);
        }
    }



    $thumbnail = $newThumbnail;
}





// =====================================
// Update Database
// =====================================


$sql = "

UPDATE constructions SET


title='$title',

slug='$slug',

description='$description',

category='$category',

location='$location',

year='$year',

budget='$budget',

funding_source='$funding_source',

volume='$volume',

start_date=$start_date,

end_date=$end_date,

progress='$progress',

status='$status',

thumbnail=" .
    (
        $thumbnail
        ? "'$thumbnail'"
        : "NULL"
    )
    . ",

updated_by='$updated_by'


WHERE slug='$old_slug'


";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Pembangunan berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui pembangunan.";
}





header("Location:index.php");
exit;
