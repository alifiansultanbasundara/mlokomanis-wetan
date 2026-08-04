<?php

require_once '../../../config/app.php';


// =====================================
// Hanya POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// =====================================
// Ambil Data POST
// =====================================

$slug = mysqli_real_escape_string(
    $conn,
    $_POST['slug']
);


$title = mysqli_real_escape_string(
    $conn,
    trim($_POST['title'])
);


$description = mysqli_real_escape_string(
    $conn,
    trim($_POST['description'])
);


$category = mysqli_real_escape_string(
    $conn,
    $_POST['category']
);


$document_number = mysqli_real_escape_string(
    $conn,
    trim($_POST['document_number'])
);


$document_year = (int) $_POST['document_year'];


$effective_date = !empty($_POST['effective_date'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['effective_date']) . "'"
    : "NULL";


$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



$updated_by = $_SESSION['id'];




// =====================================
// Validasi
// =====================================

if (
    empty($title) ||
    empty($category) ||
    empty($document_year)
) {


    $_SESSION['error'] = "Data wajib belum lengkap.";

    header("Location: edit.php?slug=" . $slug);
    exit;
}





// =====================================
// Validasi Category
// =====================================

$allowedCategory = [

    'Peraturan Desa',
    'Peraturan Kepala Desa',
    'Keputusan Kepala Desa',
    'Surat Keputusan',
    'Instruksi',
    'SOP',
    'Dokumen Lain'

];


if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] = "Jenis produk hukum tidak valid.";

    header("Location: edit.php?slug=" . $slug);
    exit;
}





// =====================================
// Validasi Status
// =====================================

$allowedStatus = [

    'Published',
    'Draft'

];


if (!in_array($status, $allowedStatus)) {


    $_SESSION['error'] = "Status tidak valid.";

    header("Location: edit.php?slug=" . $slug);
    exit;
}




// =====================================
// Ambil Data Lama
// =====================================

$getOld = mysqli_query(
    $conn,
    "
    SELECT file
    FROM legal_instruments
    WHERE slug='$slug'
    LIMIT 1
    "
);



if (mysqli_num_rows($getOld) == 0) {


    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location:index.php");
    exit;
}


$oldData = mysqli_fetch_assoc($getOld);

$oldFile = $oldData['file'];






// =====================================
// Upload PDF Baru
// =====================================

$newFile = $oldFile;

$fileSize = null;



if (!empty($_FILES['file']['name'])) {



    $extension = strtolower(
        pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($extension !== 'pdf') {


        $_SESSION['error'] = "File harus berupa PDF.";

        header("Location: edit.php?slug=" . $slug);
        exit;
    }





    if ($_FILES['file']['size'] > (10 * 1024 * 1024)) {


        $_SESSION['error'] = "Ukuran file maksimal 10 MB.";

        header("Location: edit.php?slug=" . $slug);
        exit;
    }




    $uploadPath = APP_PATH . "uploads/informasi/produk-hukum/";



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }





    $newFile = uniqid(
        "produk_hukum_",
        true
    ) . ".pdf";





    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $uploadPath . $newFile
    )) {



        $_SESSION['error'] = "Upload file gagal.";

        header("Location: edit.php?slug=" . $slug);
        exit;
    }



    $fileSize = $_FILES['file']['size'];





    // Hapus file lama

    if (!empty($oldFile)) {


        $oldPath = APP_PATH .
            "uploads/informasi/produk-hukum/" .
            $oldFile;



        if (file_exists($oldPath)) {

            unlink($oldPath);
        }
    }
}





// =====================================
// Update Database
// =====================================

$sql = "

UPDATE legal_instruments

SET

title='$title',

description='$description',

category='$category',

document_number='$document_number',

document_year='$document_year',

effective_date=$effective_date,

file='$newFile',

" . (
    $fileSize !== null
    ? "file_size='$fileSize',"
    : ""
) . "

status='$status',

updated_by='$updated_by'

WHERE slug='$slug'

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Produk hukum berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui produk hukum.";
}





header("Location:index.php");
exit;
