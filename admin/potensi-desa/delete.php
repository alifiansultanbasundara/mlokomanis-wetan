<?php

require_once "../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data potensi desa tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT image, brochure
    FROM village_potentials
    WHERE id = {$id}
    LIMIT 1
    "
);

if (mysqli_num_rows($query) == 0) {

    $_SESSION["error"] = "Data potensi desa tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Delete Files
// ===============================

$uploadDir = APP_PATH . "uploads/potentials/";

if (!empty($data["image"])) {

    $imagePath = $uploadDir . $data["image"];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

if (!empty($data["brochure"])) {

    $brochurePath = $uploadDir . $data["brochure"];

    if (file_exists($brochurePath)) {
        unlink($brochurePath);
    }
}

// ===============================
// Delete Database
// ===============================

$delete = mysqli_query(
    $conn,
    "
    DELETE FROM village_potentials
    WHERE id = {$id}
    LIMIT 1
    "
);

// ===============================
// Response
// ===============================

if ($delete) {

    $_SESSION["success"] = "Potensi desa berhasil dihapus.";
} else {

    $_SESSION["error"] = "Gagal menghapus data potensi desa.";
}

header("Location: index.php");
exit;
