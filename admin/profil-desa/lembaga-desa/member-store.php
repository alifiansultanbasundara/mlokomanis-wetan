<?php

require_once '../../../config/app.php';


// ===============================
// HANYA POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;
}



// ===============================
// HELPER
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
// AMBIL DATA
// ===============================

$institution_id = (int) $_POST['institution_id'];

$name        = clean($_POST['name']);
$position    = clean($_POST['position']);
$gender      = clean($_POST['gender']);
$phone       = clean($_POST['phone']);
$address     = clean($_POST['address']);
$status      = clean($_POST['status']);
$sort_order  = (int) $_POST['sort_order'];




// ===============================
// VALIDASI
// ===============================

if (!$institution_id || empty($name)) {

    $_SESSION['error'] = "Nama anggota wajib diisi.";

    header("Location: create-member.php?id=" . $institution_id);

    exit;
}




// ===============================
// CEK LEMBAGA
// ===============================

$checkInstitution = mysqli_query($conn, "

    SELECT id
    FROM village_institution_members
    WHERE id='$institution_id'

");


if (mysqli_num_rows($checkInstitution) == 0) {


    $_SESSION['error'] = "Lembaga tidak ditemukan.";

    header("Location: index.php");

    exit;
}







// ===============================
// UPLOAD FOTO
// ===============================

$photo = null;


if (!empty($_FILES['photo']['name'])) {


    $uploadDir = "../../../uploads/institution-members/";


    if (!is_dir($uploadDir)) {

        mkdir($uploadDir, 0777, true);
    }



    $fileName = $_FILES['photo']['name'];

    $fileTmp  = $_FILES['photo']['tmp_name'];



    $ext = strtolower(
        pathinfo(
            $fileName,
            PATHINFO_EXTENSION
        )
    );



    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];



    if (!in_array($ext, $allowed)) {


        $_SESSION['error'] = "Format foto tidak diperbolehkan.";


        header(
            "Location: create-member.php?id=" . $institution_id
        );

        exit;
    }




    $photo = time() . "-" . uniqid() . "." . $ext;



    move_uploaded_file(

        $fileTmp,

        $uploadDir . $photo

    );
}




// ===============================
// INSERT DATA
// ===============================


$query = mysqli_query($conn, "

    INSERT INTO institution_members

    (
        institution_id,
        name,
        position,
        gender,
        phone,
        address,
        photo,
        status,
        sort_order
    )

    VALUES

    (

        '$institution_id',

        '$name',

        '$position',

        '$gender',

        '$phone',

        '$address',

        '$photo',

        '$status',

        '$sort_order'

    )

");





// ===============================
// HASIL
// ===============================

if ($query) {


    // Update jumlah anggota

    mysqli_query($conn, "

        UPDATE village_institution_members

        SET total_members = (

            SELECT COUNT(*)

            FROM institution_members

            WHERE institution_id='$institution_id'

            AND status='Active'

        )

        WHERE id='$institution_id'

    ");



    $_SESSION['success'] =
        "Anggota berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menyimpan anggota.";
}






header(

    "Location: detail.php?id=" . $institution_id

);


exit;
