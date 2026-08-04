<?php

include "../auth/auth.php";
include "../config/database.php";

// ==============================
// Validasi ID
// ==============================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];


// ==============================
// Ambil Data
// ==============================

$query = mysqli_query($conn, "

SELECT *

FROM wilayah

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data wilayah tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ==============================
// Hapus Thumbnail
// ==============================

if (

    !empty($data['image']) &&

    file_exists("uploads/thumbnail/" . $data['image'])

) {

    unlink("uploads/thumbnail/" . $data['image']);
}


// ==============================
// Hapus File
// ==============================

if (

    !empty($data['file']) &&

    file_exists("uploads/files/" . $data['file'])

) {

    unlink("uploads/files/" . $data['file']);
}


// ==============================
// Hapus Database
// ==============================

$delete = mysqli_query($conn, "

DELETE FROM wilayah

WHERE id='$id'

");

if ($delete) {

    $_SESSION['success'] = "Data wilayah berhasil dihapus.";
} else {

    $_SESSION['success'] = "Gagal menghapus data wilayah.";
}

header("Location: index.php");
exit;
