<?php

require_once '../../../config/app.php';


// ===============================
// Hanya POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// ===============================
// Helper
// ===============================

function clean($data)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}



// ===============================
// Ambil Data
// ===============================

$id = (int) $_POST['id'];



$name       = clean($_POST['name']);
$position   = clean($_POST['position']);
$category   = clean($_POST['category']);

$nip        = clean($_POST['nip']);
$education  = clean($_POST['education']);

$address    = clean($_POST['address']);



$parent_id = !empty($_POST['parent_id'])
    ? (int) $_POST['parent_id']
    : NULL;



$sort_order = (int) ($_POST['sort_order'] ?? 0);



$status = clean($_POST['status']);




// ===============================
// Validasi
// ===============================

if (empty($name) || empty($position)) {


    $_SESSION['error'] =
        "Nama dan jabatan wajib diisi.";


    header("Location: edit.php?id=" . $id);
    exit;
}




$allowedCategory = [

    'Kepala Desa',
    'Sekretariat Desa',
    'Kepala Urusan',
    'Kepala Seksi',
    'Kepala Dusun',
    'Staf Desa',
    'BPD',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori tidak valid.";


    header("Location: edit.php?id=" . $id);
    exit;
}




// ===============================
// Ambil Foto Lama
// ===============================

$getOld = mysqli_query(
    $conn,

    "
    SELECT photo
    FROM village_officials
    WHERE id='$id'
    LIMIT 1
    "

);



$oldData = mysqli_fetch_assoc($getOld);


$photo = $oldData['photo'] ?? NULL;





// ===============================
// Upload Foto Baru
// ===============================

if (!empty($_FILES['photo']['name'])) {


    $file = $_FILES['photo'];



    $allowedImage = [

        'jpg',
        'jpeg',
        'png',
        'webp'

    ];



    $ext = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowedImage)) {


        $_SESSION['error'] =
            "Format foto tidak valid.";


        header("Location: edit.php?id=" . $id);
        exit;
    }




    if ($file['size'] > 2 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran foto maksimal 2 MB.";


        header("Location: edit.php?id=" . $id);
        exit;
    }




    $folder = APP_PATH .
        "uploads/village/officials/";



    if (!is_dir($folder)) {


        mkdir(
            $folder,
            0777,
            true
        );
    }




    $newPhoto =
        uniqid(
            "official_",
            true
        )
        .
        "."
        .
        $ext;



    if (move_uploaded_file(
        $file['tmp_name'],
        $folder . $newPhoto
    )) {


        // hapus foto lama

        if (
            !empty($photo)
            && file_exists($folder . $photo)
        ) {


            unlink(
                $folder . $photo
            );
        }



        $photo = $newPhoto;
    }
}





// ===============================
// Update Database
// ===============================


$sql = "

UPDATE village_officials SET


name='$name',

position='$position',

category='$category',


nip='$nip',

education='$education',


address='$address',


photo=" .
    ($photo ? "'$photo'" : "NULL")
    . ",


parent_id=" .
    ($parent_id ? $parent_id : "NULL")
    . ",


sort_order='$sort_order',


status='$status'


WHERE id='$id'


";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Struktur organisasi berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui struktur organisasi.";
}




header("Location: index.php");
exit;
