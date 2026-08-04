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

$id                 = (int) $_POST['id'];

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

$updated_by         = $_SESSION['id'] ?? NULL;


// =====================================
// Validasi
// =====================================

if (
    empty($id) ||
    empty($year) ||
    empty($title) ||
    empty($slug) ||
    empty($status_idm) ||
    $idm_score === ''
) {

    $_SESSION['error'] = "Silakan lengkapi data yang wajib diisi.";

    header("Location:edit.php?id=" . $id);
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
// Ambil Data Lama
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT *
    FROM idms
    WHERE id='$id'
    LIMIT 1"
);

if (!$query || mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$old = mysqli_fetch_assoc($query);


// =====================================
// Validasi Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id
    FROM idms
    WHERE slug='$slug'
    AND id != '$id'
    LIMIT 1"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location:edit.php?id=" . $id);
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

$infographic = $old['infographic'];

if (!empty($_FILES['infographic']['name'])) {

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $ext = strtolower(pathinfo($_FILES['infographic']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Format infografik tidak didukung.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if ($_FILES['infographic']['size'] > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran infografik maksimal 2 MB.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if (!empty($old['infographic']) && file_exists($uploadDir . $old['infographic'])) {
        unlink($uploadDir . $old['infographic']);
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

$document = $old['document'];

if (!empty($_FILES['document']['name'])) {

    $allowed = ['pdf'];

    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        $_SESSION['error'] = "Dokumen harus berupa PDF.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if ($_FILES['document']['size'] > (10 * 1024 * 1024)) {

        $_SESSION['error'] = "Ukuran dokumen maksimal 10 MB.";

        header("Location:edit.php?id=" . $id);
        exit;
    }

    if (!empty($old['document']) && file_exists($uploadDir . $old['document'])) {
        unlink($uploadDir . $old['document']);
    }

    $document = uniqid('idm_doc_') . "." . $ext;

    move_uploaded_file(
        $_FILES['document']['tmp_name'],
        $uploadDir . $document
    );
}


// =====================================
// Update Database
// =====================================

$sql = "

UPDATE idms SET

    year='$year',
    title='$title',
    slug='$slug',
    description='$description',

    status_idm='$status_idm',

    idm_score=" . ($idm_score !== '' ? "'$idm_score'" : "NULL") . ",
    social_score=" . ($social_score !== '' ? "'$social_score'" : "NULL") . ",
    economic_score=" . ($economic_score !== '' ? "'$economic_score'" : "NULL") . ",
    environmental_score=" . ($environmental_score !== '' ? "'$environmental_score'" : "NULL") . ",
    target_score=" . ($target_score !== '' ? "'$target_score'" : "NULL") . ",

    ranking_regency=" . ($ranking_regency ? "'$ranking_regency'" : "NULL") . ",
    ranking_province=" . ($ranking_province ? "'$ranking_province'" : "NULL") . ",
    ranking_national=" . ($ranking_national ? "'$ranking_national'" : "NULL") . ",

    strengths=" . (!empty($strengths) ? "'$strengths'" : "NULL") . ",
    weaknesses=" . (!empty($weaknesses) ? "'$weaknesses'" : "NULL") . ",
    recommendation=" . (!empty($recommendation) ? "'$recommendation'" : "NULL") . ",

    infographic=" . ($infographic ? "'$infographic'" : "NULL") . ",
    document=" . ($document ? "'$document'" : "NULL") . ",

    source=" . (!empty($source) ? "'$source'" : "NULL") . ",

    status='$status',

    updated_by=" . ($updated_by ? "'$updated_by'" : "NULL") . "

WHERE id='$id'

";

$result = mysqli_query($conn, $sql);


// =====================================
// Redirect
// =====================================

if ($result) {

    $_SESSION['success'] = "Data Indeks Desa Membangun berhasil diperbarui.";
} else {

    $_SESSION['error'] = "Gagal memperbarui data.";
}

header("Location:index.php");
exit;
