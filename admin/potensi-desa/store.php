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
    $text = trim($text, '-');

    return $text;
}

// =====================================================
// Sanitize
// =====================================================

$title               = mysqli_real_escape_string($conn, $_POST['title']);
$slug                = slugify($_POST['slug'] ?: $_POST['title']);

$category            = mysqli_real_escape_string($conn, $_POST['category']);
$description         = mysqli_real_escape_string($conn, $_POST['description']);

$owner_name          = mysqli_real_escape_string($conn, $_POST['owner_name']);
$organization        = mysqli_real_escape_string($conn, $_POST['organization']);

$address             = mysqli_real_escape_string($conn, $_POST['address']);

$phone               = mysqli_real_escape_string($conn, $_POST['phone']);
$whatsapp            = mysqli_real_escape_string($conn, $_POST['whatsapp']);
$email               = mysqli_real_escape_string($conn, $_POST['email']);
$website             = mysqli_real_escape_string($conn, $_POST['website']);

$latitude            = mysqli_real_escape_string($conn, $_POST['latitude']);
$longitude           = mysqli_real_escape_string($conn, $_POST['longitude']);
$google_maps         = mysqli_real_escape_string($conn, $_POST['google_maps']);

$established_year    = !empty($_POST['established_year'])
    ? (int) $_POST['established_year']
    : "NULL";

$operational_hours   = mysqli_real_escape_string($conn, $_POST['operational_hours']);
$price_range         = mysqli_real_escape_string($conn, $_POST['price_range']);
$facilities          = mysqli_real_escape_string($conn, $_POST['facilities']);

$featured            = mysqli_real_escape_string($conn, $_POST['featured']);
$status              = mysqli_real_escape_string($conn, $_POST['status']);

$sort_order          = 0;
$views               = 0;

$created_by = $_SESSION['user']['id'] ?? NULL;
$updated_by = $_SESSION['user']['id'] ?? NULL;

// =====================================================
// Upload Image
// =====================================================

$image = NULL;

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $dir = APP_PATH . "uploads/potentials/";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    $image = time() . "_img." . $ext;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $dir . $image
    );
}

// =====================================================
// Upload Brochure
// =====================================================

$brochure = NULL;

if (
    isset($_FILES['brochure']) &&
    $_FILES['brochure']['error'] == 0
) {

    $dir = APP_PATH . "uploads/potentials/";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['brochure']['name'], PATHINFO_EXTENSION));

    $brochure = time() . "_brochure." . $ext;

    move_uploaded_file(
        $_FILES['brochure']['tmp_name'],
        $dir . $brochure
    );
}

// =====================================================
// Duplicate Slug
// =====================================================

$check = mysqli_query($conn, "
    SELECT id
    FROM village_potentials
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
INSERT INTO village_potentials (

    title,
    slug,
    category,
    description,

    owner_name,
    organization,

    address,

    phone,
    whatsapp,
    email,
    website,

    image,
    brochure,

    latitude,
    longitude,
    google_maps,

    established_year,

    operational_hours,
    price_range,
    facilities,

    featured,

    sort_order,
    views,

    status,

    created_by,
    updated_by

) VALUES (

    '$title',
    '$slug',
    '$category',
    '$description',

    '$owner_name',
    '$organization',

    '$address',

    '$phone',
    '$whatsapp',
    '$email',
    '$website',

    " . ($image ? "'$image'" : "NULL") . ",
    " . ($brochure ? "'$brochure'" : "NULL") . ",

    '$latitude',
    '$longitude',
    '$google_maps',

    $established_year,

    '$operational_hours',
    '$price_range',
    '$facilities',

    '$featured',

    '$sort_order',
    '$views',

    '$status',

    " . ($created_by ?: "NULL") . ",
    " . ($updated_by ?: "NULL") . "

)
";

if (mysqli_query($conn, $query)) {

    $potential_id = mysqli_insert_id($conn);

    $_SESSION['success'] =
        "Potensi desa berhasil ditambahkan. Silakan tambahkan produk atau layanan.";

    header("Location:produk.php?potential_id=" . $potential_id);
    exit;
}

$_SESSION['error'] = "Gagal menyimpan data.";

header("Location:create.php");
exit;
