<?php

require_once '../../../config/app.php';


// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// =====================================
// Helper Upload
// =====================================

function uploadCover($file)
{

    global $conn;


    if (
        !isset($file) ||
        $file['error'] !== UPLOAD_ERR_OK
    ) {

        return null;
    }



    // Folder tujuan

    $folder = "../../../uploads/informasi/galeri/cover/";



    if (!is_dir($folder)) {

        mkdir(
            $folder,
            0777,
            true
        );
    }




    // Validasi extension

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

        return null;
    }




    // Nama file baru

    $filename = uniqid('cover_', true) . '.' . $ext;



    $target = $folder . $filename;



    if (move_uploaded_file(
        $file['tmp_name'],
        $target
    )) {

        return $filename;
    }


    return null;
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
    trim($_POST['description'] ?? '')
);



$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



$priority = (int) (
    $_POST['priority'] ?? 0
);



$created_by = $_SESSION['id'] ?? null;





// =====================================
// Upload Cover
// =====================================

$coverImage = uploadCover(
    $_FILES['cover_image'] ?? null
);





// =====================================
// Validasi
// =====================================


if (
    empty($title) ||
    empty($slug)
) {


    $_SESSION['error'] =
        "Judul dan slug wajib diisi.";


    header("Location: create.php");
    exit;
}







// =====================================
// Validasi Status
// =====================================


$allowedStatus = [

    'Draft',
    'Published'

];



if (!in_array(
    $status,
    $allowedStatus
)) {


    $_SESSION['error'] =
        "Status tidak valid.";


    header("Location: create.php");
    exit;
}





// =====================================
// Cek Slug
// =====================================


$check = mysqli_query(
    $conn,

    "
    SELECT id
    FROM galleries
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





// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO galleries

(
    title,
    slug,
    description,
    cover_image,
    priority,
    status,
    created_by
)

VALUES

(
    '$title',
    '$slug',
    '$description',
    '$coverImage',
    '$priority',
    '$status',
    '$created_by'
)

";





if (mysqli_query(
    $conn,
    $sql
)) {


    $_SESSION['success'] =
        "Album galeri berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan album galeri.";
}





header("Location: index.php");
exit;
