<?php

require_once '../../config/app.php';


// ======================================================
// Hanya POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");

    exit;
}



// ======================================================
// Helper
// ======================================================

function clean($data)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}




// ======================================================
// Ambil Data
// ======================================================

$id = (int) ($_POST['id'] ?? 0);


$service_id = (int) ($_POST['service_id'] ?? 0);



$applicant_name = clean($_POST['applicant_name'] ?? '');

$nik = clean($_POST['nik'] ?? '');

$phone = clean($_POST['phone'] ?? '');

$email = clean($_POST['email'] ?? '');

$status = clean($_POST['status'] ?? '');

$notes = clean($_POST['notes'] ?? '');




// ======================================================
// Validasi
// ======================================================

if (!$id || !$service_id || !$applicant_name) {

    echo "

    <script>

    alert('Data tidak lengkap');

    history.back();

    </script>

    ";

    exit;
}




// ======================================================
// Completed At
// ======================================================


if ($status == "Selesai") {


    $completed_at = "NOW()";
} else {


    $completed_at = "NULL";
}






// ======================================================
// Updated By
// ======================================================


$updated_by = "NULL";


if (isset($_SESSION['user_id'])) {


    $updated_by = (int) $_SESSION['user_id'];
}








// ======================================================
// Update Database
// ======================================================


$query = mysqli_query($conn, "

    UPDATE letter_trackings SET


        applicant_name = '$applicant_name',


        nik = '$nik',


        phone = '$phone',


        email = '$email',


        status = '$status',


        notes = '$notes',


        completed_at = $completed_at,


        updated_by = $updated_by



    WHERE id='$id'


");







// ======================================================
// Response
// ======================================================


if ($query) {


    echo "

    <script>

    alert('Tracking berhasil diperbarui');


    window.location='tracking.php?service_id=$service_id';


    </script>

    ";
} else {


    echo "

    <script>

    alert('Gagal memperbarui tracking');


    history.back();


    </script>

    ";
}
