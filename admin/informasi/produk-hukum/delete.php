<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location: index.php");
    exit;
}


$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);



// =====================================
// Ambil Data Lama
// =====================================

$query = mysqli_query(
    $conn,
    "
    SELECT file
    FROM legal_instruments
    WHERE slug='$slug'
    LIMIT 1
    "
);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] = "Produk hukum tidak ditemukan.";

    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);

$file = $data['file'];




// =====================================
// Hapus File PDF
// =====================================

if (!empty($file)) {


    $filePath = APP_PATH .
        "uploads/informasi/produk-hukum/" .
        $file;



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
    DELETE FROM legal_instruments
    WHERE slug='$slug'
    "
);




if ($delete) {


    $_SESSION['success'] =
        "Produk hukum berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus produk hukum.";
}





header("Location:index.php");
exit;
