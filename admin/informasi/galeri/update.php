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

$id = (int) $_POST['id'];

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

$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);


$updated_by = $_SESSION['id'];



// =====================================
// Validasi
// =====================================

if (
    $id <= 0 ||
    empty($title) ||
    empty($slug)
) {

    $_SESSION['error'] = "Mohon lengkapi data yang wajib diisi.";

    header(
        "Location: edit.php?slug=" . urlencode($slug)
    );

    exit;
}




// =====================================
// Ambil Album
// =====================================

$cek = mysqli_query($conn, "

    SELECT *

    FROM galleries

    WHERE id='$id'

");


if (mysqli_num_rows($cek) == 0) {

    $_SESSION['error'] = "Album tidak ditemukan.";

    header("Location:index.php");

    exit;
}


$data = mysqli_fetch_assoc($cek);



// =====================================
// Validasi Status
// =====================================

if (!in_array($status, ['Published', 'Draft'])) {


    $_SESSION['error'] = "Status tidak valid.";

    header(
        "Location: edit.php?slug=" . urlencode($data['slug'])
    );

    exit;
}




// =====================================
// Cek Slug
// =====================================


$checkSlug = mysqli_query($conn, "

    SELECT id

    FROM galleries

    WHERE slug='$slug'

    AND id!='$id'

");


if (mysqli_num_rows($checkSlug) > 0) {


    $_SESSION['error'] = "Slug sudah digunakan.";

    header(
        "Location: edit.php?slug=" . urlencode($data['slug'])
    );

    exit;
}





// =====================================
// COVER IMAGE
// =====================================

$coverImage = $data['cover_image'];



if (
    isset($_FILES['cover_image']) &&
    $_FILES['cover_image']['name'] != ''
) {


    $uploadCover = APP_PATH .
        "uploads/informasi/galeri/cover/";



    if (!is_dir($uploadCover)) {

        mkdir(
            $uploadCover,
            0777,
            true
        );
    }



    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];



    $name = $_FILES['cover_image']['name'];

    $tmp = $_FILES['cover_image']['tmp_name'];

    $size = $_FILES['cover_image']['size'];



    $ext = strtolower(
        pathinfo(
            $name,
            PATHINFO_EXTENSION
        )
    );



    if (
        in_array($ext, $allowed) &&
        $size <= 2 * 1024 * 1024
    ) {


        $newCover =
            uniqid('cover_', true)
            . "." . $ext;



        if (
            move_uploaded_file(
                $tmp,
                $uploadCover . $newCover
            )
        ) {


            // hapus cover lama

            if (
                !empty($data['cover_image']) &&
                file_exists(
                    $uploadCover . $data['cover_image']
                )
            ) {

                unlink(
                    $uploadCover . $data['cover_image']
                );
            }



            $coverImage = $newCover;
        }
    }
}





// =====================================
// Update Album
// =====================================


mysqli_query($conn, "

    UPDATE galleries

    SET

        title='$title',

        slug='$slug',

        description='$description',

        cover_image='$coverImage',

        status='$status',

        updated_by='$updated_by'

    WHERE id='$id'

");







// =====================================
// Hapus Foto Album
// =====================================


if (!empty($_POST['delete_images'])) {


    foreach ($_POST['delete_images'] as $imageId) {


        $imageId = (int)$imageId;



        $img = mysqli_query($conn, "

            SELECT *

            FROM gallery_images

            WHERE id='$imageId'

            AND gallery_id='$id'

        ");



        if (mysqli_num_rows($img)) {


            $foto = mysqli_fetch_assoc($img);



            $path =
                APP_PATH .
                "uploads/informasi/galeri/images/"
                . $foto['image'];



            if (file_exists($path)) {

                unlink($path);
            }



            mysqli_query($conn, "

                DELETE FROM gallery_images

                WHERE id='$imageId'

            ");
        }
    }
}







// =====================================
// Upload Foto Album Baru
// =====================================


if (
    !empty($_FILES['images']['name'][0])
) {


    $uploadPath =
        APP_PATH .
        "uploads/informasi/galeri/images/";



    if (!is_dir($uploadPath)) {

        mkdir(
            $uploadPath,
            0777,
            true
        );
    }



    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];



    foreach (
        $_FILES['images']['name']
        as $key => $name
    ) {


        if (empty($name)) continue;



        $tmp =
            $_FILES['images']['tmp_name'][$key];


        $size =
            $_FILES['images']['size'][$key];



        $ext = strtolower(
            pathinfo(
                $name,
                PATHINFO_EXTENSION
            )
        );



        if (
            !in_array($ext, $allowed)
        ) {

            continue;
        }



        if (
            $size > 2 * 1024 * 1024
        ) {

            continue;
        }



        $newName =
            uniqid('gallery_', true)
            . "." . $ext;



        if (
            move_uploaded_file(
                $tmp,
                $uploadPath . $newName
            )
        ) {


            mysqli_query($conn, "

                INSERT INTO gallery_images

                (
                    gallery_id,
                    image
                )

                VALUES

                (
                    '$id',
                    '$newName'
                )

            ");
        }
    }
}




// =====================================
// Selesai
// =====================================


$_SESSION['success'] =
    "Album galeri berhasil diperbarui.";


header("Location:index.php");

exit;
