<?php

require_once '../../config/app.php';

// ======================================================
// Validasi
// ======================================================

if (!isset($_GET['potential_id'])) {
    header("Location:index.php");
    exit;
}

$potential_id = (int) $_GET['potential_id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM village_potentials
    WHERE id = '$potential_id'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    header("Location:index.php");
    exit;
}

$potential = mysqli_fetch_assoc($query);

// ======================================================

$title = "Tambah Produk Potensi";
$page  = "potensi-desa";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Tambah Produk
            </h1>

            <p class="text-slate-500 mt-2">
                <?= htmlspecialchars($potential['title']); ?>
            </p>

        </div>

        <a
            href="produk.php?potential_id=<?= $potential_id; ?>"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <form
        action="produk-store.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">

        <input
            type="hidden"
            name="potential_id"
            value="<?= $potential_id; ?>">

        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold mb-6">

                Informasi Produk

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-medium mb-2">

                        Nama Produk

                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        required
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Slug

                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        required
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Kategori

                    </label>

                    <select
                        name="category"
                        class="w-full rounded-xl border px-4 py-3">

                        <option value="Produk">Produk</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Paket Wisata">Paket Wisata</option>
                        <option value="Hasil Panen">Hasil Panen</option>
                        <option value="Hasil Peternakan">Hasil Peternakan</option>
                        <option value="Hasil Perikanan">Hasil Perikanan</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Harga

                    </label>

                    <input
                        type="number"
                        name="price"
                        min="0"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Satuan

                    </label>

                    <input
                        type="text"
                        name="unit"
                        placeholder="Contoh : pcs, kg, liter"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Stok

                    </label>

                    <input
                        type="number"
                        name="stock"
                        value="0"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        SKU

                    </label>

                    <input
                        type="text"
                        name="sku"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Gambar Produk

                    </label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border px-4 py-2">

                </div>

            </div>

            <div class="mt-6">

                <label class="block font-medium mb-2">

                    Deskripsi

                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full rounded-xl border px-4 py-3"></textarea>

            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="text-lg font-semibold mb-6">

                Pengaturan

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-medium mb-2">

                        Featured

                    </label>

                    <select
                        name="featured"
                        class="w-full rounded-xl border px-4 py-3">

                        <option value="No">Tidak</option>
                        <option value="Yes">Ya</option>

                    </select>

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Status

                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border px-4 py-3">

                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>

                    </select>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a
                href="produk.php?potential_id=<?= $potential_id; ?>"
                class="px-6 py-3 rounded-xl border">

                Batal

            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                <i class="bi bi-check-circle"></i>

                Simpan Produk

            </button>

        </div>

    </form>

</main>

<script>
    const name = document.getElementById('name');
    const slug = document.getElementById('slug');

    name.addEventListener('keyup', function() {

        slug.value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

    });
</script>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>