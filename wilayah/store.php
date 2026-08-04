<?php

include "../auth/auth.php";
include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: index.php");
    exit;
}

$title       = trim($_POST['title']);
$slug        = trim($_POST['slug']);
$type        = $_POST['type'];
$description = trim($_POST['description']);
$status      = $_POST['status'];

$author_id = $_SESSION['id'];


// =====================================
// VALIDASI
// =====================================

if (empty($title) || empty($slug)) {

    $_SESSION['success'] = "Data belum lengkap.";

    header("Location: create.php");
    exit;
}


// =====================================
// CEK SLUG
// =====================================

$cek = mysqli_query(
    $conn,
    "SELECT id FROM wilayah WHERE slug='$slug'"
);

if (mysqli_num_rows($cek) > 0) {

    $_SESSION['success'] = "Slug sudah digunakan.";

    header("Location: create.php");
    exit;
}



// =====================================
// UPLOAD THUMBNAIL
// =====================================

$image = NULL;

if (!empty($_FILES['image']['name'])) {

    $allowed = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $filename = $_FILES['image']['name'];

    $tmp = $_FILES['image']['tmp_name'];

    $size = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format thumbnail tidak didukung.";

        header("Location: create.php");
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran thumbnail maksimal 2MB.";

        header("Location: create.php");
        exit;
    }

    $image = time() . "_thumb_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $tmp,
        "uploads/thumbnail/" . $image
    );
}



// =====================================
// UPLOAD FILE
// =====================================

$file = NULL;

if (!empty($_FILES['file']['name'])) {

    $allowed = [
        "pdf",
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $filename = $_FILES['file']['name'];

    $tmp = $_FILES['file']['tmp_name'];

    $size = $_FILES['file']['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format file tidak didukung.";

        header("Location: create.php");
        exit;
    }

    if ($size > (10 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran file maksimal 10 MB.";

        header("Location: create.php");
        exit;
    }

    $file = time() . "_file_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $tmp,
        "uploads/files/" . $file
    );
}



// =====================================
// SIMPAN DATABASE
// =====================================

$sql = "

INSERT INTO wilayah
(

title,

slug,

type,

description,

image,

file,

status,

author_id

)

VALUES
(

'$title',

'$slug',

'$type',

'$description',

'$image',

'$file',

'$status',

'$author_id'

)

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Data wilayah berhasil ditambahkan.";
} else {

    $_SESSION['success'] = "Gagal menyimpan data wilayah.";
}

header("Location: index.php");
exit;
