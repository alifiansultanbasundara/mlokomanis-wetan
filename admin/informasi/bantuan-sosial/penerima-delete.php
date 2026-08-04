<?php

require_once '../../../config/app.php';


// =====================================
// Validasi ID
// =====================================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location:index.php");
    exit;
}


$id = (int) $_GET['id'];





// =====================================
// Ambil Data Penerima
// =====================================

$query = mysqli_query(

    $conn,

    "
    SELECT
        id,
        assistance_id

    FROM social_assistance_recipients

    WHERE id='$id'

    LIMIT 1

    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Data penerima tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$data = mysqli_fetch_assoc($query);



$assistance_id = $data['assistance_id'];






// =====================================
// Hapus Data
// =====================================

$delete = mysqli_query(

    $conn,

    "
    DELETE FROM social_assistance_recipients

    WHERE id='$id'

    "

);





if ($delete) {


    $_SESSION['success'] =
        "Penerima bantuan berhasil dihapus.";
} else {


    $_SESSION['error'] =
        "Gagal menghapus penerima bantuan.";
}







// =====================================
// Redirect
// =====================================

header(

    "Location:penerima.php?id=" . $assistance_id

);

exit;
