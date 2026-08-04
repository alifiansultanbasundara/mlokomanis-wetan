<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    $_SESSION['error'] =
        "Dokumen tidak ditemukan.";

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
        document_file

    FROM social_assistances

    WHERE slug='$slug'

    LIMIT 1

    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data bantuan sosial tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);






// =====================================
// Validasi File
// =====================================

if (empty($data['document_file'])) {


    $_SESSION['error'] =
        "Dokumen belum tersedia.";


    header("Location:detail.php?slug=" . $slug);
    exit;
}







$filePath = APP_PATH .
    "uploads/informasi/bantuan-sosial/" .
    $data['document_file'];






if (!file_exists($filePath)) {


    $_SESSION['error'] =
        "File dokumen tidak ditemukan di server.";


    header("Location:detail.php?slug=" . $slug);
    exit;
}







// =====================================
// Download File
// =====================================


$fileName = basename($data['document_file']);



header("Content-Description: File Transfer");

header("Content-Type: application/pdf");

header(
    "Content-Disposition: attachment; filename=\"" .
        $fileName .
        "\""
);


header("Content-Length: " . filesize($filePath));

header("Pragma: public");

header("Cache-Control: must-revalidate");



flush();



readfile($filePath);


exit;
