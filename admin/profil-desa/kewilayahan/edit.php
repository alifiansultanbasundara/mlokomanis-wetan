<?php

require_once '../../../config/app.php';


// =====================================
// Validasi ID
// =====================================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location:index.php");
    exit;
}

$id = (int) $_GET['id'];


// =====================================
// Ambil Data
// =====================================

$query = mysqli_query(
    $conn,
    "SELECT *
    FROM regionals
    WHERE id='$id'
    LIMIT 1"
);

if (!$query || mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);


// =====================================
// Layout
// =====================================

$title = "Edit Kewilayahan";
$page  = "kewilayahan";

include APP_PATH . 'includes/admin/layout-top.php';

?>

<div class="p-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Edit Data Kewilayahan
            </h1>

            <p class="mt-2 text-slate-500">
                Perbarui data peta atau kewilayahan desa.
            </p>

        </div>

        <a
            href="index.php"
            class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50">

            Kembali

        </a>

    </div>


    <form
        action="update.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid lg:grid-cols-3 gap-8">

        <input
            type="hidden"
            name="id"
            value="<?= $data['id']; ?>">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Informasi Peta
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Judul Peta
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            required
                            value="<?= htmlspecialchars($data['title']); ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            readonly
                            value="<?= htmlspecialchars($data['slug']); ?>"
                            class="w-full rounded-xl bg-slate-100 border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Kategori
                        </label>

                        <select
                            name="category"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <?php

                            $categories = [

                                'Peta Administrasi',
                                'Peta RT/RW',
                                'Peta Blok SPPT PBB',
                                'Peta Tata Guna Lahan',
                                'Peta Mata Pencaharian',
                                'Peta Infrastruktur',
                                'Peta Rawan Bencana',
                                'Peta Potensi Desa',
                                'Peta Sebaran Penduduk',
                                'Peta Batas Dusun',
                                'Peta Fasilitas Umum',
                                'Lainnya'

                            ];

                            foreach ($categories as $item):

                            ?>

                                <option
                                    value="<?= $item; ?>"
                                    <?= $data['category'] == $item ? 'selected' : ''; ?>>

                                    <?= $item; ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="7"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data['description']); ?></textarea>

                    </div>

                </div>

            </div>


            <!-- Lokasi -->

            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Lokasi
                    </h2>

                </div>

                <div class="p-6 grid md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            value="<?= htmlspecialchars($data["latitude"] ?? "") ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            value="<?= htmlspecialchars($data['longitude'] ?? ""); ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div class="md:col-span-2">

                        <label class="block mb-2 font-medium">
                            Google Maps
                        </label>

                        <textarea
                            name="google_maps"
                            rows="3"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3"><?= htmlspecialchars($data['google_maps'] ?? ""); ?></textarea>

                    </div>

                </div>

            </div>

        </div>


        <!-- RIGHT -->

        <div class="space-y-8">

            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        Detail
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block mb-2 font-medium">
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="year"
                            value="<?= $data['year']; ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Skala
                        </label>

                        <input
                            type="text"
                            name="scale"
                            value="<?= htmlspecialchars($data["scale"] ?? "") ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <option value="Published" <?= $data['status'] == 'Published' ? 'selected' : ''; ?>>
                                Published
                            </option>

                            <option value="Draft" <?= $data['status'] == 'Draft' ? 'selected' : ''; ?>>
                                Draft
                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">
                            Urutan
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="<?= $data['sort_order']; ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                </div>

            </div>


            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">

                    <h2 class="font-semibold">
                        File
                    </h2>

                </div>

                <div class="p-6 space-y-5">

                    <?php if (!empty($data['image'])): ?>

                        <div>

                            <p class="text-sm text-slate-500 mb-2">
                                Preview Saat Ini
                            </p>

                            <img
                                src="<?= APP_URL; ?>uploads/village/regionals/<?= $data['image']; ?>"
                                class="rounded-xl w-full border">

                        </div>

                    <?php endif; ?>

                    <div>

                        <label class="block mb-2 font-medium">
                            Ganti Preview
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                    <?php if (!empty($data['document'])): ?>

                        <a
                            href="<?= APP_URL; ?>uploads/village/regionals/<?= $data['document']; ?>"
                            target="_blank"
                            class="block text-teal-600 font-medium">

                            📄 Lihat Dokumen Saat Ini

                        </a>

                    <?php endif; ?>

                    <div>

                        <label class="block mb-2 font-medium">
                            Ganti Dokumen
                        </label>

                        <input
                            type="file"
                            name="document"
                            accept=".pdf,.jpg,.jpeg,.png,.zip,.rar,.dwg"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    </div>

                </div>

            </div>

            <button
                class="w-full bg-teal-600 hover:bg-teal-700 text-white py-4 rounded-xl font-semibold">

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

<script>
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    title.addEventListener('keyup', function() {

        slug.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');

    });
</script>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>