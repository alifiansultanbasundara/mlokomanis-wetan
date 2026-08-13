<?php

require_once '../../../config/app.php';

// ======================================================
// HANYA POST
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}


// ======================================================
// VALIDASI ID
// ======================================================

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$id = (int) $_POST['id'];


// ======================================================
// CEK DATA
// ======================================================

$check = mysqli_query(
    $conn,
    "SELECT id
     FROM populations
     WHERE id = '$id'
     LIMIT 1"
);

if (!$check || mysqli_num_rows($check) === 0) {
    header("Location: index.php?error=not_found");
    exit;
}


// ======================================================
// DELETE
// ======================================================

$delete = mysqli_query(
    $conn,
    "DELETE FROM populations
     WHERE id = '$id'
     LIMIT 1"
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

header("Location: index.php?error=delete_failed");
exit;
