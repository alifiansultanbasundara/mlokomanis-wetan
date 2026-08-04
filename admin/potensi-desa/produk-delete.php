<?php

require_once '../../config/app.php';


// ======================================================
// Validasi ID
// ======================================================

if (!isset($_GET['id'])) {

    header("Location:index.php");

    exit;
}


$id = (int) $_GET['id'];




// ======================================================
// Ambil Data Produk
// ======================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM village_potential_products
    WHERE id='$id'
    LIMIT 1
");



if (mysqli_num_rows($query) == 0) {


    header("Location:index.php");

    exit;
}



$product = mysqli_fetch_assoc($query);





$potential_id = $product['potential_id'];

$image = $product['image'];





// ======================================================
// Hapus Gambar
// ======================================================


if (!empty($image)) {


    $file = "../../uploads/potentials/products/" . $image;



    if (file_exists($file)) {


        unlink($file);
    }
}





// ======================================================
// Hapus Database
// ======================================================


$delete = mysqli_query($conn, "
    DELETE FROM village_potential_products
    WHERE id='$id'
");





// ======================================================
// Redirect
// ======================================================


if ($delete) {


    header(
        "Location:produk.php?potential_id=" . $potential_id
    );


    exit;
} else {


    echo "

    <script>

    alert('Produk gagal dihapus');

    history.back();

    </script>

    ";
}
