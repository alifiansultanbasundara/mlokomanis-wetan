<?php

require_once '../../../config/app.php';

// ======================================================
// VALIDASI ID
// ======================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// ======================================================
// HELPER
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
// HANYA POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit.php?id=" . $id);
    exit;
}

// ======================================================
// AMBIL DATA LAMA
// ======================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM letter_types WHERE id = $id LIMIT 1"
);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php?error=not_found");
    exit;
}

$oldData = mysqli_fetch_assoc($result);

// ======================================================
// AMBIL INPUT
// ======================================================

$slug = clean($_POST['slug'] ?? '');
$name = clean($_POST['name'] ?? '');
$icon = clean($_POST['icon'] ?? 'bi-file-earmark-text');
$color = clean($_POST['color'] ?? 'teal');
$description = clean($_POST['description'] ?? '');
$template_body = clean($_POST['template_body'] ?? '');
$placeholder_map = clean($_POST['placeholder_map'] ?? '');
$status = clean($_POST['status'] ?? 'Aktif');
$sort_order = isset($_POST['sort_order'])
    ? (int) $_POST['sort_order']
    : 0;

// ======================================================
// VALIDASI
// ======================================================

if ($slug === '' || $name === '') {
    header("Location: edit.php?id=$id&error=required");
    exit;
}

if (!in_array($status, ['Aktif', 'Nonaktif'])) {
    $status = 'Aktif';
}

// ======================================================
// CEK SLUG DUPLIKAT
// ======================================================

$check = mysqli_query(
    $conn,
    "SELECT id
     FROM letter_types
     WHERE slug = '$slug'
     AND id != $id
     LIMIT 1"
);

if ($check && mysqli_num_rows($check) > 0) {
    header("Location: edit.php?id=$id&error=slug_exists");
    exit;
}

// ======================================================
// FILE TEMPLATE
// ======================================================

$filePath = $oldData['file_path'] ?? null;

if (
    isset($_FILES['template_file']) &&
    $_FILES['template_file']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['template_file']['error'] !== UPLOAD_ERR_OK) {
        header("Location: edit.php?id=$id&error=upload");
        exit;
    }

    $file = $_FILES['template_file'];

    // Maksimal 10 MB
    if ($file['size'] > 10 * 1024 * 1024) {
        header("Location: edit.php?id=$id&error=file_size");
        exit;
    }

    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    // Hanya DOCX
    if ($extension !== 'docx') {
        header("Location: edit.php?id=$id&error=file_type");
        exit;
    }

    // Folder upload
    $uploadDir = '../../../uploads/letter-templates/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Nama file baru
    $fileName =
        $slug .
        '-' .
        time() .
        '.docx';

    $targetPath = $uploadDir . $fileName;

    // Upload
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        header("Location: edit.php?id=$id&error=upload_failed");
        exit;
    }

    // Path yang disimpan di database
    $filePath = 'uploads/letter-templates/' . $fileName;

    // Hapus file lama jika ada
    if (
        !empty($oldData['file_path']) &&
        file_exists('../../../' . $oldData['file_path'])
    ) {
        unlink('../../../' . $oldData['file_path']);
    }
}

// Escape file path
$filePathSql = $filePath !== null
    ? "'" . clean($filePath) . "'"
    : "NULL";

// ======================================================
// UPDATE
// ======================================================

$query = "
    UPDATE letter_types SET

        slug = '$slug',
        name = '$name',
        icon = '$icon',
        color = '$color',
        description = " . ($description !== '' ? "'$description'" : "NULL") . ",
        template_body = " . ($template_body !== '' ? "'$template_body'" : "NULL") . ",
        file_path = $filePathSql,
        placeholder_map = " . ($placeholder_map !== '' ? "'$placeholder_map'" : "NULL") . ",
        status = '$status',
        sort_order = $sort_order

    WHERE id = $id
";

// ======================================================
// EKSEKUSI
// ======================================================

if (mysqli_query($conn, $query)) {

    header("Location: index.php?success=updated");
    exit;
}

// ======================================================
// GAGAL
// ======================================================

header(
    "Location: edit.php?id=$id&error=update"
);
exit;
