<?php

require_once '../../config/app.php';


// ======================================================
// Validasi ID
// ======================================================

if (!isset($_GET['id'])) {

    header("Location:index.php");

    exit;
}


$id = (int) $_GET['id'];




// ======================================================
// Cek Data
// ======================================================

$query = mysqli_query($conn, "
    SELECT id, name
    FROM service_letters
    WHERE id='$id'
    LIMIT 1
");



if (mysqli_num_rows($query) == 0) {


    echo "

    <script>

    alert('Data layanan tidak ditemukan');

    window.location='index.php';

    </script>

    ";


    exit;
}



$service = mysqli_fetch_assoc($query);





// ======================================================
// Hapus Data
// ======================================================


$delete = mysqli_query($conn, "
    
    DELETE FROM service_letters

    WHERE id='$id'

");





// ======================================================
// Redirect
// ======================================================


if ($delete) {


    echo "

    <script>

    alert('Layanan berhasil dihapus');

    window.location='index.php';

    </script>

    ";
} else {


    echo "

    <script>

    alert('Gagal menghapus layanan');

    history.back();

    </script>

    ";
}
