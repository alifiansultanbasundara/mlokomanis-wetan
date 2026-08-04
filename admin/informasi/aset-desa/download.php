<?php

require_once '../../../config/app.php';


// =====================================
// Validasi File
// =====================================

if (!isset($_GET['file']) || empty($_GET['file'])) {

    $_SESSION['error'] =
        "File tidak ditemukan.";

    header("Location:index.php");
    exit;
}



$file = basename($_GET['file']);




// =====================================
// Lokasi File
// =====================================


$filePath =
    APP_PATH .
    "uploads/informasi/aset-desa/" .
    $file;





// =====================================
// Cek File
// =====================================


if (!file_exists($filePath)) {


    $_SESSION['error'] =
        "Dokumen tidak tersedia.";


    header("Location:index.php");
    exit;
}







// =====================================
// Download
// =====================================


header(
    "Content-Type: application/pdf"
);


header(
    "Content-Disposition: attachment; filename=\"" . $file . "\""
);


header(
    "Content-Length: " . filesize($filePath)
);


header(
    "Cache-Control: private"
);





readfile($filePath);

exit;
