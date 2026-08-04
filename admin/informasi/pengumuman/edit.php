<?php
require_once '../../../config/app.php';

if (!isset($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
        SELECT *
        FROM announcements
        WHERE slug='$slug'
        LIMIT 1
    ");

if (mysqli_num_rows($query) == 0) {
    $_SESSION['error'] = "Pengumuman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$title = "";
$page = "pengumuman";

include APP_PATH . 'includes/admin/layout-top.php';
?>
<form
    action="update.php?slug=<?= urlencode($data['slug']) ?>"
    method="POST"
    class="space-y-8 p-8">
    <!-- Header -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Edit Pengumuman
            </h2>
            <p class="mt-2 text-slate-500">
                Perbarui informasi pengumuman yang akan ditampilkan pada website desa.
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
                Simpan Perubahan
            </button>
        </div>
    </div>
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">
            <!-- Informasi Pengumuman -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Pengumuman
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- Judul -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Pengumuman <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            value="<?= htmlspecialchars($data['title']) ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-600">
                    </div>
                    <!-- Slug -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Slug
                        </label>
                        <input
                            <input
                            id="slug"
                            name="slug"
                            readonly
                            value="<?= htmlspecialchars($data['slug']) ?>"
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">
                    </div>
                    <!-- Isi -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Isi Pengumuman <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            rows="16"
                            required
                            placeholder="Tuliskan isi pengumuman..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-100">
                        <?= htmlspecialchars($data['content']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- RIGHT -->
        <div class="space-y-8">
            <!-- Publikasi -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Publikasi
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- Jenis -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Jenis
                        </label>
                        <select name="type"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="Pengumuman" <?= $data['type'] == 'Pengumuman' ? 'selected' : '' ?>>
                                Pengumuman
                            </option>
                            <option value="Informasi" <?= $data['type'] == 'Informasi' ? 'selected' : '' ?>>
                                Informasi
                            </option>
                            <option value="Agenda" <?= $data['type'] == 'Agenda' ? 'selected' : '' ?>>
                                Agenda
                            </option>
                            <option value="Bansos" <?= $data['type'] == 'Bansos' ? 'selected' : '' ?>>
                                Bansos
                            </option>
                            <option value="Kesehatan" <?= $data['type'] == 'Kesehatan' ? 'selected' : '' ?>>
                                Kesehatan
                            </option>
                            <option value="Pendidikan" <?= $data['type'] == 'Pendidikan' ? 'selected' : '' ?>>
                                Pendidikan
                            </option>
                            <option value="Darurat" <?= $data['type'] == 'Darurat' ? 'selected' : '' ?>>
                                Darurat
                            </option>
                        </select>
                    </div>
                    <!-- Status -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>
                        <select name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option
                                value="Published"
                                <?= $data['status'] == "Published" ? "selected" : "" ?>>
                                Published
                            </option>
                            <option
                                value="Draft"
                                <?= $data['status'] == "Draft" ? "selected" : "" ?>>
                                Draft
                            </option>
                        </select>
                    </div>
                    <!-- Urutan -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Urutan Tampil
                        </label>
                        <input
                            type="number"
                            name="priority"
                            value="<?= $data['priority'] ?>"
                            min="0"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Mulai Tampil
                        </label>
                        <input
                            type="date"
                            name="start_date"
                            value="<?= $data['start_date'] ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Berakhir Tampil
                        </label>
                        <input
                            type="date"
                            name="end_date"
                            value="<?= $data['end_date'] ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    </div>
                </div>
            </div>
            <!-- Tampilan -->
            <div class="rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="font-semibold text-slate-900">
                        Tampilan
                    </h3>
                </div>
                <div class="space-y-5 p-6">
                    <!-- Icon -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Icon Bootstrap
                        </label>
                        <select name="icon"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="bi-megaphone" <?= $data['icon'] == "bi-megaphone" ? "selected" : "" ?>>
                                📢 Megaphone
                            </option>
                            <option value="bi-info-circle" <?= $data['icon'] == "bi-info-circle" ? "selected" : "" ?>>
                                ℹ️ Informasi
                            </option>
                            <option value="bi-calendar-event" <?= $data['icon'] == "bi-calendar-event" ? "selected" : "" ?>>
                                📅 Agenda
                            </option>
                            <option value="bi-box-seam" <?= $data['icon'] == "bi-box-seam" ? "selected" : "" ?>>
                                📦 Bantuan Sosial
                            </option>
                            <option value="bi-heart-pulse" <?= $data['icon'] == "bi-heart-pulse" ? "selected" : "" ?>>
                                ❤️ Kesehatan
                            </option>
                            <option value="bi-mortarboard" <?= $data['icon'] == "bi-mortarboard" ? "selected" : "" ?>>
                                🎓 Pendidikan
                            </option>
                            <option value="bi-exclamation-triangle" <?= $data['icon'] == "bi-exclamation-triangle" ? "selected" : "" ?>>
                                ⚠️ Darurat
                            </option>
                        </select>
                    </div>
                    <!-- Warna -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Warna
                        </label>
                        <select name="color"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="blue" <?= $data['color'] == "blue" ? "selected" : "" ?>>Blue</option>
                            <option value="green" <?= $data['color'] == "green" ? "selected" : "" ?>>Green</option>
                            <option value="red" <?= $data['color'] == "red" ? "selected" : "" ?>>Red</option>
                            <option value="yellow" <?= $data['color'] == "yellow" ? "selected" : "" ?>>Yellow</option>
                            <option value="orange" <?= $data['color'] == "orange" ? "selected" : "" ?>>Orange</option>
                            <option value="purple" <?= $data['color'] == "purple" ? "selected" : "" ?>>Purple</option>
                            <option value="gray" <?= $data['color'] == "gray" ? "selected" : "" ?>>Gray</option>
                        </select>
                    </div>
                    <!-- Popup -->
                    <div>
                        <label class="mb-2 block font-medium text-slate-700">
                            Tampilkan Sebagai Popup
                        </label>
                        <select
                            name="is_popup"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">
                            <option value="0"
                                <?= $data['is_popup'] == 0 ? "selected" : "" ?>>
                                Tidak
                            </option>
                            <option value="1"
                                <?= $data['is_popup'] == 1 ? "selected" : "" ?>>
                                Ya
                            </option>
                        </select>
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
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>