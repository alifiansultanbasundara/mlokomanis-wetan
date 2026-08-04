<?php

require_once '../../../config/app.php';


// ===============================
// Validasi ID
// ===============================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: index.php");
    exit;
}


$id = (int) $_GET['id'];




// ===============================
// Ambil Data
// ===============================

$query = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_institutions
    WHERE id='$id'
    LIMIT 1
    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data lembaga tidak ditemukan.";


    header("Location: index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);





// ===============================
// Folder Upload
// ===============================

$uploadPath = APP_PATH .
    "uploads/village/institutions/";






// ===============================
// Hapus Gambar
// ===============================

if (!empty($data['image'])) {


    $imagePath =
        $uploadPath . $data['image'];



    if (file_exists($imagePath)) {


        unlink($imagePath);
    }
}







// ===============================
// Hapus Dokumen
// ===============================

if (!empty($data['document'])) {


    $documentPath =
        $uploadPath . $data['document'];



    if (file_exists($documentPath)) {


        unlink($documentPath);
    }
}







// ===============================
// Hapus Database
// ===============================

$delete = mysqli_query(

    $conn,

    "
    DELETE FROM village_institutions
    WHERE id='$id'
    "

);






if ($delete) {


    $_SESSION['success'] =
        "Lembaga desa berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus lembaga desa.";
}





header("Location:index.php");

exit;
