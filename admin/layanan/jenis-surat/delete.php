<?php

require_once '../../../config/app.php';

// ======================================================
// VALIDASI ID
// ======================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$id = (int) $_GET['id'];

// ======================================================
// AMBIL DATA JENIS SURAT
// ======================================================

$query = mysqli_query(
    $conn,
    "SELECT id, file_path
     FROM letter_types
     WHERE id = $id
     LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0) {
    header("Location: index.php?error=not_found");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ======================================================
// HAPUS FILE TEMPLATE
// ======================================================

if (!empty($data['file_path'])) {

    $filePath = '../../../' . $data['file_path'];

    if (file_exists($filePath) && is_file($filePath)) {
        unlink($filePath);
    }
}

// ======================================================
// HAPUS DATA
// ======================================================

$delete = mysqli_query(
    $conn,
    "DELETE FROM letter_types
     WHERE id = $id"
);

// ======================================================
// HASIL
// ======================================================

if ($delete) {

    header("Location: index.php?success=deleted");
    exit;
}

// ======================================================
// GAGAL
// ======================================================

header("Location: index.php?error=delete");
exit;
