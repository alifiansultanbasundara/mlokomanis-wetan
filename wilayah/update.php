<?php

include "../auth/auth.php";
include "../config/database.php";

// ==============================
// Validasi Request
// ==============================

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: index.php");
    exit;
}

$id          = (int) $_POST['id'];
$title       = trim($_POST['title']);
$slug        = trim($_POST['slug']);
$type        = $_POST['type'];
$description = trim($_POST['description']);
$status      = $_POST['status'];

$old_image = $_POST['old_image'];
$old_file  = $_POST['old_file'];

$image = $old_image;
$file  = $old_file;


// ==============================
// Validasi
// ==============================

if (
    empty($title) ||
    empty($slug)
) {

    $_SESSION['success'] = "Data belum lengkap.";

    header("Location: edit.php?id=" . $id);
    exit;
}



// ==============================
// Cek Slug
// ==============================

$cek = mysqli_query(
    $conn,
    "SELECT id
     FROM wilayah
     WHERE slug='$slug'
     AND id!='$id'"
);

if (mysqli_num_rows($cek) > 0) {

    $_SESSION['success'] = "Slug sudah digunakan.";

    header("Location: edit.php?id=" . $id);
    exit;
}



// ==============================
// Upload Thumbnail Baru
// ==============================

if (!empty($_FILES['image']['name'])) {

    $allowed = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $filename = $_FILES['image']['name'];
    $tmp      = $_FILES['image']['tmp_name'];
    $size     = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format thumbnail tidak didukung.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran thumbnail maksimal 2 MB.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    $image = time() . "_thumb_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $tmp,
        "uploads/thumbnail/" . $image
    );

    if (
        !empty($old_image) &&
        file_exists("uploads/thumbnail/" . $old_image)
    ) {

        unlink("uploads/thumbnail/" . $old_image);
    }
}



// ==============================
// Upload File Baru
// ==============================

if (!empty($_FILES['file']['name'])) {

    $allowed = [
        "pdf",
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $filename = $_FILES['file']['name'];
    $tmp      = $_FILES['file']['tmp_name'];
    $size     = $_FILES['file']['size'];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format file tidak didukung.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    if ($size > (10 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran file maksimal 10 MB.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    $file = time() . "_file_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $tmp,
        "uploads/files/" . $file
    );

    if (
        !empty($old_file) &&
        file_exists("uploads/files/" . $old_file)
    ) {

        unlink("uploads/files/" . $old_file);
    }
}



// ==============================
// Update Database
// ==============================

$sql = "

UPDATE wilayah

SET

title='$title',

slug='$slug',

type='$type',

description='$description',

image='$image',

file='$file',

status='$status'

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Data wilayah berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui data wilayah.";
}

header("Location: index.php");
exit;
