<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {


    $_SESSION['error'] =
        "Data pembangunan tidak ditemukan.";


    header("Location:index.php");
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
    SELECT thumbnail

    FROM constructions

    WHERE slug='$slug'

    LIMIT 1
    "
);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Pembangunan tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);

$thumbnail = $data['thumbnail'];






// =====================================
// Hapus Thumbnail
// =====================================

if (!empty($thumbnail)) {


    $filePath = APP_PATH .
        "uploads/informasi/pembangunan/" .
        $thumbnail;



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
    DELETE FROM constructions

    WHERE slug='$slug'

    "
);





if ($delete) {


    $_SESSION['success'] =
        "Pembangunan berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus pembangunan.";
}





header("Location:index.php");
exit;
