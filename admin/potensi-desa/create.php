<?php
require_once '../../config/app.php';

$title = "Tambah Potensi Desa";
$page  = "potensi-desa";

include APP_PATH . 'includes/admin/layout-top.php';
?>

<main class="p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Tambah Potensi Desa
            </h1>

            <p class="text-slate-500 mt-1">
                Tambahkan data potensi desa seperti UMKM, pertanian, wisata, peternakan dan lainnya.
            </p>
        </div>

        <a href="index.php"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <div class="flex items-start gap-3">
            <i class="bi bi-info-circle-fill text-blue-600 text-xl"></i>

            <div>
                <h3 class="font-semibold text-blue-800">
                    Informasi
                </h3>

                <p class="text-sm text-blue-700 mt-1">
                    Setelah data potensi desa berhasil disimpan,
                    Anda dapat menambahkan daftar produk atau layanan
                    yang dimiliki melalui halaman <b>Produk Potensi</b>.
                </p>
            </div>
        </div>
    </div>

    <form
        action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">

        <!-- Informasi Potensi -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Informasi Potensi
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="font-medium">Judul *</label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        required
                        class="w-full mt-2 rounded-xl border px-4 py-3">
                </div>

                <div>
                    <label class="font-medium">Slug *</label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        required
                        class="w-full mt-2 rounded-xl border px-4 py-3">
                </div>

                <div>
                    <label class="font-medium">
                        Kategori
                    </label>

                    <select
                        name="category"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                        <option>UMKM</option>
                        <option>Pertanian</option>
                        <option>Perkebunan</option>
                        <option>Peternakan</option>
                        <option>Perikanan</option>
                        <option>Pariwisata</option>
                        <option>Kerajinan</option>
                        <option>Industri Rumah Tangga</option>
                        <option>Kuliner</option>
                        <option>Jasa</option>
                        <option>BUMDes</option>
                        <option>Sumber Daya Alam</option>
                        <option>Energi</option>
                        <option>Ekonomi Kreatif</option>
                        <option>Lainnya</option>

                    </select>
                </div>

                <div>
                    <label class="font-medium">
                        Tahun Berdiri
                    </label>

                    <input
                        type="number"
                        min="1900"
                        max="<?= date('Y'); ?>"
                        name="established_year"
                        class="w-full mt-2 rounded-xl border px-4 py-3">
                </div>

            </div>

            <div class="mt-6">

                <label class="font-medium">
                    Deskripsi
                </label>

                <textarea
                    rows="6"
                    name="description"
                    class="w-full mt-2 rounded-xl border px-4 py-3"></textarea>

            </div>

        </div>

        <!-- Pemilik -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Informasi Pemilik
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <input
                    type="text"
                    name="owner_name"
                    placeholder="Nama Pemilik"
                    class="rounded-xl border px-4 py-3">

                <input
                    type="text"
                    name="organization"
                    placeholder="Nama Organisasi"
                    class="rounded-xl border px-4 py-3">

            </div>

        </div>

        <!-- Kontak -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Kontak
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <textarea
                    name="address"
                    rows="3"
                    placeholder="Alamat"
                    class="rounded-xl border px-4 py-3"></textarea>

                <div class="space-y-4">

                    <input
                        type="text"
                        name="phone"
                        placeholder="Telepon"
                        class="w-full rounded-xl border px-4 py-3">

                    <input
                        type="text"
                        name="whatsapp"
                        placeholder="WhatsApp"
                        class="w-full rounded-xl border px-4 py-3">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        class="w-full rounded-xl border px-4 py-3">

                    <input
                        type="text"
                        name="website"
                        placeholder="Website"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

            </div>

        </div>

        <!-- Lokasi -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Lokasi
            </h2>

            <div class="grid md:grid-cols-3 gap-6">

                <input
                    type="text"
                    name="latitude"
                    placeholder="Latitude"
                    class="rounded-xl border px-4 py-3">

                <input
                    type="text"
                    name="longitude"
                    placeholder="Longitude"
                    class="rounded-xl border px-4 py-3">

                <input
                    type="text"
                    name="google_maps"
                    placeholder="Link Google Maps"
                    class="rounded-xl border px-4 py-3">

            </div>

        </div>

        <!-- Tambahan -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Informasi Tambahan
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <input
                    type="text"
                    name="operational_hours"
                    placeholder="Jam Operasional"
                    class="rounded-xl border px-4 py-3">

                <input
                    type="text"
                    name="price_range"
                    placeholder="Kisaran Harga"
                    class="rounded-xl border px-4 py-3">

            </div>

            <textarea
                rows="4"
                name="facilities"
                placeholder="Fasilitas"
                class="w-full mt-6 rounded-xl border px-4 py-3"></textarea>

        </div>

        <!-- Upload -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h2 class="font-bold text-lg mb-6">
                Upload File
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label>Foto</label>

                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="mt-2 block w-full">

                </div>

                <div>

                    <label>Brosur (PDF)</label>

                    <input
                        type="file"
                        name="brochure"
                        accept=".pdf"
                        class="mt-2 block w-full">

                </div>

            </div>

        </div>

        <!-- Pengaturan -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label>Featured</label>

                    <select
                        name="featured"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                        <option value="No">Tidak</option>
                        <option value="Yes">Ya</option>

                    </select>

                </div>

                <div>

                    <label>Status</label>

                    <select
                        name="status"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>

                    </select>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-4">

            <a
                href="index.php"
                class="px-6 py-3 border rounded-xl">

                Batal

            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                <i class="bi bi-check-circle"></i>
                Simpan Data

            </button>

        </div>

    </form>

</main>

<script>
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    title.addEventListener('keyup', function() {

        slug.value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

    });
</script>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>