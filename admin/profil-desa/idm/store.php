<?php

require_once '../../../config/app.php';


// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit;
}


// =====================================
// Ambil Data
// =====================================

$year               = (int) $_POST['year'];
$title              = trim($_POST['title']);
$slug               = trim($_POST['slug']);
$description        = trim($_POST['description']);

$status_idm         = trim($_POST['status_idm']);

$idm_score          = $_POST['idm_score'];
$social_score       = $_POST['social_score'];
$economic_score     = $_POST['economic_score'];
$environmental_score = $_POST['environmental_score'];
$target_score       = $_POST['target_score'];

$ranking_regency    = !empty($_POST['ranking_regency']) ? (int) $_POST['ranking_regency'] : NULL;
$ranking_province   = !empty($_POST['ranking_province']) ? (int) $_POST['ranking_province'] : NULL;
$ranking_national   = !empty($_POST['ranking_national']) ? (int) $_POST['ranking_national'] : NULL;

$strengths          = trim($_POST['strengths']);
$weaknesses         = trim($_POST['weaknesses']);
$recommendation     = trim($_POST['recommendation']);

$source             = trim($_POST['source']);
$status             = trim($_POST['status']);

$created_by         = $_SESSION['id'] ?? NULL;


// =====================================
// Validasi
// =====================================

if (
    empty($year) ||
    empty($title) ||
    empty($slug) ||
    empty($status_idm) ||
    $idm_score === ''
) {

    $_SESSION['error'] = "Silakan lengkapi data yang wajib diisi.";

    header("Location:create.php");
    exit;
}


// =====================================
// Escape
// =====================================

$title          = mysqli_real_escape_string($conn, $title);
$slug           = mysqli_real_escape_string($conn, $slug);
$description    = mysqli_real_escape_string($conn, $description);
$status_idm     = mysqli_real_escape_string($conn, $status_idm);

$strengths      = mysqli_real_escape_string($conn, $strengths);
$weaknesses     = mysqli_real_escape_string($conn, $weaknesses);
$recommendation = mysqli_real_escape_string($conn, $recommendation);

$source         = mysqli_real_escape_string($conn, $source);
$status         = mysqli_real_escape_string($conn, $status);


// =====================================
// Cek Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id
    FROM idms
    WHERE slug='$slug'
    LIMIT 1"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location:create.php");
    exit;
}


// =====================================
// Folder Upload
// =====================================

$uploadDir = APP_PATH . "uploads/village/idm/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}


// =====================================
// Upload Infografik
// =====================================

$infographic = NULL;

if (!empty($_FILES['infographic']['name'])) {

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['infographic']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Format infografik tidak didukung.";

        header("Location:create.php");
        exit;
    }

    if ($_FILES['infographic']['size'] > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran infografik maksimal 2 MB.";

        header("Location:create.php");
        exit;
    }

    $infographic = uniqid('idm_') . "." . $ext;

    move_uploaded_file(
        $_FILES['infographic']['tmp_name'],
        $uploadDir . $infographic
    );
}


// =====================================
// Upload Dokumen
// =====================================

$document = NULL;

if (!empty($_FILES['document']['name'])) {

    $allowed = ['pdf'];

    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Dokumen harus berupa PDF.";

        header("Location:create.php");
        exit;
    }

    if ($_FILES['document']['size'] > (10 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran dokumen maksimal 10 MB.";

        header("Location:create.php");
        exit;
    }

    $document = uniqid('idm_doc_') . "." . $ext;

    move_uploaded_file(
        $_FILES['document']['tmp_name'],
        $uploadDir . $document
    );
}


// =====================================
// Simpan Database
// =====================================

$sql = "

INSERT INTO idms (

    year,
    title,
    slug,
    description,

    status_idm,

    idm_score,
    social_score,
    economic_score,
    environmental_score,
    target_score,

    ranking_regency,
    ranking_province,
    ranking_national,

    strengths,
    weaknesses,
    recommendation,

    infographic,
    document,

    source,
    status,

    created_by

)

VALUES (

    '$year',
    '$title',
    '$slug',
    '$description',

    '$status_idm',

    " . ($idm_score !== '' ? "'$idm_score'" : "NULL") . ",
    " . ($social_score !== '' ? "'$social_score'" : "NULL") . ",
    " . ($economic_score !== '' ? "'$economic_score'" : "NULL") . ",
    " . ($environmental_score !== '' ? "'$environmental_score'" : "NULL") . ",
    " . ($target_score !== '' ? "'$target_score'" : "NULL") . ",

    " . ($ranking_regency ? "'$ranking_regency'" : "NULL") . ",
    " . ($ranking_province ? "'$ranking_province'" : "NULL") . ",
    " . ($ranking_national ? "'$ranking_national'" : "NULL") . ",

    " . (!empty($strengths) ? "'$strengths'" : "NULL") . ",
    " . (!empty($weaknesses) ? "'$weaknesses'" : "NULL") . ",
    " . (!empty($recommendation) ? "'$recommendation'" : "NULL") . ",

    " . ($infographic ? "'$infographic'" : "NULL") . ",
    " . ($document ? "'$document'" : "NULL") . ",

    " . (!empty($source) ? "'$source'" : "NULL") . ",
    '$status',

    " . ($created_by ? "'$created_by'" : "NULL") . "

)

";

$query = mysqli_query($conn, $sql);


// =====================================
// Redirect
// =====================================

if ($query) {

    $_SESSION['success'] = "Data Indeks Desa Membangun berhasil ditambahkan.";
} else {

    $_SESSION['error'] = "Gagal menambahkan data.";
}

header("Location:index.php");
exit;
