<?php

require_once '../../../config/app.php';


// =====================================
// Validasi Slug
// =====================================

if (!isset($_GET['slug']) || empty($_GET['slug'])) {

    $_SESSION['error'] =
        "Data bantuan sosial tidak ditemukan.";

    header("Location:index.php");
    exit;
}



$slug = mysqli_real_escape_string(
    $conn,
    $_GET['slug']
);




// =====================================
// Ambil Data Bantuan
// =====================================

$query = mysqli_query(

    $conn,

    "
    SELECT *

    FROM social_assistances

    WHERE slug='$slug'

    LIMIT 1

    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Program bantuan sosial tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);








// =====================================
// Hapus Dokumen
// =====================================


if (!empty($data['document_file'])) {


    $filePath =
        APP_PATH .
        "uploads/informasi/bantuan-sosial/" .
        $data['document_file'];



    if (file_exists($filePath)) {

        unlink($filePath);
    }
}







// =====================================
// Hapus Data Penerima
// =====================================


mysqli_query(

    $conn,

    "
    DELETE FROM social_assistance_recipients

    WHERE assistance_id='{$data['id']}'

    "

);








// =====================================
// Hapus Program Bantuan
// =====================================


$delete = mysqli_query(

    $conn,

    "
    DELETE FROM social_assistances

    WHERE id='{$data['id']}'

    "

);






if ($delete) {


    $_SESSION['success'] =
        "Program bantuan sosial berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus program bantuan sosial.";
}







header("Location:index.php");
exit;
