<?php

require_once '../../../config/app.php';


// ===============================
// Validasi ID
// ===============================

if (!isset($_GET['id'])) {

    $_SESSION['error'] =
        "Data tidak ditemukan.";

    header("Location: index.php");
    exit;
}


$id = (int) $_GET['id'];




// ===============================
// Ambil Data Lama
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT photo
    FROM village_officials
    WHERE id='$id'
    "
);


$data = mysqli_fetch_assoc($query);



if (!$data) {

    $_SESSION['error'] =
        "Struktur organisasi tidak ditemukan.";

    header("Location: index.php");
    exit;
}




// ===============================
// Hapus Foto
// ===============================

if (!empty($data['photo'])) {


    $file = APP_PATH .
        "uploads/village/officials/" .
        $data['photo'];



    if (file_exists($file)) {

        unlink($file);
    }
}




// ===============================
// Hapus Database
// ===============================

$delete = mysqli_query(
    $conn,
    "
    DELETE FROM village_officials
    WHERE id='$id'
    "
);




if ($delete) {


    $_SESSION['success'] =
        "Struktur organisasi berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus struktur organisasi.";
}




header("Location: index.php");
exit;
