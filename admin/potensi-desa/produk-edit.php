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
// Ambil Produk
// ======================================================

$productQuery = mysqli_query($conn, "
    SELECT *
    FROM village_potential_products
    WHERE id='$id'
    LIMIT 1
");



if (mysqli_num_rows($productQuery) == 0) {

    header("Location:index.php");

    exit;
}


$product = mysqli_fetch_assoc($productQuery);




// ======================================================
// Ambil Potensi Desa
// ======================================================

$potential_id = $product['potential_id'];



$potentialQuery = mysqli_query($conn, "
    SELECT *
    FROM village_potentials
    WHERE id='$potential_id'
    LIMIT 1
");


$potential = mysqli_fetch_assoc($potentialQuery);




// ======================================================
// Kategori Produk
// ======================================================

$categories = [

    "Produk",

    "Jasa",

    "Paket Wisata",

    "Hasil Panen",

    "Kerajinan"

];




// ======================================================
// Page
// ======================================================

$title = "Edit Produk Potensi Desa";

$page = "potensi-desa";


include APP_PATH . "includes/admin/layout-top.php";

?>



<main class="p-8 space-y-6">



    <!-- HEADER -->

    <div class="flex justify-between items-center">


        <div>


            <h1 class="text-3xl font-bold text-slate-800">

                Edit Produk Potensi Desa

            </h1>


            <p class="text-slate-500 mt-2">


                <?= htmlspecialchars($potential['title'] ?? 'Potensi Desa'); ?>


                <span class="mx-2">
                    •
                </span>


                <?= htmlspecialchars($product['name']); ?>


            </p>


        </div>





        <div class="flex gap-3">


            <a
                href="produk.php?potential_id=<?= $potential_id; ?>"
                class="px-5 py-3 rounded-xl border hover:bg-slate-50">


                <i class="bi bi-arrow-left"></i>

                Kembali


            </a>


        </div>


    </div>






    <!-- FORM -->

    <form
        action="produk-update.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid lg:grid-cols-3 gap-8">



        <input
            type="hidden"
            name="id"
            value="<?= $product['id']; ?>">



        <input
            type="hidden"
            name="potential_id"
            value="<?= $potential_id; ?>">





        <!-- LEFT -->

        <div class="lg:col-span-2 space-y-6">



            <!-- INFORMASI PRODUK -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Informasi Produk

                </h2>





                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Nama Produk *

                    </label>


                    <input
                        type="text"
                        name="name"
                        value="<?= htmlspecialchars($product['name']); ?>"
                        required
                        class="w-full mt-2 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-teal-500">


                </div>





                <div class="grid md:grid-cols-2 gap-5">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Kategori

                        </label>


                        <select
                            name="category"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <?php foreach ($categories as $cat): ?>


                                <option
                                    value="<?= $cat; ?>"
                                    <?= $product['category'] == $cat ? 'selected' : ''; ?>>


                                    <?= $cat; ?>


                                </option>


                            <?php endforeach; ?>


                        </select>


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Satuan

                        </label>


                        <input
                            type="text"
                            name="unit"
                            value="<?= htmlspecialchars($product['unit']); ?>"
                            placeholder="Contoh: Kg, Paket, Orang"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>


                </div>






                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Deskripsi

                    </label>


                    <textarea
                        name="description"
                        rows="6"
                        class="w-full mt-2 px-4 py-3 rounded-xl border"><?= htmlspecialchars($product['description']); ?></textarea>


                </div>



            </div>






            <!-- DETAIL PENJUALAN -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Detail Produk

                </h2>



                <div class="grid md:grid-cols-3 gap-5">



                    <div>


                        <label class="text-sm">

                            Harga

                        </label>


                        <input
                            type="number"
                            name="price"
                            value="<?= $product['price']; ?>"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>





                    <div>


                        <label class="text-sm">

                            Stok

                        </label>


                        <input
                            type="number"
                            name="stock"
                            value="<?= $product['stock']; ?>"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>






                    <div>


                        <label class="text-sm">

                            SKU

                        </label>


                        <input
                            type="text"
                            name="sku"
                            value="<?= htmlspecialchars($product['sku']); ?>"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>



                </div>



            </div>

            <!-- GAMBAR PRODUK -->

            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Foto Produk

                </h2>



                <div class="grid md:grid-cols-2 gap-6">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Upload Foto Baru

                        </label>


                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                        <p class="text-xs text-slate-500 mt-2">

                            Kosongkan jika tidak ingin mengganti gambar.

                        </p>


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Foto Saat Ini

                        </label>


                        <div class="mt-2">


                            <?php if (!empty($product['image'])): ?>


                                <img
                                    src="<?= APP_URL ?>uploads/potentials/products/<?= $product['image']; ?>"
                                    class="w-32 h-32 object-cover rounded-xl border">


                            <?php else: ?>


                                <div
                                    class="w-32 h-32 rounded-xl bg-slate-100 flex items-center justify-center">


                                    <i class="bi bi-image text-4xl text-slate-400"></i>


                                </div>


                            <?php endif; ?>


                        </div>


                    </div>



                </div>



            </div>






            <!-- PENGATURAN -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Pengaturan Produk

                </h2>




                <div class="grid md:grid-cols-3 gap-5">





                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Produk Unggulan

                        </label>


                        <select
                            name="featured"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <option
                                value="No"
                                <?= $product['featured'] == "No" ? 'selected' : ''; ?>>

                                Tidak

                            </option>


                            <option
                                value="Yes"
                                <?= $product['featured'] == "Yes" ? 'selected' : ''; ?>>

                                Ya

                            </option>


                        </select>


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Urutan Tampil

                        </label>


                        <input
                            type="number"
                            name="sort_order"
                            value="<?= $product['sort_order']; ?>"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Status

                        </label>


                        <select
                            name="status"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">



                            <option
                                value="Published"
                                <?= $product['status'] == "Published" ? 'selected' : ''; ?>>

                                Published

                            </option>




                            <option
                                value="Draft"
                                <?= $product['status'] == "Draft" ? 'selected' : ''; ?>>

                                Draft

                            </option>



                        </select>


                    </div>




                </div>


            </div>





        </div>
        <!-- END LEFT -->






        <!-- RIGHT SIDEBAR -->


        <div class="space-y-6">



            <!-- ACTION -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold mb-5">

                    Aksi

                </h2>



                <button
                    type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">


                    <i class="bi bi-save"></i>


                    Simpan Perubahan


                </button>





                <a
                    href="produk.php?potential_id=<?= $potential_id; ?>"
                    class="block text-center mt-3 px-5 py-3 rounded-xl border hover:bg-slate-50">


                    Batal


                </a>


            </div>







            <!-- INFO -->


            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold mb-5">

                    Informasi

                </h2>




                <div class="space-y-3 text-sm">



                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            ID Produk

                        </span>


                        <span class="font-medium">

                            #<?= $product['id']; ?>

                        </span>


                    </div>





                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Dibuat

                        </span>


                        <span class="font-medium">

                            <?= date(
                                'd M Y',
                                strtotime($product['created_at'])
                            ); ?>


                        </span>


                    </div>





                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Update

                        </span>


                        <span class="font-medium">

                            <?= date(
                                'd M Y',
                                strtotime($product['updated_at'])
                            ); ?>


                        </span>


                    </div>



                </div>


            </div>







            <!-- POTENSI -->

            <div class="bg-white border rounded-2xl p-6">


                <h2 class="text-lg font-semibold mb-4">

                    Potensi Desa

                </h2>


                <p class="text-sm text-slate-600">


                    <?= htmlspecialchars($potential['title'] ?? '-'); ?>


                </p>



                <span class="inline-block mt-3 px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs">


                    <?= htmlspecialchars($potential['category'] ?? '-'); ?>


                </span>


            </div>




        </div>



    </form>





</main>




<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>