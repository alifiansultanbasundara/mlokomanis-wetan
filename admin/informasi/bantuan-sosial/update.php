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


$old_slug = mysqli_real_escape_string(
    $conn,
    $_POST['old_slug']
);



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



$updated_by = $_SESSION['id'];







// =====================================
// Validasi
// =====================================


if (
    empty($title) ||
    empty($year)
) {


    $_SESSION['error'] =
        "Nama program dan tahun wajib diisi.";


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}








// =====================================
// Validasi ENUM
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


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}





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


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}





$allowedStatus = [

    'Published',
    'Draft'

];



if (!in_array($status, $allowedStatus)) {


    $_SESSION['error'] =
        "Status tidak valid.";


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}








// =====================================
// Cek Slug Duplikat
// =====================================


$checkSlug = mysqli_query(
    $conn,

    "
    SELECT id

    FROM social_assistances

    WHERE slug='$slug'

    AND slug!='$old_slug'

    LIMIT 1

    "
);



if (mysqli_num_rows($checkSlug) > 0) {


    $_SESSION['error'] =
        "Slug sudah digunakan.";


    header("Location:edit.php?slug=" . $old_slug);
    exit;
}









// =====================================
// Ambil File Lama
// =====================================


$oldData = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT document_file

        FROM social_assistances

        WHERE slug='$old_slug'

        LIMIT 1
        "

    )

);



$document = $oldData['document_file'];







// =====================================
// Upload Dokumen Baru
// =====================================


if (!empty($_FILES['document']['name'])) {


    $ext = strtolower(

        pathinfo(
            $_FILES['document']['name'],
            PATHINFO_EXTENSION
        )

    );



    if ($ext !== 'pdf') {


        $_SESSION['error'] =
            "Dokumen harus berupa PDF.";


        header("Location:edit.php?slug=" . $old_slug);
        exit;
    }





    if ($_FILES['document']['size'] > (10 * 1024 * 1024)) {


        $_SESSION['error'] =
            "Ukuran dokumen maksimal 10 MB.";


        header("Location:edit.php?slug=" . $old_slug);
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






    $newFile =
        uniqid(
            "bantuan_",
            true
        )
        . ".pdf";





    if (move_uploaded_file(

        $_FILES['document']['tmp_name'],

        $uploadPath . $newFile

    )) {


        // Hapus file lama

        if (!empty($document)) {


            $oldFile =
                $uploadPath . $document;



            if (file_exists($oldFile)) {

                unlink($oldFile);
            }
        }





        $document = $newFile;
    } else {


        $_SESSION['error'] =
            "Upload dokumen gagal.";


        header("Location:edit.php?slug=" . $old_slug);
        exit;
    }
}








// =====================================
// Update Database
// =====================================


$sql = "

UPDATE social_assistances SET


title='$title',

slug='$slug',

description='$description',

category='$category',

year='$year',

total_budget='$total_budget',

funding_source='$funding_source',

status='$status',

document_file=" .
    (
        $document
        ? "'$document'"
        : "NULL"
    )
    . ",

updated_by='$updated_by'


WHERE slug='$old_slug'


";








if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Program bantuan sosial berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui program bantuan sosial.";
}







header("Location:index.php");
exit;
