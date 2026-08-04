<?php

require_once '../../../config/app.php';


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

    FROM contact_messages

    WHERE id='$id'

    LIMIT 1

");





if (mysqli_num_rows($query) == 0) {


    echo "

    <script>

        alert('Pesan tidak ditemukan');

        window.location='index.php';

    </script>

    ";


    exit;
}



$message = mysqli_fetch_assoc($query);







// ======================================================
// Hapus Data
// ======================================================


$delete = mysqli_query($conn, "

    DELETE FROM contact_messages

    WHERE id='$id'

");






// ======================================================
// Response
// ======================================================


if ($delete) {


    echo "

    <script>

        alert('Pesan berhasil dihapus');

        window.location='index.php';

    </script>

    ";
} else {


    echo "

    <script>

        alert('Gagal menghapus pesan');

        history.back();

    </script>

    ";
}
