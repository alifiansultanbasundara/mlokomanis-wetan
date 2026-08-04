<?php
require_once '../../../config/app.php';
require_once APP_PATH . 'config/database.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
    SELECT *
    FROM articles
    WHERE slug = '$slug'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$title = "";
$page  = "berita";

include APP_PATH . 'includes/admin/layout-top.php';
?>

<form
    action="update.php"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-8 px-8">

    <input type="hidden" name="id" value="<?= $data['id']; ?>">
    <input type="hidden" name="old_thumbnail" value="<?= $data['thumbnail']; ?>">

    <!-- Header -->
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">
                Edit Berita
            </h2>

            <p class="mt-2 text-slate-500">
                Perbarui informasi berita yang telah dibuat.
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

            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Berita
                    </h3>

                </div>

                <div class="space-y-5 p-6">

                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            value="<?= htmlspecialchars($data['title']); ?>"
                            placeholder="Masukkan judul berita..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-600">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Slug <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="slug"
                            readonly
                            name="slug"
                            value="<?= htmlspecialchars($data['slug']); ?>"
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-slate-500">

                    </div>

                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Deskripsi Singkat
                        </label>

                        <textarea
                            rows="4"
                            name="excerpt"
                            placeholder="Ringkasan singkat berita..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600"><?= htmlspecialchars($data['excerpt']); ?></textarea>

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
                            placeholder="Tulis isi berita..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($data['content']); ?></textarea>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-8">

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

                            <?php
                            $kategori = ['Berita', 'Pengumuman', 'Kegiatan', 'Pembangunan', 'Layanan'];

                            foreach ($kategori as $item) :
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

                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            <option value="Draft" <?= $data['status'] == 'Draft' ? 'selected' : ''; ?>>
                                Draft
                            </option>

                            <option value="Published" <?= $data['status'] == 'Published' ? 'selected' : ''; ?>>
                                Published
                            </option>

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

                    <img
                        id="thumbnailPreview"
                        src="<?= !empty($data['thumbnail']) ? APP_URL . 'uploads/informasi/berita/' . $data['thumbnail'] : 'https://placehold.co/600x350/e2e8f0/64748b?text=Preview+Thumbnail'; ?>"
                        class="h-52 w-full rounded-xl border object-cover">

                    <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 px-6 py-10 text-center transition hover:border-teal-600 hover:bg-teal-50">

                        <i class="bi bi-image text-5xl text-slate-400"></i>

                        <p class="mt-4 font-medium text-slate-700">
                            Ganti Thumbnail
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

                        <?= !empty($data['thumbnail']) ? htmlspecialchars($data['thumbnail']) : 'Belum ada file dipilih.'; ?>

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

        if (!file) return;

        thumbnailName.textContent = file.name;

        const reader = new FileReader();

        reader.onload = function(e) {

            thumbnailPreview.src = e.target.result;

        }

        reader.readAsDataURL(file);

    });
</script>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>