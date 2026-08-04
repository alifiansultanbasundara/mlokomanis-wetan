<?php
require_once '../../../config/app.php';
require_once APP_PATH . 'config/database.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

$query = mysqli_query($conn, "
SELECT
    a.*,
    creator.nama AS created_by_name,
    updater.nama AS updated_by_name
FROM announcements a
LEFT JOIN users creator
    ON creator.id = a.created_by
LEFT JOIN users updater
    ON updater.id = a.updated_by
WHERE a.slug='$slug'
LIMIT 1
");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$title = "Detail Pengumuman";
$page  = "pengumuman";

include APP_PATH . 'includes/admin/layout-top.php';
?>

<div class="p-8">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">
                Detail Pengumuman
            </h2>

            <p class="mt-2 text-slate-500">
                Informasi lengkap pengumuman desa.
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

                Edit Pengumuman

            </a>

        </div>

    </div>

    <div class="grid gap-8 lg:grid-cols-3">

        <!-- LEFT -->
        <div class="space-y-8 lg:col-span-2">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-sm font-medium text-teal-700">
                        <?= htmlspecialchars($data['type']) ?>
                    </span>

                    <h1 class="mt-4 text-3xl font-bold text-slate-900">
                        <?= htmlspecialchars($data['title']) ?>
                    </h1>

                    <?php if (!empty($data['start_date']) || !empty($data['end_date'])): ?>

                        <p class="mt-4 text-slate-500">

                            Periode :

                            <?= !empty($data['start_date']) ? date('d F Y', strtotime($data['start_date'])) : '-' ?>

                            s/d

                            <?= !empty($data['end_date']) ? date('d F Y', strtotime($data['end_date'])) : '-' ?>

                        </p>

                    <?php endif; ?>

                </div>

                <div class="p-6 leading-8 text-slate-700">

                    <?= nl2br($data['content']); ?>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-6 py-5">

                    <h3 class="font-semibold text-slate-900">
                        Informasi
                    </h3>

                </div>

                <div class="space-y-5 p-6">

                    <div>

                        <p class="text-sm text-slate-500">
                            Status
                        </p>

                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium <?= $data['status'] == 'Published'
                                                                                                ? 'bg-emerald-100 text-emerald-700'
                                                                                                : 'bg-yellow-100 text-yellow-700'; ?>">

                            <?= htmlspecialchars($data['status']) ?>

                        </span>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Jenis
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['type']) ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Icon
                        </p>

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">

                                <i class="<?= htmlspecialchars($data['icon']) ?>"></i>

                            </div>

                            <span class="font-medium">

                                <?= htmlspecialchars($data['icon']) ?>

                            </span>

                        </div>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Warna
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= ucfirst(htmlspecialchars($data['color'])) ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Urutan Tampil
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= $data['priority'] ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Popup
                        </p>

                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium <?= $data['is_popup']
                                                                                                ? 'bg-blue-100 text-blue-700'
                                                                                                : 'bg-slate-100 text-slate-700'; ?>">

                            <?= $data['is_popup'] ? 'Ya' : 'Tidak' ?>

                        </span>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat Oleh
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['created_by_name'] ?? '-') ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Terakhir Diubah Oleh
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= htmlspecialchars($data['updated_by_name'] ?? '-') ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Dibuat
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= date('d F Y H:i', strtotime($data['created_at'])) ?>

                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Terakhir Diubah
                        </p>

                        <p class="font-medium text-slate-800">

                            <?= date('d F Y H:i', strtotime($data['updated_at'])) ?>

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