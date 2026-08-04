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

$name = trim($_POST['name']);
$position = trim($_POST['position']);
$nip = trim($_POST['nip']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$description = trim($_POST['description']);
$sort_order = !empty($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
$status = $_POST['status'];

$photo = NULL;


// ======================================
// Validasi
// ======================================

if (

    empty($name) ||

    empty($position)

) {

    $_SESSION['success'] = "Nama dan jabatan wajib diisi.";

    header("Location: create.php");
    exit;
}


// ======================================
// Validasi Email
// ======================================

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['success'] = "Format email tidak valid.";

    header("Location: create.php");
    exit;
}


// ======================================
// Upload Foto
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

        $_SESSION['success'] = "Format foto tidak didukung.";

        header("Location:create.php");
        exit;
    }

    if ($size > (2 * 1024 * 1024)) {

        $_SESSION['success'] = "Ukuran foto maksimal 2 MB.";

        header("Location:create.php");
        exit;
    }

    $photo = time() . "_" . uniqid() . "." . $ext;

    move_uploaded_file(

        $tmp,

        "uploads/" . $photo

    );
}


// ======================================
// Simpan Database
// ======================================

$sql = "

INSERT INTO perangkat_desa(

name,

position,

nip,

phone,

email,

photo,

description,

sort_order,

status

)

VALUES(

'$name',

'$position',

'$nip',

'$phone',

'$email',

'$photo',

'$description',

'$sort_order',

'$status'

)

";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Data perangkat desa berhasil ditambahkan.";
} else {

    $_SESSION['success'] = "Gagal menyimpan data perangkat desa.";
}

header("Location:index.php");
exit;
