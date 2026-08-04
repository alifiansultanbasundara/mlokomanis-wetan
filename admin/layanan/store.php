<?php

require_once '../../config/app.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit;
}

// =====================================================
// Helper
// =====================================================

function slugify($text)
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// =====================================================
// Ambil Data
// =====================================================

$name             = mysqli_real_escape_string($conn, trim($_POST['name']));
$slug             = slugify($_POST['slug'] ?: $_POST['name']);

$icon             = mysqli_real_escape_string($conn, $_POST['icon']);
$color            = mysqli_real_escape_string($conn, $_POST['color']);

$description      = mysqli_real_escape_string($conn, $_POST['description']);
$requirements     = mysqli_real_escape_string($conn, $_POST['requirements']);
$service_procedure        = mysqli_real_escape_string($conn, $_POST['service_procedure']);

$processing_time  = mysqli_real_escape_string($conn, $_POST['processing_time']);
$fee              = mysqli_real_escape_string($conn, $_POST['fee']);

$contact_person   = mysqli_real_escape_string($conn, $_POST['contact_person']);
$phone            = mysqli_real_escape_string($conn, $_POST['phone']);

$google_form_url  = mysqli_real_escape_string($conn, $_POST['google_form_url']);
$template_url     = mysqli_real_escape_string($conn, $_POST['template_url']);
$spreadsheet_url  = mysqli_real_escape_string($conn, $_POST['spreadsheet_url']);
$tracking_url     = mysqli_real_escape_string($conn, $_POST['tracking_url']);
$guide_url        = mysqli_real_escape_string($conn, $_POST['guide_url']);

$has_google_form  = isset($_POST['has_google_form']) ? 'Yes' : 'No';
$has_template     = isset($_POST['has_template']) ? 'Yes' : 'No';
$has_tracking     = isset($_POST['has_tracking']) ? 'Yes' : 'No';

$status           = mysqli_real_escape_string($conn, $_POST['status']);

$sort_order = 0;

$created_by = $_SESSION['user']['id'] ?? NULL;
$updated_by = $_SESSION['user']['id'] ?? NULL;

// =====================================================
// Validasi Slug
// =====================================================

$check = mysqli_query($conn, "
    SELECT id
    FROM service_letters
    WHERE slug = '$slug'
    LIMIT 1
");

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location:create.php");
    exit;
}

// =====================================================
// Insert
// =====================================================

$query = "
INSERT INTO service_letters (

    name,
    slug,

    icon,
    color,

    description,
    requirements,
    service_procedure,

    processing_time,
    fee,

    contact_person,
    phone,

    google_form_url,
    template_url,
    spreadsheet_url,
    tracking_url,
    guide_url,

    has_google_form,
    has_template,
    has_tracking,

    status,
    sort_order,

    created_by,
    updated_by

) VALUES (

    '$name',
    '$slug',

    '$icon',
    '$color',

    '$description',
    '$requirements',
    '$service_procedure',

    '$processing_time',
    '$fee',

    '$contact_person',
    '$phone',

    '$google_form_url',
    '$template_url',
    '$spreadsheet_url',
    '$tracking_url',
    '$guide_url',

    '$has_google_form',
    '$has_template',
    '$has_tracking',

    '$status',
    '$sort_order',

    " . ($created_by ?: "NULL") . ",
    " . ($updated_by ?: "NULL") . "

)
";

if (mysqli_query($conn, $query)) {

    $_SESSION['success'] = "Pelayanan surat berhasil ditambahkan.";

    header("Location:index.php");
    exit;
}

$_SESSION['error'] = "Gagal menyimpan data pelayanan surat.";

header("Location:create.php");
exit;
