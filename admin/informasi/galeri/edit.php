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
    FROM galleries
    WHERE slug = '$slug'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// =====================================
// Ambil Cover Album
// =====================================

$coverImage = $data['cover_image'] ?? null;

$title = "";
$page  = "galeri";

include APP_PATH . 'includes/admin/layout-top.php';
?>

<form
    action="update.php"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-8 px-8">

    <input type="hidden" name="id" value="<?= $data['id']; ?>">
    <input
        type="hidden"
        name="old_cover"
        value="<?= htmlspecialchars($coverImage); ?>">

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

            <!-- Informasi Album -->
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Informasi Album
                    </h3>
                </div>

                <div class="space-y-5 p-6">

                    <!-- Hidden ID -->
                    <input
                        type="hidden"
                        name="id"
                        value="<?= $data['id']; ?>">

                    <!-- Judul -->
                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Judul Album <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="title"
                            type="text"
                            name="title"
                            required
                            value="<?= htmlspecialchars($data['title']); ?>"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600">

                    </div>

                    <!-- Slug -->
                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Slug
                        </label>

                        <input
                            id="slug"
                            readonly
                            name="slug"
                            value="<?= htmlspecialchars($data['slug']); ?>"
                            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3">

                    </div>

                    <!-- Deskripsi -->
                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Deskripsi Album
                        </label>

                        <textarea
                            rows="6"
                            name="description"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-600"><?= htmlspecialchars($data['description']); ?></textarea>

                    </div>

                </div>

            </div>

            <!-- Cover Album -->

            <div class="rounded-2xl border border-slate-200 bg-white">


                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="text-lg font-semibold text-slate-900">

                        Cover Album

                    </h3>

                </div>


                <div class="space-y-5 p-6">


                    <?php if ($coverImage): ?>


                        <img

                            src="<?= APP_URL ?>uploads/informasi/galeri/cover/<?= htmlspecialchars($coverImage); ?>"

                            class="h-56 w-full rounded-2xl object-cover">


                    <?php else: ?>


                        <div class="flex h-56 items-center justify-center rounded-2xl bg-slate-100">

                            <i class="bi bi-images text-5xl text-slate-400"></i>

                        </div>


                    <?php endif; ?>



                    <div>


                        <label class="mb-2 block font-medium text-slate-700">

                            Ganti Cover Album

                        </label>


                        <input

                            type="file"

                            name="cover_image"

                            accept=".jpg,.jpeg,.png,.webp"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        <p class="mt-2 text-sm text-slate-500">

                            Gunakan gambar terbaik untuk tampilan utama album.

                        </p>


                    </div>


                </div>


            </div>


            <!-- Foto Saat Ini -->
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="text-lg font-semibold text-slate-900">
                        Foto Dalam Album
                    </h3>

                </div>

                <div class="grid grid-cols-2 gap-4 p-6 md:grid-cols-3">

                    <?php

                    $foto = mysqli_query($conn, "
            SELECT *
            FROM gallery_images
            WHERE gallery_id='{$data['id']}'
            ORDER BY id ASC
        ");

                    while ($img = mysqli_fetch_assoc($foto)):
                    ?>

                        <div class="group relative">

                            <img
                                src="<?= APP_URL ?>uploads/informasi/galeri/<?= $img['image']; ?>"
                                class="h-40 w-full rounded-xl object-cover">

                            <label class="absolute right-2 top-2 rounded-lg bg-red-500 px-2 py-1 text-xs text-white">

                                <input
                                    type="checkbox"
                                    name="delete_images[]"
                                    value="<?= $img['id']; ?>"
                                    class="mr-1">

                                Hapus

                            </label>

                        </div>

                    <?php endwhile; ?>

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

                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Status
                        </label>

                        <select
                            name="status"
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

                </div>

            </div>


            <!-- Tambah Foto -->
            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">
                        Tambah Foto Baru
                    </h3>

                </div>

                <div class="space-y-4 p-6">

                    <label class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 px-6 py-10 hover:border-teal-600 hover:bg-teal-50">

                        <i class="bi bi-images text-5xl text-slate-400"></i>

                        <p class="mt-4 font-medium">
                            Tambahkan Foto Baru
                        </p>

                        <span class="text-sm text-slate-500">
                            JPG, PNG atau WEBP
                        </span>

                        <input
                            id="images"
                            type="file"
                            name="images[]"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp"
                            class="hidden">

                    </label>

                    <div
                        id="previewImages"
                        class="grid grid-cols-2 gap-3"></div>

                </div>

            </div>

        </div>

    </div>

</form>

<script>
    const title = document.getElementById("title");
    const slug = document.getElementById("slug");

    title.addEventListener("keyup", () => {

        slug.value = title.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-");

    });

    const input = document.getElementById("images");
    const preview = document.getElementById("previewImages");

    input.addEventListener("change", function() {

        preview.innerHTML = "";

        [...this.files].forEach(file => {

            const reader = new FileReader();

            reader.onload = function(e) {

                preview.innerHTML += `
                <img
                    src="${e.target.result}"
                    class="h-32 w-full rounded-xl object-cover border">
            `;

            }

            reader.readAsDataURL(file);

        });

    });
</script>
<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>