<?php

require_once '../../../config/app.php';


// =====================================
// Hanya POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");
    exit;
}



// =====================================
// Ambil Data
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



$fiscal_year = (int) $_POST['fiscal_year'];



$total_budget = (float) $_POST['total_budget'];


$realization = (float) $_POST['realization'];



$funding_source = mysqli_real_escape_string(
    $conn,
    $_POST['funding_source']
);



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
    empty($fiscal_year)
) {


    $_SESSION['error'] =
        "Data wajib belum lengkap.";


    header(
        "Location:edit.php?slug=" . $slug
    );

    exit;
}









// =====================================
// Ambil File Lama
// =====================================


$getData = mysqli_query(
    $conn,
    "
    SELECT file

    FROM financial_managements

    WHERE slug='$slug'

    LIMIT 1
    "
);



if (!$getData || mysqli_num_rows($getData) == 0) {


    $_SESSION['error'] =
        "Data keuangan tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$oldData = mysqli_fetch_assoc($getData);



$oldFile = $oldData['file'];



$newFile = $oldFile;


$file_size = 0;







// =====================================
// Upload PDF Baru
// =====================================


if (!empty($_FILES['file']['name'])) {


    $ext = strtolower(
        pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($ext !== 'pdf') {


        $_SESSION['error'] =
            "File harus PDF.";


        header(
            "Location:edit.php?slug=" . $slug
        );

        exit;
    }





    if ($_FILES['file']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran file maksimal 10 MB.";


        header(
            "Location:edit.php?slug=" . $slug
        );

        exit;
    }







    $uploadPath = APP_PATH .
        "uploads/informasi/pengelolaan-keuangan/";




    if (!is_dir($uploadPath)) {


        mkdir(
            $uploadPath,
            0777,
            true
        );
    }





    $newFile = uniqid(
        'keuangan_',
        true
    ) . '.pdf';



    $file_size =
        $_FILES['file']['size'];





    if (!move_uploaded_file(

        $_FILES['file']['tmp_name'],

        $uploadPath . $newFile

    )) {


        $_SESSION['error'] =
            "Upload file gagal.";


        header(
            "Location:edit.php?slug=" . $slug
        );

        exit;
    }







    // Hapus file lama

    if (!empty($oldFile)) {


        $oldPath =
            $uploadPath . $oldFile;



        if (file_exists($oldPath)) {


            unlink($oldPath);
        }
    }
}







// =====================================
// Update Database
// =====================================


$sql = "

UPDATE financial_managements

SET


title='$title',

description='$description',

category='$category',

fiscal_year='$fiscal_year',

total_budget='$total_budget',

realization='$realization',

funding_source='$funding_source',

file='$newFile',

file_size='$file_size',

status='$status',

updated_by='$updated_by'


WHERE slug='$slug'


";







if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Data keuangan berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui data keuangan.";
}







header("Location:detail.php?slug=" . $slug);

exit;
