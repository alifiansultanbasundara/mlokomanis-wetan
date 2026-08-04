<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Validasi Request
// ======================================

if ($_SERVER['REQUEST_METHOD'] != "POST") {

    header("Location: index.php");
    exit;
}

// ======================================
// Ambil Data
// ======================================

$id = (int) $_POST['id'];

$name = trim($_POST['name']);

$position = trim($_POST['position']);

$nip = trim($_POST['nip']);

$phone = trim($_POST['phone']);

$email = trim($_POST['email']);

$description = trim($_POST['description']);

$sort_order = (int) $_POST['sort_order'];

$status = $_POST['status'];

$old_photo = $_POST['old_photo'];

$photo = $old_photo;


// ======================================
// Validasi
// ======================================

if (

    empty($id) ||

    empty($name) ||

    empty($position)

) {

    $_SESSION['success'] = "Data belum lengkap.";

    header("Location: edit.php?id=" . $id);
    exit;
}


// ======================================
// Validasi Email
// ======================================

if (!empty($email)) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['success'] = "Format email tidak valid.";

        header("Location: edit.php?id=" . $id);
        exit;
    }
}


// ======================================
// Upload Foto Baru
// ======================================

if (!empty($_FILES['photo']['name'])) {

    $allowed = [

        "jpg",

        "jpeg",

        "png",

        "webp"

    ];

    $filename = $_FILES['photo']['name'];

    $tmp = $_FILES['photo']['tmp_name'];

    $size = $_FILES['photo']['size'];

    $ext = strtolower(

        pathinfo(

            $filename,

            PATHINFO_EXTENSION

        )

    );

    if (!in_array($ext, $allowed)) {

        $_SESSION['success'] = "Format gambar tidak didukung.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran gambar maksimal 2 MB.";

        header("Location: edit.php?id=" . $id);
        exit;
    }

    $photo = time() . "_" . uniqid() . "." . $ext;

    move_uploaded_file(

        $tmp,

        "uploads/" . $photo

    );

    // ======================================
    // Hapus Foto Lama
    // ======================================

    if (

        !empty($old_photo)

        &&

        file_exists("uploads/" . $old_photo)

    ) {

        unlink("uploads/" . $old_photo);
    }
}


// ======================================
// Update Database
// ======================================

$sql = "

UPDATE perangkat_desa

SET

name='$name',

position='$position',

nip='$nip',

phone='$phone',

email='$email',

photo='$photo',

description='$description',

sort_order='$sort_order',

status='$status'

WHERE id='$id'

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Data perangkat desa berhasil diperbarui.";
} else {

    $_SESSION['success'] = "Gagal memperbarui data.";
}

header("Location:index.php");
exit;
