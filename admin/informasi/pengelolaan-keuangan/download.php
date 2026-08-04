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
// Ambil Data File
// =====================================

$query = mysqli_query(
    $conn,
    "
    SELECT 
        title,
        file

    FROM financial_managements

    WHERE slug='$slug'

    LIMIT 1
    "
);





if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Dokumen tidak ditemukan.";


    header("Location:index.php");
    exit;
}




$data = mysqli_fetch_assoc($query);





if (empty($data['file'])) {


    $_SESSION['error'] =
        "Dokumen belum tersedia.";


    header("Location:index.php");
    exit;
}







// =====================================
// Lokasi File
// =====================================


$filePath = APP_PATH .
    "uploads/informasi/pengelolaan-keuangan/" .
    $data['file'];






// =====================================
// Cek File
// =====================================

if (!file_exists($filePath)) {


    $_SESSION['error'] =
        "File tidak ditemukan di server.";


    header("Location:index.php");
    exit;
}







// =====================================
// Download
// =====================================


$fileName = basename(
    $data['file']
);





header(
    "Content-Type: application/pdf"
);


header(
    "Content-Disposition: attachment; filename=\"$fileName\""
);


header(
    "Content-Length: " . filesize($filePath)
);





readfile($filePath);

exit;
