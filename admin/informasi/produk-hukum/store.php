<?php

require_once '../../../config/app.php';

// =====================================
// Hanya menerima POST
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// =====================================
// Ambil Data
// =====================================

$title       = mysqli_real_escape_string($conn, trim($_POST['title']));
$slug        = mysqli_real_escape_string($conn, strtolower(trim($_POST['slug'])));
$description = mysqli_real_escape_string($conn, trim($_POST['description']));

$type        = mysqli_real_escape_string($conn, $_POST['type']);
$number      = mysqli_real_escape_string($conn, trim($_POST['number']));
$year        = (int) $_POST['year'];

$issued_at = !empty($_POST['issued_at'])
    ? "'" . mysqli_real_escape_string($conn, $_POST['issued_at']) . "'"
    : "NULL";

$status      = mysqli_real_escape_string($conn, $_POST['status']);

$created_by  = $_SESSION['id'];

// =====================================
// Validasi
// =====================================

if (
    empty($title) ||
    empty($slug) ||
    empty($type) ||
    empty($year)
) {

    $_SESSION['error'] = "Mohon lengkapi seluruh data yang wajib diisi.";

    header("Location: create.php");
    exit;
}

// =====================================
// Validasi Jenis
// =====================================

$allowedType = [
    'Peraturan Desa',
    'Peraturan Kepala Desa',
    'Keputusan Kepala Desa',
    'Peraturan Bersama',
    'Instruksi Kepala Desa'
];

if (!in_array($type, $allowedType)) {

    $_SESSION['error'] = "Jenis produk hukum tidak valid.";

    header("Location: create.php");
    exit;
}

// =====================================
// Validasi Status
// =====================================

$allowedStatus = [
    'Published',
    'Draft'
];

if (!in_array($status, $allowedStatus)) {

    $_SESSION['error'] = "Status tidak valid.";

    header("Location: create.php");
    exit;
}

// =====================================
// Cek Slug
// =====================================

$check = mysqli_query(
    $conn,
    "SELECT id
    FROM legal_instruments
    WHERE slug='$slug'"
);

if (mysqli_num_rows($check) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location: create.php");
    exit;
}

// =====================================
// Upload PDF
// =====================================

$pdf = "";

if (empty($_FILES['file']['name'])) {

    $_SESSION['error'] = "Dokumen PDF wajib diupload.";

    header("Location: create.php");
    exit;
}

$pdfExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

if ($pdfExt != "pdf") {

    $_SESSION['error'] = "Dokumen harus berupa PDF.";

    header("Location: create.php");
    exit;
}

if ($_FILES['file']['size'] > (10 * 1024 * 1024)) {

    $_SESSION['error'] = "Ukuran PDF maksimal 10 MB.";

    header("Location: create.php");
    exit;
}

$pdfPath = APP_PATH . "uploads/informasi/produk-hukum/";

if (!is_dir($pdfPath)) {
    mkdir($pdfPath, 0777, true);
}

$pdf = uniqid("produk_hukum_", true) . ".pdf";

if (!move_uploaded_file(
    $_FILES['file']['tmp_name'],
    $pdfPath . $pdf
)) {

    $_SESSION['error'] = "Upload PDF gagal.";

    header("Location: create.php");
    exit;
}

// =====================================
// Upload Thumbnail
// =====================================

$thumbnail = NULL;

if (!empty($_FILES['thumbnail']['name'])) {

    $allowedImage = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    $ext = strtolower(pathinfo(
        $_FILES['thumbnail']['name'],
        PATHINFO_EXTENSION
    ));

    if (!in_array($ext, $allowedImage)) {

        $_SESSION['error'] = "Thumbnail harus JPG, PNG atau WEBP.";

        header("Location: create.php");
        exit;
    }

    if ($_FILES['thumbnail']['size'] > (2 * 1024 * 1024)) {

        $_SESSION['error'] = "Thumbnail maksimal 2 MB.";

        header("Location: create.php");
        exit;
    }

    $thumbPath = APP_PATH . "uploads/informasi/produk-hukum/";

    if (!is_dir($thumbPath)) {
        mkdir($thumbPath, 0777, true);
    }

    $thumbnail = uniqid("thumbnail_", true) . "." . $ext;

    if (!move_uploaded_file(
        $_FILES['thumbnail']['tmp_name'],
        $thumbPath . $thumbnail
    )) {

        $_SESSION['error'] = "Upload thumbnail gagal.";

        header("Location: create.php");
        exit;
    }
}

// =====================================
// Simpan Database
// =====================================

$sql = "
INSERT INTO legal_instruments
(
    title,
    slug,
    description,
    category,
    document_number,
    document_year,
    file,
    file_size,
    effective_date,
    status,
    created_by
)
VALUES
(
    '$title',
    '$slug',
    '$description',
    '$type',
    '$number',
    '$year',
    '$pdf',
    '" . $_FILES['file']['size'] . "',
    $issued_at,
    '$status',
    '$created_by'
)
";

if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Produk hukum berhasil ditambahkan.";
} else {

    $_SESSION['error'] = "Gagal menambahkan produk hukum.";
}

header("Location: index.php");
exit;
