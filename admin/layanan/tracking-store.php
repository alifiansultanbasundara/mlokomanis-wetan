<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit;
}

// =====================================================
// Data
// =====================================================

$service_id = (int) $_POST['service_id'];

$applicant_name = mysqli_real_escape_string(
    $conn,
    trim($_POST['applicant_name'])
);

$nik = mysqli_real_escape_string(
    $conn,
    trim($_POST['nik'])
);

$phone = mysqli_real_escape_string(
    $conn,
    trim($_POST['phone'])
);

$email = mysqli_real_escape_string(
    $conn,
    trim($_POST['email'])
);

$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);

$notes = mysqli_real_escape_string(
    $conn,
    trim($_POST['notes'])
);

$created_by = $_SESSION['user']['id'] ?? NULL;
$updated_by = $_SESSION['user']['id'] ?? NULL;

// =====================================================
// Validasi Service
// =====================================================

$service = mysqli_query($conn, "
SELECT id
FROM service_letters
WHERE id='$service_id'
LIMIT 1
");

if (mysqli_num_rows($service) == 0) {

    $_SESSION['error'] = "Pelayanan surat tidak ditemukan.";

    header("Location:index.php");
    exit;
}

// =====================================================
// Generate Tracking Code
// =====================================================

$last = mysqli_query($conn, "
SELECT id
FROM letter_trackings
ORDER BY id DESC
LIMIT 1
");

$number = 1;

if (mysqli_num_rows($last) > 0) {

    $row = mysqli_fetch_assoc($last);

    $number = $row['id'] + 1;
}

$tracking_code = "SRT" . str_pad($number, 6, "0", STR_PAD_LEFT);

// =====================================================
// Insert
// =====================================================

$query = "
INSERT INTO letter_trackings(

tracking_code,

service_id,

applicant_name,
nik,
phone,
email,

status,
notes,

created_by,
updated_by

)

VALUES(

'$tracking_code',

'$service_id',

'$applicant_name',
'$nik',
'$phone',
'$email',

'$status',
'$notes',

" . ($created_by ?: "NULL") . ",
" . ($updated_by ?: "NULL") . "

)

";

if (mysqli_query($conn, $query)) {

    $_SESSION['success'] = "Tracking berhasil ditambahkan.";

    header("Location:tracking.php?service_id=" . $service_id);
    exit;
}

$_SESSION['error'] = "Gagal menambahkan tracking.";

header("Location:tracking-create.php?service_id=" . $service_id);
exit;
