<?php
require_once '../../../config/app.php';

$title = "";
$page  = "berita";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<form
    action="store.php"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-8 p-8">
    <!-- Header -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Tambah Berita
            </h2>
            <p class="mt-2 text-slate-500">
                Buat berita atau artikel baru yang akan ditampilkan pada website desa.
            </p>
        </div>
        <div class="flex gap-3">
            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50">
                Kembali
            </a>
            <button
                type="submit"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white transition hover:bg-teal-700">
                Simpan Berita
            </button>
        </div>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Produk Hukum
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- Judul -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Produk Hukum
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            placeholder="Contoh: Peraturan Desa Tentang APBDes Tahun 2026"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none">
                    </div>
                    <!-- Slug -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Slug
                        </label>
                        <input
                            id="slug"
                            name="slug"
                            readonly
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>
                    <!-- Deskripsi -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Deskripsi
                        </label>
                        <textarea
                            rows="5"
                            name="description"
                            placeholder="Ringkasan isi produk hukum..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 outline-none"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- RIGHT -->
        <div class="space-y-8">
            <!-- Detail -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Detail Produk
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Jenis Produk
                        </label>
                        <select
                            name="type"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="Peraturan Desa">Peraturan Desa</option>
                            <option value="Peraturan Kepala Desa">Peraturan Kepala Desa</option>
                            <option value="Keputusan Kepala Desa">Keputusan Kepala Desa</option>
                            <option value="Peraturan Bersama">Peraturan Bersama</option>
                            <option value="Instruksi Kepala Desa">Instruksi Kepala Desa</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Nomor
                        </label>
                        <input
                            type="text"
                            name="number"
                            placeholder="Contoh: 5"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Tahun
                        </label>
                        <input
                            type="number"
                            name="year"
                            value="<?= date('Y') ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Tanggal Penetapan
                        </label>
                        <input
                            type="date"
                            name="issued_at"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>
                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="Published">Published</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Dokumen -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Dokumen
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- PDF -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            File PDF
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="pdf"
                            type="file"
                            name="file"
                            accept=".pdf"
                            required
                            class="block w-full rounded-xl border border-slate-300 px-4 py-3">
                        <p
                            id="pdfName"
                            class="mt-2 text-sm text-slate-500">
                            Belum ada file dipilih.
                        </p>
                    </div>
                    <!-- Thumbnail -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Thumbnail
                        </label>
                        <input
                            id="thumbnail"
                            type="file"
                            name="thumbnail"
                            accept="image/png,image/jpeg,image/webp"
                            class="block w-full rounded-xl border border-slate-300 px-4 py-3">
                        <img
                            id="thumbnailPreview"
                            class="mt-4 hidden h-48 w-full rounded-xl border object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    const title = document.getElementById('title');
    const slug = document.getElementById('slug');

    title.addEventListener('keyup', () => {

        slug.value = title.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

    });

    const pdf = document.getElementById('pdf');
    const pdfName = document.getElementById('pdfName');

    pdf.addEventListener('change', function() {

        pdfName.textContent = this.files.length ?
            this.files[0].name :
            'Belum ada file dipilih.';

    });

    const thumbnail = document.getElementById('thumbnail');
    const preview = document.getElementById('thumbnailPreview');

    thumbnail.addEventListener('change', function() {

        if (!this.files.length) {

            preview.classList.add('hidden');
            return;

        }

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;
            preview.classList.remove('hidden');

        }

        reader.readAsDataURL(thumbnail.files[0]);

    });
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>