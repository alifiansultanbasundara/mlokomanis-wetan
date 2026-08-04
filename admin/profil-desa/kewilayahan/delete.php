<?php

require_once "../../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data kewilayahan tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT image, document
    FROM regionals
    WHERE id = {$id}
    LIMIT 1
    "
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION["error"] = "Data kewilayahan tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Delete Files
// ===============================

if (!empty($data["image"])) {

    $imagePath = APP_PATH . "uploads/village/regionals/" . $data["image"];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

if (!empty($data["document"])) {

    $documentPath = APP_PATH . "uploads/village/regionals/" . $data["document"];

    if (file_exists($documentPath)) {
        unlink($documentPath);
    }
}

// ===============================
// Delete Database
// ===============================

$delete = mysqli_query(
    $conn,
    "
    DELETE FROM regionals
    WHERE id = {$id}
    LIMIT 1
    "
);

// ===============================
// Response
// ===============================

if ($delete) {

    $_SESSION["success"] = "Data kewilayahan berhasil dihapus.";
} else {

    $_SESSION["error"] = "Gagal menghapus data kewilayahan.";
}

header("Location: index.php");
exit;
