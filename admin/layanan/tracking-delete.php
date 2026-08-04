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
    
    SELECT id, service_id, tracking_code

    FROM letter_trackings

    WHERE id='$id'

    LIMIT 1

");




if (mysqli_num_rows($query) == 0) {


    echo "

    <script>

    alert('Data pengajuan tidak ditemukan');

    window.location='index.php';

    </script>

    ";


    exit;
}




$data = mysqli_fetch_assoc($query);




$service_id = $data['service_id'];







// ======================================================
// Hapus Data
// ======================================================


$delete = mysqli_query($conn, "
    
    DELETE FROM letter_trackings

    WHERE id='$id'


");








// ======================================================
// Response
// ======================================================


if ($delete) {


    echo "

    <script>

    alert('Pengajuan berhasil dihapus');


    window.location='tracking.php?service_id=$service_id';


    </script>

    ";
} else {


    echo "

    <script>

    alert('Gagal menghapus pengajuan');


    history.back();


    </script>

    ";
}
