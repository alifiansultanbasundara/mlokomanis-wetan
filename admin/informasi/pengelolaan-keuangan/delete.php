<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    header("Location:index.php");
    exit;
}



$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);





// =====================================
// Ambil Data
// =====================================

$query = mysqli_query(
    $conn,
    "
    SELECT file

    FROM financial_managements

    WHERE slug='$slug'

    LIMIT 1
    "
);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data keuangan tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);





// =====================================
// Hapus File PDF
// =====================================

if (!empty($data['file'])) {


    $filePath = APP_PATH .
        "uploads/informasi/pengelolaan-keuangan/" .
        $data['file'];



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
    DELETE FROM financial_managements

    WHERE slug='$slug'

    LIMIT 1
    "
);






if ($delete) {


    $_SESSION['success'] =
        "Data pengelolaan keuangan berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus data keuangan.";
}






header("Location:index.php");
exit;
