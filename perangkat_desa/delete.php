<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Validasi ID
// ======================================

if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];


// ======================================
// Ambil Data
// ======================================

$query = mysqli_query($conn, "

SELECT *

FROM perangkat_desa

WHERE id='$id'

");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['success'] = "Data perangkat desa tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// ======================================
// Hapus Foto
// ======================================

if (

    !empty($data['photo'])

    &&

    file_exists("uploads/" . $data['photo'])

) {

    unlink("uploads/" . $data['photo']);
}


// ======================================
// Hapus Database
// ======================================

$delete = mysqli_query($conn, "

DELETE FROM perangkat_desa

WHERE id='$id'

");

if ($delete) {

    $_SESSION['success'] = "Data perangkat desa berhasil dihapus.";
} else {

    $_SESSION['success'] = "Gagal menghapus data perangkat desa.";
}

header("Location:index.php");
exit;
