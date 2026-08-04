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
// Ambil Data
// =====================================


$title = mysqli_real_escape_string(
    $conn,
    trim($_POST['title'])
);


$slug = mysqli_real_escape_string(
    $conn,
    strtolower(trim($_POST['slug']))
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



$created_by = $_SESSION['id'];





// =====================================
// Generate Slug
// =====================================

if (empty($slug)) {


    $slug = strtolower($title);


    $slug = preg_replace(
        '/[^a-z0-9]+/',
        '-',
        $slug
    );


    $slug = trim(
        $slug,
        '-'
    );
}






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


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Kategori
// =====================================


$allowedCategory = [

    'APBDes',
    'Pendapatan Desa',
    'Belanja Desa',
    'Pembiayaan Desa',
    'Realisasi Anggaran',
    'Laporan Keuangan',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori keuangan tidak valid.";


    header("Location:create.php");
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


    $_SESSION['error'] =
        "Status tidak valid.";


    header("Location:create.php");
    exit;
}








// =====================================
// Cek Slug
// =====================================


$check = mysqli_query(
    $conn,
    "
    SELECT id

    FROM financial_managements

    WHERE slug='$slug'

    LIMIT 1
    "
);



if (mysqli_num_rows($check) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location:create.php");
    exit;
}








// =====================================
// Upload PDF
// =====================================


$file = NULL;

$file_size = 0;



if (!empty($_FILES['file']['name'])) {


    $ext = strtolower(
        pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($ext !== 'pdf') {


        $_SESSION['error'] =
            "File harus berupa PDF.";


        header("Location:create.php");
        exit;
    }




    if ($_FILES['file']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran file maksimal 10 MB.";


        header("Location:create.php");
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





    $file =
        uniqid(
            'keuangan_',
            true
        )
        .
        ".pdf";





    $file_size =
        $_FILES['file']['size'];





    if (!move_uploaded_file(

        $_FILES['file']['tmp_name'],

        $uploadPath . $file

    )) {


        $_SESSION['error'] =
            "Upload file gagal.";


        header("Location:create.php");
        exit;
    }
}







// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO financial_managements

(

title,

slug,

description,

category,

fiscal_year,

total_budget,

realization,

funding_source,

file,

file_size,

status,

created_by

)


VALUES

(

'$title',

'$slug',

'$description',

'$category',

'$fiscal_year',

'$total_budget',

'$realization',

'$funding_source',

" . ($file ? "'$file'" : "NULL") . ",

'$file_size',

'$status',

'$created_by'

)

";







if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Data keuangan berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menyimpan data keuangan.";
}







header("Location:index.php");
exit;
