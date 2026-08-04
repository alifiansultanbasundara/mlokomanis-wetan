<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    $_SESSION['error'] =
        "Data aset tidak valid.";

    header("Location:index.php");
    exit;
}



$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);




// =====================================
// Ambil Data Aset
// =====================================

$query = mysqli_query(
    $conn,

    "
    SELECT

        document_file

    FROM village_assets

    WHERE slug='$slug'

    LIMIT 1

    "
);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data aset tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);





// =====================================
// Hapus File Dokumen
// =====================================


if (!empty($data['document_file'])) {


    $filePath =
        APP_PATH .
        "uploads/informasi/aset-desa/" .
        $data['document_file'];



    if (file_exists($filePath)) {

        unlink($filePath);
    }
}







// =====================================
// Hapus Database
// =====================================


$delete = mysqli_query(
    $conn,

    "
    DELETE FROM village_assets

    WHERE slug='$slug'

    LIMIT 1

    "
);







if ($delete) {


    $_SESSION['success'] =
        "Data aset desa berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus data aset desa.";
}







header("Location:index.php");
exit;
