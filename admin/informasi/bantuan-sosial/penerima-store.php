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
// Validasi Program
// =====================================

$program = mysqli_query(

    $conn,

    "
    SELECT id

    FROM social_assistances

    WHERE id='$assistance_id'

    LIMIT 1

    "

);



if (!$program || mysqli_num_rows($program) == 0) {


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
        "Location:penerima-create.php?id=" . $assistance_id
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
            "Location:penerima-create.php?id=" . $assistance_id
        );

        exit;
    }







    // cek duplikat NIK

    $check = mysqli_query(

        $conn,

        "
        SELECT id

        FROM social_assistance_recipients

        WHERE assistance_id='$assistance_id'

        AND nik='$nik'

        LIMIT 1

        "

    );




    if (mysqli_num_rows($check) > 0) {


        $_SESSION['error'] =
            "NIK sudah terdaftar pada program bantuan ini.";


        header(
            "Location:penerima-create.php?id=" . $assistance_id
        );

        exit;
    }
}









// =====================================
// Simpan Database
// =====================================


$sql = "

INSERT INTO social_assistance_recipients

(

    assistance_id,

    name,

    nik,

    kk,

    address,

    rtrw,

    dusun,

    description

)

VALUES

(

    '$assistance_id',

    '$name',

    " .
    (
        !empty($nik)
        ? "'$nik'"
        : "NULL"
    )
    . ",

    " .
    (
        !empty($kk)
        ? "'$kk'"
        : "NULL"
    )
    . ",

    '$address',

    '$rtrw',

    '$dusun',

    '$description'

)

";






if (mysqli_query($conn, $sql)) {


    $_SESSION['success'] =
        "Penerima bantuan berhasil ditambahkan.";
} else {


    $_SESSION['error'] =
        "Gagal menambahkan penerima bantuan.";
}






header(
    "Location:penerima.php?id=" . $assistance_id
);

exit;
