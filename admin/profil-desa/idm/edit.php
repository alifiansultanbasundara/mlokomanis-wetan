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
// Get IDM
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

if (mysqli_num_rows($query) == 0) {

    $_SESSION["error"] = "Data IDM tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// ===============================
// Layout
// ===============================

$title = "Edit Indeks Desa Membangun";
$page  = "idm";

include APP_PATH . "includes/admin/layout-top.php";
?>

<div class="p-8 space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Edit Indeks Desa Membangun
            </h1>

            <p class="mt-2 text-slate-500">
                Perbarui informasi Indeks Desa Membangun (IDM).
            </p>

        </div>

        <a
            href="index.php"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <?php if (isset($_SESSION["error"])): ?>

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            <?= $_SESSION["error"] ?>

        </div>

        <?php unset($_SESSION["error"]); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION["success"])): ?>

        <div class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-4 text-teal-700">

            <i class="bi bi-check-circle-fill me-2"></i>

            <?= $_SESSION["success"] ?>

        </div>

        <?php unset($_SESSION["success"]); ?>

    <?php endif; ?>

    <!-- FORM -->
    <form
        action="update.php"
        method="POST"
        enctype="multipart/form-data"
        class="grid gap-8 lg:grid-cols-3">

        <input
            type="hidden"
            name="id"
            value="<?= $data["id"] ?>">

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
                                value="<?= htmlspecialchars($data["year"]) ?>"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Sumber Data
                            </label>
                            <input
                                type="text"
                                name="source"
                                value="<?= htmlspecialchars($data["source"]) ?>"
                                placeholder="Kemendes RI / BPS / OpenSID"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                        </div>

                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="<?= htmlspecialchars($data["title"]) ?>"
                            placeholder="Indeks Desa Membangun Tahun 2026"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Slug
                        </label>
                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            readonly
                            value="<?= htmlspecialchars($data["slug"]) ?>"
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>

                    <div>
                        <label class="block font-medium mb-2">
                            Penjelasan IDM
                        </label>
                        <textarea
                            name="description"
                            rows="8"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"><?= htmlspecialchars($data["description"]) ?></textarea>
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
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"><?= htmlspecialchars($data["strengths"] ?? "") ?></textarea>
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
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"><?= htmlspecialchars($data["weaknesses"] ?? "") ?></textarea>
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
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500"><?= htmlspecialchars($data["recommendation"] ?? "") ?></textarea>
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
                            <option
                                value="Desa Sangat Tertinggal"
                                <?= $data["status_idm"] == "Desa Sangat Tertinggal" ? "selected" : "" ?>>
                                Desa Sangat Tertinggal
                            </option>
                            <option
                                value="Desa Tertinggal"
                                <?= $data["status_idm"] == "Desa Tertinggal" ? "selected" : "" ?>>
                                Desa Tertinggal
                            </option>
                            <option
                                value="Desa Berkembang"
                                <?= $data["status_idm"] == "Desa Berkembang" ? "selected" : "" ?>>
                                Desa Berkembang
                            </option>
                            <option
                                value="Desa Maju"
                                <?= $data["status_idm"] == "Desa Maju" ? "selected" : "" ?>>
                                Desa Maju
                            </option>
                            <option
                                value="Desa Mandiri"
                                <?= $data["status_idm"] == "Desa Mandiri" ? "selected" : "" ?>>
                                Desa Mandiri
                            </option>
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
                            value="<?= $data["idm_score"] ?>"
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
                                value="<?= $data["social_score"] ?>"
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
                                value="<?= $data["economic_score"] ?>"
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
                                value="<?= $data["environmental_score"] ?>"
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
                            value="<?= $data["target_score"] ?>"
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
                            value="<?= $data["ranking_regency"] ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-700">
                            Ranking Provinsi
                        </label>
                        <input type="number"
                            name="ranking_province"
                            value="<?= $data["ranking_province"] ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-700">
                            Ranking Nasional
                        </label>
                        <input type="number"
                            name="ranking_national"
                            value="<?= $data["ranking_national"] ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-teal-500">
                    </div>

                </div>
            </div>

            <!-- Upload File -->
            <!-- Upload File -->
            <div class="bg-white rounded-2xl border border-slate-200">

                <div class="border-b px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        File Pendukung
                    </h2>
                </div>

                <div class="p-6 space-y-6">

                    <!-- Infografik -->
                    <div>

                        <label class="mb-2 block font-medium">
                            Infografik
                        </label>

                        <?php if (!empty($data["infographic"])): ?>

                            <img
                                src="<?= APP_URL ?>uploads/idm/<?= htmlspecialchars($data["infographic"]) ?>"
                                alt="Infografik IDM"
                                class="mb-4 h-40 w-full rounded-xl border object-cover">

                        <?php endif; ?>

                        <input
                            type="file"
                            name="infographic"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <p class="mt-2 text-xs text-slate-500">
                            JPG, PNG, WEBP (maks. 2 MB)
                        </p>

                    </div>


                    <!-- Dokumen -->
                    <div>

                        <label class="mb-2 block font-medium">
                            Dokumen IDM
                        </label>

                        <?php if (!empty($data["document"])): ?>

                            <a
                                href="<?= APP_URL ?>uploads/idm/<?= htmlspecialchars($data["document"]) ?>"
                                target="_blank"
                                class="mb-3 inline-flex items-center gap-2 text-sm text-teal-600 hover:underline">

                                <i class="bi bi-file-earmark-pdf"></i>

                                Lihat Dokumen Saat Ini

                            </a>

                        <?php endif; ?>

                        <input
                            type="file"
                            name="document"
                            accept=".pdf"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <p class="mt-2 text-xs text-slate-500">
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
                            <option
                                value="Published"
                                <?= $data["status"] == "Published" ? "selected" : "" ?>>
                                Published
                            </option>

                            <option
                                value="Draft"
                                <?= $data["status"] == "Draft" ? "selected" : "" ?>>
                                Draft
                            </option>
                        </select>
                    </div>

                </div>
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white py-4 rounded-xl font-semibold transition">
                Perbarui Data IDM
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