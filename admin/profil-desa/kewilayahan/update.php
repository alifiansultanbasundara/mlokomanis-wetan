<?php

require_once '../../../config/app.php';


// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location:index.php");
    exit;
}


// =====================================
// Ambil Data
// =====================================

$id            = (int) $_POST['id'];
$title         = trim($_POST['title']);
$slug          = trim($_POST['slug']);
$category      = trim($_POST['category']);
$description   = trim($_POST['description']);
$latitude      = trim($_POST['latitude']);
$longitude     = trim($_POST['longitude']);
$google_maps   = trim($_POST['google_maps']);
$scale         = trim($_POST['scale']);
$year          = !empty($_POST['year']) ? (int) $_POST['year'] : NULL;
$status        = trim($_POST['status']);
$sort_order    = (int) $_POST['sort_order'];
$updated_by    = $_SESSION['id'] ?? NULL;


// =====================================
// Validasi
// =====================================

if (
    empty($id) ||
    empty($title) ||
    empty($slug) ||
    empty($category)
) {

    $_SESSION['error'] = "Silakan lengkapi data yang wajib diisi.";

    header("Location:edit.php?id=" . $id);
    exit;
}


// =====================================
// Escape
// =====================================

$title       = mysqli_real_escape_string($conn, $title);
$slug        = mysqli_real_escape_string($conn, $slug);
$category    = mysqli_real_escape_string($conn, $category);
$description = mysqli_real_escape_string($conn, $description);
$latitude    = mysqli_real_escape_string($conn, $latitude);
$longitude   = mysqli_real_escape_string($conn, $longitude);
$google_maps = mysqli_real_escape_string($conn, $google_maps);
$scale       = mysqli_real_escape_string($conn, $scale);
$status      = mysqli_real_escape_string($conn, $status);


// =====================================
// Ambil Data Lama
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT *
    FROM regionals
    WHERE id='$id'
    LIMIT 1"
);

if (!$query || mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$old = mysqli_fetch_assoc($query);


// =====================================
// Validasi Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id
    FROM regionals
    WHERE slug='$slug'
    AND id != '$id'
    LIMIT 1"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location:edit.php?id=" . $id);
    exit;
}


// =====================================
// Folder Upload
// =====================================

$uploadDir = APP_PATH . "uploads/village/regionals/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}


// =====================================
// Upload Preview Baru
// =====================================

$image = $old['image'];

if (!empty($_FILES['image']['name'])) {

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Format gambar tidak didukung.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {

        $_SESSION['error'] = "Ukuran gambar maksimal 2 MB.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if (!empty($old['image']) && file_exists($uploadDir . $old['image'])) {
        unlink($uploadDir . $old['image']);
    }

    $image = uniqid('regional_') . "." . $ext;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadDir . $image
    );
}


// =====================================
// Upload Dokumen Baru
// =====================================

$document = $old['document'];

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

        $_SESSION['error'] = "Format dokumen tidak didukung.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if ($_FILES['document']['size'] > 20 * 1024 * 1024) {

        $_SESSION['error'] = "Ukuran dokumen maksimal 20 MB.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if (!empty($old['document']) && file_exists($uploadDir . $old['document'])) {
        unlink($uploadDir . $old['document']);
    }

    $document = uniqid('map_') . "." . $ext;

    move_uploaded_file(
        $_FILES['document']['tmp_name'],
        $uploadDir . $document
    );
}


// =====================================
// Update Database
// =====================================

$sql = "

UPDATE regionals SET

    title='$title',
    slug='$slug',
    category='$category',
    description='$description',

    image=" . ($image ? "'$image'" : "NULL") . ",
    document=" . ($document ? "'$document'" : "NULL") . ",

    latitude=" . (!empty($latitude) ? "'$latitude'" : "NULL") . ",
    longitude=" . (!empty($longitude) ? "'$longitude'" : "NULL") . ",
    google_maps=" . (!empty($google_maps) ? "'$google_maps'" : "NULL") . ",

    scale=" . (!empty($scale) ? "'$scale'" : "NULL") . ",
    year=" . ($year ? "'$year'" : "NULL") . ",

    status='$status',
    sort_order='$sort_order',

    updated_by=" . ($updated_by ? "'$updated_by'" : "NULL") . "

WHERE id='$id'

";

$result = mysqli_query($conn, $sql);


// =====================================
// Redirect
// =====================================

if ($result) {

    $_SESSION['success'] = "Data kewilayahan berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui data.";
}

header("Location:index.php");
exit;
