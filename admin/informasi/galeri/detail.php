<?php
require_once '../../../config/app.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
SELECT
    g.*,
    u.nama AS author
FROM galleries g
LEFT JOIN users u
ON u.id = g.created_by
WHERE g.slug = '$slug'
LIMIT 1
");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// Ambil seluruh foto
$images = mysqli_query($conn, "
SELECT *
FROM gallery_images
WHERE gallery_id = {$data['id']}
ORDER BY id ASC
");

$totalImages = mysqli_num_rows($images);

$title = "Detail Galeri";
$page  = "galeri";

include APP_PATH . 'includes/admin/layout-top.php';
?>

<div class="p-8">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Detail Galeri
            </h2>

            <p class="mt-2 text-slate-500">
                Informasi lengkap album galeri.
            </p>
        </div>

        <div class="flex gap-3">

            <a
                href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>

            <a
                href="edit.php?slug=<?= urlencode($data['slug']) ?>"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Edit Galeri

            </a>

        </div>

    </div>


    <div class="grid gap-8 lg:grid-cols-3">

        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">

            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h1 class="text-3xl font-bold text-slate-900">
                        <?= htmlspecialchars($data['title']) ?>
                    </h1>

                    <?php if (!empty($data['description'])) : ?>

                        <p class="mt-4 text-slate-600 leading-7">
                            <?= nl2br(htmlspecialchars($data['description'])) ?>
                        </p>

                    <?php endif; ?>

                </div>

                <div class="p-6">

                    <?php if ($totalImages > 0): ?>

                        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">

                            <?php while ($img = mysqli_fetch_assoc($images)): ?>

                                <a
                                    href="<?= APP_URL ?>uploads/informasi/galeri/<?= $img['image']; ?>"
                                    target="_blank">

                                    <img
                                        src="<?= APP_URL ?>uploads/informasi/galeri/<?= $img['image']; ?>"
                                        class="h-52 w-full rounded-xl object-cover transition hover:scale-[1.02]"
                                        alt="">

                                </a>

                            <?php endwhile; ?>

                        </div>

                    <?php else: ?>

                        <div class="py-16 text-center">

                            <i class="bi bi-images text-6xl text-slate-300"></i>

                            <p class="mt-4 text-slate-500">

                                Album ini belum memiliki foto.

                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- RIGHT -->
        <div class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">
                        Informasi Album
                    </h3>

                </div>

                <div class="space-y-5 p-6">

                    <div>

                        <p class="text-sm text-slate-500">
                            Status
                        </p>

                        <?php if ($data['status'] == 'Published'): ?>

                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700">
                                Published
                            </span>

                        <?php else: ?>

                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-700">
                                Draft
                            </span>

                        <?php endif; ?>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Jumlah Foto
                        </p>

                        <p class="font-semibold text-slate-800">
                            <?= number_format($totalImages) ?> Foto
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat Oleh
                        </p>

                        <p class="font-medium text-slate-800">
                            <?= htmlspecialchars($data['author']) ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat
                        </p>

                        <p class="font-medium text-slate-800">
                            <?= tanggalIndonesia($data['created_at'], 'dd MMMM yyyy') ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Terakhir Diubah
                        </p>

                        <p class="font-medium text-slate-800">
                            <?= tanggalIndonesia($data['updated_at'], 'dd MMMM yyyy') ?>
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Slug
                        </p>

                        <div class="break-all rounded-xl bg-slate-100 p-3 text-sm text-slate-700">
                            <?= htmlspecialchars($data['slug']) ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>