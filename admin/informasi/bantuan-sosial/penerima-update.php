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

$id = (int) $_POST['id'];

$assistance_id = (int) $_POST['assistance_id'];



$name = mysqli_real_escape_string(
    $conn,
    trim($_POST['name'])
);



$nik = mysqli_real_escape_string(
    $conn,
    trim($_POST['nik'])
);



$kk = mysqli_real_escape_string(
    $conn,
    trim($_POST['kk'])
);



$address = mysqli_real_escape_string(
    $conn,
    trim($_POST['address'])
);



$rtrw = mysqli_real_escape_string(
    $conn,
    trim($_POST['rtrw'])
);



$dusun = mysqli_real_escape_string(
    $conn,
    trim($_POST['dusun'])
);



$description = mysqli_real_escape_string(
    $conn,
    trim($_POST['description'])
);







// =====================================
// Validasi Data Penerima
// =====================================

$checkRecipient = mysqli_query(

    $conn,

    "
    SELECT id

    FROM social_assistance_recipients

    WHERE id='$id'

    LIMIT 1

    "

);



if (!$checkRecipient || mysqli_num_rows($checkRecipient) == 0) {


    $_SESSION['error'] =
        "Data penerima tidak ditemukan.";


    header(
        "Location:penerima.php?id=" . $assistance_id
    );

    exit;
}







// =====================================
// Validasi Program
// =====================================

$checkProgram = mysqli_query(

    $conn,

    "
    SELECT id

    FROM social_assistances

    WHERE id='$assistance_id'

    LIMIT 1

    "

);



if (!$checkProgram || mysqli_num_rows($checkProgram) == 0) {


    $_SESSION['error'] =
        "Program bantuan tidak ditemukan.";


    header("Location:index.php");

    exit;
}








// =====================================
// Validasi Nama
// =====================================

if (empty($name)) {


    $_SESSION['error'] =
        "Nama penerima wajib diisi.";


    header(
        "Location:penerima-edit.php?id=" . $id
    );

    exit;
}







// =====================================
// Validasi NIK
// =====================================

if (!empty($nik)) {


    if (!preg_match('/^[0-9]{16}$/', $nik)) {


        $_SESSION['error'] =
            "NIK harus terdiri dari 16 digit angka.";


        header(
            "Location:penerima-edit.php?id=" . $id
        );

        exit;
    }







    // Cek NIK duplikat selain data sendiri

    $duplicate = mysqli_query(

        $conn,

        "
        SELECT id

        FROM social_assistance_recipients

        WHERE assistance_id='$assistance_id'

        AND nik='$nik'

        AND id != '$id'

        LIMIT 1

        "

    );




    if (mysqli_num_rows($duplicate) > 0) {


        $_SESSION['error'] =
            "NIK sudah digunakan oleh penerima lain.";


        header(
            "Location:penerima-edit.php?id=" . $id
        );

        exit;
    }
}








// =====================================
// Update Database
// =====================================


$sql = "

UPDATE social_assistance_recipients

SET

    name = '$name',

    nik = " .
    (
        !empty($nik)
        ? "'$nik'"
        : "NULL"
    )
    . ",

    kk = " .
    (
        !empty($kk)
        ? "'$kk'"
        : "NULL"
    )
    . ",

    address = '$address',

    rtrw = '$rtrw',

    dusun = '$dusun',

    description = '$description'


WHERE id='$id'

";






if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Data penerima berhasil diperbarui.";
} else {


    $_SESSION['error'] =
        "Gagal memperbarui data penerima.";
}






header(

    "Location:penerima.php?id=" . $assistance_id

);

exit;
