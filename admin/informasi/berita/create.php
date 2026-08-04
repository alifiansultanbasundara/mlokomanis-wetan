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
            <!-- Informasi -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Berita
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- Judul -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            placeholder="Contoh: Gotong Royong Membersihkan Lingkungan Desa"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-600">
                    </div>
                    <!-- Slug -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="slug"
                            readonly
                            name="slug"
                            placeholder="Slug akan dibuat otomatis..."
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>
                    <!-- Excerpt -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Deskripsi Singkat
                        </label>
                        <textarea
                            rows="4"
                            name="excerpt"
                            placeholder="Ringkasan singkat berita (opsional)..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600"></textarea>
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Isi Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            rows="18"
                            required
                            placeholder="Tulis isi berita di sini..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-100 outline-none"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- RIGHT -->
        <div class="space-y-8">
            <!-- Publish -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Publikasi
                    </h3>
                </div>
                <div class="space-y-6 p-6">
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Kategori
                        </label>
                        <select
                            name="category"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="Berita">Berita</option>
                            <option value="Pengumuman">Pengumuman</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Pembangunan">Pembangunan</option>
                            <option value="Layanan">Layanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>
                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Thumbnail -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Thumbnail
                    </h3>
                </div>
                <div class="space-y-4 p-6">
                    <!-- Preview -->
                    <img
                        id="thumbnailPreview"
                        src="https://placehold.co/600x350/e2e8f0/64748b?text=Preview+Thumbnail"
                        class="hidden h-52 w-full rounded-xl border object-cover">
                    <!-- Upload -->
                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 px-6 py-10 text-center transition hover:border-teal-600 hover:bg-teal-50">
                        <i class="bi bi-image text-5xl text-slate-400"></i>
                        <p class="mt-4 font-medium text-slate-700">
                            Upload Thumbnail
                        </p>
                        <span class="mt-2 text-sm text-slate-500">
                            JPG, PNG atau WEBP (Opsional)
                        </span>
                        <input
                            id="thumbnail"
                            type="file"
                            name="thumbnail"
                            accept="image/png,image/jpeg,image/webp"
                            class="hidden">
                    </label>
                    <p
                        id="thumbnailName"
                        class="text-center text-sm text-slate-500">
                        Belum ada file dipilih.
                    </p>
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


    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailName = document.getElementById('thumbnailName');

    thumbnailInput.addEventListener('change', function() {

        const file = this.files[0];

        if (!file) {
            thumbnailPreview.classList.add('hidden');
            thumbnailName.textContent = "Belum ada file dipilih.";
            return;
        }

        thumbnailName.textContent = file.name;

        const reader = new FileReader();

        reader.onload = function(e) {

            thumbnailPreview.src = e.target.result;
            thumbnailPreview.classList.remove('hidden');

        }

        reader.readAsDataURL(file);

    });
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>