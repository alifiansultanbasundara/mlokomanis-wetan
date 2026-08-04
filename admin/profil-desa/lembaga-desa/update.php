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
// Ambil ID
// ===============================

$id = (int) $_POST['id'];




// ===============================
// Ambil Data Lama
// ===============================

$getData = mysqli_query(
    $conn,

    "
    SELECT *
    FROM village_institutions
    WHERE id='$id'
    LIMIT 1
    "
);



if (!$getData || mysqli_num_rows($getData) == 0) {


    $_SESSION['error'] =
        "Data lembaga tidak ditemukan.";


    header("Location:index.php");
    exit;
}


$old = mysqli_fetch_assoc($getData);






// ===============================
// Ambil Input
// ===============================

$name = mysqli_real_escape_string(
    $conn,
    trim($_POST['name'])
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



$chairman = mysqli_real_escape_string(
    $conn,
    trim($_POST['chairman'])
);



$secretary = mysqli_real_escape_string(
    $conn,
    trim($_POST['secretary'])
);



$phone = mysqli_real_escape_string(
    $conn,
    trim($_POST['phone'])
);



$email = mysqli_real_escape_string(
    $conn,
    trim($_POST['email'])
);



$total_members = (int) $_POST['total_members'];



$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);



$sort_order = (int) $_POST['sort_order'];



$updated_by = $_SESSION['id'] ?? null;







// ===============================
// Validasi
// ===============================

if (empty($name) || empty($slug)) {


    $_SESSION['error'] =
        "Nama lembaga wajib diisi.";


    header("Location: edit.php?id=" . $id);
    exit;
}







// ===============================
// Validasi Kategori
// ===============================

$allowedCategory = [

    'BPD',
    'LPMD',
    'PKK',
    'Karang Taruna',
    'RT/RW',
    'Posyandu',
    'Kelompok Tani',
    'Lainnya'

];



if (!in_array($category, $allowedCategory)) {


    $_SESSION['error'] =
        "Kategori tidak valid.";


    header("Location: edit.php?id=" . $id);
    exit;
}







// ===============================
// Cek Slug
// ===============================

$checkSlug = mysqli_query(
    $conn,

    "
    SELECT id
    FROM village_institutions
    WHERE slug='$slug'
    AND id != '$id'
    LIMIT 1
    "

);



if (mysqli_num_rows($checkSlug) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location: edit.php?id=" . $id);
    exit;
}








// ===============================
// Upload Image Baru
// ===============================

$image = $old['image'];



if (!empty($_FILES['image']['name'])) {



    $allowedImage = [

        'jpg',
        'jpeg',
        'png',
        'webp'

    ];



    $ext = strtolower(
        pathinfo(
            $_FILES['image']['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowedImage)) {


        $_SESSION['error'] =
            "Format gambar tidak valid.";


        header("Location: edit.php?id=" . $id);
        exit;
    }




    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran gambar maksimal 2 MB.";


        header("Location: edit.php?id=" . $id);
        exit;
    }






    $uploadPath = APP_PATH .
        "uploads/village/institutions/";



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }





    $newImage =
        uniqid("institution_", true)
        . "." . $ext;




    if (move_uploaded_file(

        $_FILES['image']['tmp_name'],

        $uploadPath . $newImage

    )) {



        // hapus lama

        if (!empty($old['image'])) {


            $oldFile = $uploadPath . $old['image'];


            if (file_exists($oldFile)) {

                unlink($oldFile);
            }
        }



        $image = $newImage;
    }
}









// ===============================
// Upload Document Baru
// ===============================

$document = $old['document'];



if (!empty($_FILES['document']['name'])) {



    $allowedDoc = [

        'pdf',
        'doc',
        'docx'

    ];



    $ext = strtolower(
        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )
    );



    if (!in_array($ext, $allowedDoc)) {


        $_SESSION['error'] =
            "Format dokumen tidak valid.";


        header("Location: edit.php?id=" . $id);
        exit;
    }




    if ($_FILES['document']['size'] > 10 * 1024 * 1024) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location: edit.php?id=" . $id);
        exit;
    }







    $uploadPath = APP_PATH .
        "uploads/village/institutions/";



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0777, true);
    }






    $newDocument =
        uniqid("document_", true)
        . "." . $ext;





    if (move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $newDocument

    )) {



        // hapus dokumen lama

        if (!empty($old['document'])) {


            $oldDocument =
                $uploadPath . $old['document'];



            if (file_exists($oldDocument)) {

                unlink($oldDocument);
            }
        }



        $document = $newDocument;
    }
}









// ===============================
// Update Database
// ===============================

$sql = "

UPDATE village_institutions SET


name='$name',

slug='$slug',

category='$category',

description='$description',

chairman='$chairman',

secretary='$secretary',

phone='$phone',

email='$email',

total_members='$total_members',

image=" . ($image ? "'$image'" : "NULL") . ",

document=" . ($document ? "'$document'" : "NULL") . ",

status='$status',

sort_order='$sort_order',

updated_by=" . ($updated_by ? "'$updated_by'" : "NULL") . "


WHERE id='$id'

";





if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Lembaga desa berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui lembaga desa.";
}






header("Location:index.php");
exit;
