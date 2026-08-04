<?php

require_once '../../../config/app.php';


// ===================================
// Hanya menerima POST
// ===================================

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}


// ===================================
// Ambil Data
// ===================================

$title         = trim($_POST['title']);
$slug          = trim($_POST['slug']);
$category      = trim($_POST['category']);
$description   = trim($_POST['description']);
$latitude      = trim($_POST['latitude']);
$longitude     = trim($_POST['longitude']);
$google_maps   = trim($_POST['google_maps']);
$scale         = trim($_POST['scale']);
$year          = !empty($_POST['year']) ? (int)$_POST['year'] : NULL;
$status        = $_POST['status'];
$sort_order    = (int)$_POST['sort_order'];
$created_by    = $_SESSION['id'] ?? NULL;


// ===================================
// Validasi
// ===================================

if (
    empty($title) ||
    empty($slug) ||
    empty($category)
) {

    $_SESSION['error'] = "Silakan lengkapi data yang wajib diisi.";

    header("Location: create.php");
    exit;
}



// ===================================
// Escape
// ===================================

$title       = mysqli_real_escape_string($conn, $title);
$slug        = mysqli_real_escape_string($conn, $slug);
$category    = mysqli_real_escape_string($conn, $category);
$description = mysqli_real_escape_string($conn, $description);
$latitude    = mysqli_real_escape_string($conn, $latitude);
$longitude   = mysqli_real_escape_string($conn, $longitude);
$google_maps = mysqli_real_escape_string($conn, $google_maps);
$scale       = mysqli_real_escape_string($conn, $scale);
$status      = mysqli_real_escape_string($conn, $status);



// ===================================
// Cek Slug
// ===================================

$check = mysqli_query(
    $conn,
    "SELECT id FROM regionals WHERE slug='$slug' LIMIT 1"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: create.php");
    exit;
}



// ===================================
// Folder Upload
// ===================================

$uploadDir = APP_PATH . "uploads/village/regionals/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}



// ===================================
// Upload Image
// ===================================

$image = NULL;

if (!empty($_FILES['image']['name'])) {

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Format gambar tidak didukung.";

        header("Location:create.php");
        exit;
    }

    if ($_FILES['image']['size'] > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran gambar maksimal 2 MB.";

        header("Location:create.php");
        exit;
    }

    $image = uniqid('regional_') . "." . $ext;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadDir . $image
    );
}



// ===================================
// Upload Dokumen
// ===================================

$document = NULL;

if (!empty($_FILES['document']['name'])) {

    $allowed = [
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'zip',
        'rar',
        'dwg'
    ];

    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Format file tidak didukung.";

        header("Location:create.php");
        exit;
    }

    if ($_FILES['document']['size'] > (20 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran file maksimal 20 MB.";

        header("Location:create.php");
        exit;
    }

    $document = uniqid('map_') . "." . $ext;

    move_uploaded_file(
        $_FILES['document']['tmp_name'],
        $uploadDir . $document
    );
}



// ===================================
// Simpan Database
// ===================================

$query = mysqli_query(

    $conn,

    "INSERT INTO regionals (

        title,
        slug,
        category,
        description,
        image,
        document,
        latitude,
        longitude,
        google_maps,
        scale,
        year,
        status,
        sort_order,
        created_by

    ) VALUES (

        '$title',
        '$slug',
        '$category',
        '$description',
        " . ($image ? "'$image'" : "NULL") . ",
        " . ($document ? "'$document'" : "NULL") . ",
        " . (!empty($latitude) ? "'$latitude'" : "NULL") . ",
        " . (!empty($longitude) ? "'$longitude'" : "NULL") . ",
        " . (!empty($google_maps) ? "'$google_maps'" : "NULL") . ",
        " . (!empty($scale) ? "'$scale'" : "NULL") . ",
        " . ($year ? "'$year'" : "NULL") . ",
        '$status',
        '$sort_order',
        " . ($created_by ? "'$created_by'" : "NULL") . "

    )"

);



// ===================================
// Redirect
// ===================================

if ($query) {

    $_SESSION['success'] = "Data kewilayahan berhasil ditambahkan.";
} else {

    $_SESSION['error'] = "Gagal menambahkan data.";
}

header("Location:index.php");
exit;
