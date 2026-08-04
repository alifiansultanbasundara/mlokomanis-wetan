<?php

require_once '../../config/app.php';

// ======================================================
// Validasi
// ======================================================

if (!isset($_GET['service_id'])) {
    header("Location:index.php");
    exit;
}

$service_id = (int) $_GET['service_id'];

// ======================================================
// Data Layanan
// ======================================================

$service = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE id = '$service_id'
    LIMIT 1
");

if (mysqli_num_rows($service) == 0) {
    header("Location:index.php");
    exit;
}

$service = mysqli_fetch_assoc($service);

// ======================================================
// Tracking
// ======================================================

$trackings = mysqli_query($conn, "
    SELECT *
    FROM letter_trackings
    WHERE service_id = '$service_id'
    ORDER BY id DESC
");

$total = mysqli_num_rows($trackings);

$title = "Tracking Surat";
$page  = "pengajuan";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Tracking Surat

            </h1>

            <p class="text-slate-500 mt-2">

                <?= htmlspecialchars($service['name']); ?>

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="index.php"
                class="px-5 py-3 rounded-xl border hover:bg-slate-50">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <a
                href="tracking-create.php?service_id=<?= $service_id; ?>"
                class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                <i class="bi bi-plus-circle"></i>

                Tambah Tracking

            </a>

        </div>

    </div>

    <!-- Statistik -->

    <div class="grid md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <div class="text-slate-500 text-sm">

                Total Pengajuan

            </div>

            <div class="text-3xl font-bold mt-2">

                <?= $total; ?>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr class="text-left text-sm font-semibold text-slate-600">

                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">HP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($trackings) > 0): ?>

                        <?php
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($trackings)):

                            switch ($row['status']) {

                                case 'Menunggu Verifikasi':
                                    $badge = "bg-yellow-100 text-yellow-700";
                                    break;

                                case 'Diproses':
                                    $badge = "bg-blue-100 text-blue-700";
                                    break;

                                case 'Menunggu Dokumen':
                                    $badge = "bg-orange-100 text-orange-700";
                                    break;

                                case 'Selesai':
                                    $badge = "bg-emerald-100 text-emerald-700";
                                    break;

                                default:
                                    $badge = "bg-red-100 text-red-700";
                                    break;
                            }

                        ?>

                            <tr class="border-t hover:bg-slate-50">

                                <td class="px-6 py-4">

                                    <?= $no++; ?>

                                </td>

                                <td class="px-6 py-4 font-semibold">

                                    <?= htmlspecialchars($row['tracking_code']); ?>

                                </td>

                                <td class="px-6 py-4">

                                    <?= htmlspecialchars($row['applicant_name']); ?>

                                </td>

                                <td class="px-6 py-4">

                                    <?= htmlspecialchars($row['phone']); ?>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badge; ?>">

                                        <?= $row['status']; ?>

                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <?= date('d M Y', strtotime($row['created_at'])); ?>

                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a
                                            href="tracking-detail.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a
                                            href="tracking-edit.php?id=<?= $row['id']; ?>"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a
                                            href="tracking-delete.php?id=<?= $row['id']; ?>"
                                            onclick="return confirm('Hapus data tracking ini?')"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 hover:bg-red-200 text-red-700">

                                            <i class="bi bi-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="text-center py-12">

                                <i class="bi bi-inbox text-5xl text-slate-300"></i>

                                <p class="mt-4 text-slate-500">

                                    Belum ada data tracking.

                                </p>

                                <a
                                    href="tracking-create.php?service_id=<?= $service_id; ?>"
                                    class="inline-flex mt-5 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                                    <i class="bi bi-plus-circle mr-2"></i>

                                    Tambah Tracking

                                </a>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>