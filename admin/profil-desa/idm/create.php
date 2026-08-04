<?php
require_once '../../../config/app.php';

$title = 'Tambah Data IDM';
$page  = 'idm';

include APP_PATH . 'includes/admin/layout-top.php';
?>

<div class="p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Tambah Data Indeks Desa Membangun (IDM)
            </h1>
            <p class="text-slate-500 mt-2">
                Tambahkan informasi dan nilai IDM desa.
            </p>
        </div>

        <a href="index.php"
            class="px-5 py-3 rounded-xl border border-slate-300 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <form action="store.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid lg:grid-cols-3 gap-8">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Informasi IDM -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Informasi IDM
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block font-medium mb-2">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                name="year"
                                required
                                value="<?= date('Y'); ?>"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Sumber Data
                            </label>
                            <input type="text"
                                name="source"
                                placeholder="Kemendes RI / BPS / OpenSID"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="title"
                            id="title"
                            required
                            placeholder="Indeks Desa Membangun Tahun 2026"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Slug
                        </label>
                        <input type="text"
                            name="slug"
                            id="slug"
                            readonly
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Penjelasan IDM
                        </label>
                        <textarea name="description"
                            rows="8"
                            placeholder="Jelaskan apa itu Indeks Desa Membangun dan kondisi desa saat ini..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>

                </div>
            </div>

            <!-- Kelebihan -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Kelebihan Desa
                    </h2>
                </div>

                <div class="p-6">
                    <textarea name="strengths"
                        rows="6"
                        placeholder="Tuliskan keunggulan desa berdasarkan indikator IDM..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>

            <!-- Kekurangan -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Kekurangan / Permasalahan Desa
                    </h2>
                </div>

                <div class="p-6">
                    <textarea name="weaknesses"
                        rows="6"
                        placeholder="Tuliskan permasalahan atau indikator yang masih perlu ditingkatkan..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>

            <!-- Rekomendasi -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Rekomendasi / Tindak Lanjut
                    </h2>
                </div>

                <div class="p-6">
                    <textarea name="recommendation"
                        rows="6"
                        placeholder="Tuliskan rekomendasi untuk meningkatkan nilai IDM desa..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-8">

            <!-- Nilai IDM -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Nilai IDM
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block font-medium mb-2">
                            Status IDM
                        </label>
                        <select name="status_idm"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                            <option value="Desa Sangat Tertinggal">Desa Sangat Tertinggal</option>
                            <option value="Desa Tertinggal">Desa Tertinggal</option>
                            <option value="Desa Berkembang" selected>Desa Berkembang</option>
                            <option value="Desa Maju">Desa Maju</option>
                            <option value="Desa Mandiri">Desa Mandiri</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Nilai IDM <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                            step="0.0001"
                            min="0"
                            max="1"
                            name="idm_score"
                            required
                            placeholder="0.8234"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="grid grid-cols-1 gap-4">

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-700">
                                IKS (Ketahanan Sosial)
                            </label>
                            <input type="number"
                                step="0.0001"
                                min="0"
                                max="1"
                                name="social_score"
                                placeholder="0.9000"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-700">
                                IKE (Ketahanan Ekonomi)
                            </label>
                            <input type="number"
                                step="0.0001"
                                min="0"
                                max="1"
                                name="economic_score"
                                placeholder="0.7000"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-700">
                                IKL (Ketahanan Lingkungan)
                            </label>
                            <input type="number"
                                step="0.0001"
                                min="0"
                                max="1"
                                name="environmental_score"
                                placeholder="0.8000"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Target IDM Tahun Berikutnya
                        </label>
                        <input type="number"
                            step="0.0001"
                            min="0"
                            max="1"
                            name="target_score"
                            placeholder="0.8500"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                </div>
            </div>

            <!-- Ranking -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Ranking (Opsional)
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-700">
                            Ranking Kabupaten
                        </label>
                        <input type="number"
                            name="ranking_regency"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-700">
                            Ranking Provinsi
                        </label>
                        <input type="number"
                            name="ranking_province"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-700">
                            Ranking Nasional
                        </label>
                        <input type="number"
                            name="ranking_national"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                </div>
            </div>

            <!-- Upload File -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        File Pendukung
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block font-medium mb-2">
                            Infografik
                        </label>
                        <input type="file"
                            name="infographic"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                        <p class="text-xs text-slate-500 mt-2">
                            JPG, PNG, WEBP (maks. 2 MB)
                        </p>
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Dokumen IDM
                        </label>
                        <input type="file"
                            name="document"
                            accept=".pdf"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                        <p class="text-xs text-slate-500 mt-2">
                            PDF (maks. 10 MB)
                        </p>
                    </div>

                </div>
            </div>

            <!-- Publish -->
            <div class="bg-white rounded-2xl border border-slate-200">
                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Publish
                    </h2>
                </div>

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block font-medium mb-2">
                            Status
                        </label>
                        <select name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                            <option value="Published" selected>Published</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>

                </div>
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white py-4 rounded-xl font-semibold transition">
                Simpan Data IDM
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