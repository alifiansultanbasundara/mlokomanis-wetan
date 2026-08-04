<?php

require_once '../../../config/app.php';


// =====================================
// Hanya menerima POST
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



$year = (int) $_POST['year'];



$total_budget = !empty($_POST['total_budget'])
    ? (int) $_POST['total_budget']
    : 0;



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
    empty($year)
) {


    $_SESSION['error'] =
        "Nama program dan tahun wajib diisi.";


    header("Location:create.php");
    exit;
}






// =====================================
// Validasi Kategori
// =====================================


$allowedCategory = [

    'BLT Dana Desa',
    'PKH',
    'BPNT',
    'Bantuan Sembako',
    'Bantuan Kesehatan',
    'Bantuan Pendidikan',
    'Bantuan Rumah',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori bantuan tidak valid.";


    header("Location:create.php");
    exit;
}







// =====================================
// Validasi Sumber Dana
// =====================================


$allowedFunding = [

    'Dana Desa',
    'APBD',
    'APBN',
    'Swadaya',
    'Lainnya'

];



if (!in_array($funding_source, $allowedFunding)) {


    $_SESSION['error'] =
        "Sumber dana tidak valid.";


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

    FROM social_assistances

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
// Upload Dokumen
// =====================================


$document = NULL;



if (!empty($_FILES['document']['name'])) {


    $fileExt = strtolower(
        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )
    );



    if ($fileExt !== 'pdf') {


        $_SESSION['error'] =
            "Dokumen harus berupa PDF.";


        header("Location:create.php");
        exit;
    }





    if ($_FILES['document']['size'] > (10 * 1024 * 1024)) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location:create.php");
        exit;
    }






    $uploadPath =
        APP_PATH .
        "uploads/informasi/bantuan-sosial/";



    if (!is_dir($uploadPath)) {

        mkdir(
            $uploadPath,
            0777,
            true
        );
    }






    $document =
        uniqid(
            "bantuan_",
            true
        )
        . ".pdf";





    if (!move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $document

    )) {


        $_SESSION['error'] =
            "Upload dokumen gagal.";


        header("Location:create.php");
        exit;
    }
}









// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO social_assistances

(

    title,

    slug,

    description,

    category,

    year,

    total_budget,

    funding_source,

    status,

    document_file,

    created_by

)

VALUES

(

    '$title',

    '$slug',

    '$description',

    '$category',

    '$year',

    '$total_budget',

    '$funding_source',

    '$status',

    " . ($document ? "'$document'" : "NULL") . ",

    '$created_by'

)

";






if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Program bantuan sosial berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan program bantuan sosial.";
}







header("Location:index.php");
exit;
