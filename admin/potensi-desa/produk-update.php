<?php

require_once '../../config/app.php';


// ======================================================
// Validasi Method
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location:index.php");
    exit;
}



// ======================================================
// Helper
// ======================================================

function clean($data)
{

    global $conn;

    return mysqli_real_escape_string(
        $conn,
        trim($data ?? '')
    );
}




function createSlug($text)
{

    $text = strtolower($text);

    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}




// ======================================================
// Ambil Input
// ======================================================

$id = (int) $_POST['id'];

$potential_id = (int) $_POST['potential_id'];


$name = clean($_POST['name']);

$slug = createSlug($name);


$category = clean($_POST['category']);

$description = clean($_POST['description']);

$price = clean($_POST['price']);

$unit = clean($_POST['unit']);

$stock = (int) $_POST['stock'];

$sku = clean($_POST['sku']);

$featured = clean($_POST['featured']);

$sort_order = (int) $_POST['sort_order'];

$status = clean($_POST['status']);





// ======================================================
// Ambil Data Lama
// ======================================================

$oldQuery = mysqli_query($conn, "
    SELECT image
    FROM village_potential_products
    WHERE id='$id'
    LIMIT 1
");


$old = mysqli_fetch_assoc($oldQuery);


$oldImage = $old['image'] ?? null;



// ======================================================
// Upload Image
// ======================================================

$imageName = $oldImage;



if (
    isset($_FILES['image']) &&
    $_FILES['image']['name'] != ''
) {


    $file = $_FILES['image'];



    $extension = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );



    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];



    if (!in_array($extension, $allowed)) {


        echo "Format gambar tidak diperbolehkan";

        exit;
    }




    // Folder

    $folder = "../../uploads/potentials/products/";



    if (!is_dir($folder)) {

        mkdir($folder, 0777, true);
    }





    // Nama baru

    $imageName = time()
        . "-"
        . uniqid()
        . "."
        . $extension;



    move_uploaded_file(
        $file['tmp_name'],
        $folder . $imageName
    );





    // Hapus gambar lama

    if (
        $oldImage &&
        file_exists($folder . $oldImage)
    ) {

        unlink($folder . $oldImage);
    }
}




// ======================================================
// Update Database
// ======================================================


$query = mysqli_query($conn, "
    
    UPDATE village_potential_products SET


        name='$name',

        slug='$slug',

        category='$category',

        description='$description',

        image='$imageName',

        price='$price',

        unit='$unit',

        stock='$stock',

        sku='$sku',

        featured='$featured',

        sort_order='$sort_order',

        status='$status'


    WHERE id='$id'


");






// ======================================================
// Redirect
// ======================================================


if ($query) {


    header(
        "Location:produk.php?potential_id=" . $potential_id
    );


    exit;
} else {


    echo "
    
    <script>

    alert('Gagal memperbarui produk');

    history.back();

    </script>
    
    ";
}
