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
// Data
// =====================================================

$potential_id = (int) $_POST['potential_id'];

$name        = mysqli_real_escape_string($conn, trim($_POST['name']));
$slug        = slugify($_POST['slug'] ?: $_POST['name']);

$category    = mysqli_real_escape_string($conn, $_POST['category']);
$description = mysqli_real_escape_string($conn, $_POST['description']);

$price = ($_POST['price'] !== '')
    ? (float) $_POST['price']
    : "NULL";

$unit = mysqli_real_escape_string($conn, $_POST['unit']);

$stock = ($_POST['stock'] !== '')
    ? (int) $_POST['stock']
    : 0;

$sku = mysqli_real_escape_string($conn, $_POST['sku']);

$featured = mysqli_real_escape_string($conn, $_POST['featured']);
$status   = mysqli_real_escape_string($conn, $_POST['status']);

$sort_order = 0;

// =====================================================
// Validasi Potensi
// =====================================================

$checkPotential = mysqli_query($conn, "
    SELECT id
    FROM village_potentials
    WHERE id = '$potential_id'
    LIMIT 1
");

if (mysqli_num_rows($checkPotential) == 0) {

    $_SESSION['error'] = "Potensi desa tidak ditemukan.";

    header("Location:index.php");
    exit;
}

// =====================================================
// Validasi Slug
// =====================================================

$checkSlug = mysqli_query($conn, "
    SELECT id
    FROM village_potential_products
    WHERE slug = '$slug'
    LIMIT 1
");

if (mysqli_num_rows($checkSlug) > 0) {

    $_SESSION['error'] = "Slug sudah digunakan.";

    header("Location:produk-create.php?potential_id=" . $potential_id);
    exit;
}

// =====================================================
// Upload Image
// =====================================================

$image = NULL;

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $uploadDir = APP_PATH . "uploads/potentials/products/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $extension = strtolower(pathinfo(
        $_FILES['image']['name'],
        PATHINFO_EXTENSION
    ));

    $image = time() . "_" . uniqid() . "." . $extension;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadDir . $image
    );
}

// =====================================================
// Insert
// =====================================================

$query = "
INSERT INTO village_potential_products (

    potential_id,
    name,
    slug,
    category,
    description,
    image,
    price,
    unit,
    stock,
    sku,
    featured,
    sort_order,
    status

) VALUES (

    '$potential_id',
    '$name',
    '$slug',
    '$category',
    '$description',
    " . ($image ? "'$image'" : "NULL") . ",
    $price,
    '$unit',
    '$stock',
    '$sku',
    '$featured',
    '$sort_order',
    '$status'

)
";

if (mysqli_query($conn, $query)) {

    $_SESSION['success'] = "Produk berhasil ditambahkan.";

    header("Location:produk.php?potential_id=" . $potential_id);
    exit;
}

$_SESSION['error'] = "Gagal menyimpan produk.";

header("Location:produk-create.php?potential_id=" . $potential_id);
exit;
