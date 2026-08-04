<?php

require_once "../../../config/app.php";

// ===============================
// Validate ID
// ===============================

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {

    $_SESSION["error"] = "Data IDM tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===============================
// Get Data
// ===============================

$query = mysqli_query(
    $conn,
    "
    SELECT *
    FROM idms
    WHERE id = {$id}
    LIMIT 1
"
);

if (mysqli_num_rows($query) === 0) {

    $_SESSION["error"] = "Data IDM tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Delete Files
// ===============================

if (!empty($data["infographic"])) {

    $file = APP_PATH . "uploads/idm/" . $data["infographic"];

    if (file_exists($file)) {
        unlink($file);
    }
}

if (!empty($data["document"])) {

    $file = APP_PATH . "uploads/idm/" . $data["document"];

    if (file_exists($file)) {
        unlink($file);
    }
}

// ===============================
// Delete Database
// ===============================

$delete = mysqli_query(
    $conn,
    "
    DELETE FROM idms
    WHERE id = {$id}
    LIMIT 1
"
);

// ===============================
// Response
// ===============================

if ($delete) {

    $_SESSION["success"] = "Data IDM berhasil dihapus.";
} else {

    $_SESSION["error"] = "Gagal menghapus data IDM.";
}

header("Location: index.php");
exit;
