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

$query = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE id='$service_id'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    header("Location:index.php");
    exit;
}

$service = mysqli_fetch_assoc($query);

$title = "Tambah Tracking Surat";
$page  = "pelayanan-surat";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Tambah Tracking Surat

            </h1>

            <p class="text-slate-500 mt-2">

                <?= htmlspecialchars($service['name']); ?>

            </p>

        </div>

        <a
            href="tracking.php?service_id=<?= $service_id; ?>"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <form
        action="tracking-store.php"
        method="POST"
        class="space-y-8">

        <input
            type="hidden"
            name="service_id"
            value="<?= $service_id; ?>">

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">

                Data Pemohon

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-medium mb-2">

                        Nama Pemohon

                    </label>

                    <input
                        type="text"
                        name="applicant_name"
                        required
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        NIK

                    </label>

                    <input
                        type="text"
                        name="nik"
                        maxlength="16"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="block font-medium mb-2">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">

                Status Pengajuan

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-medium mb-2">

                        Status

                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border px-4 py-3">

                        <option value="Menunggu Verifikasi">
                            Menunggu Verifikasi
                        </option>

                        <option value="Diproses">
                            Diproses
                        </option>

                        <option value="Menunggu Dokumen">
                            Menunggu Dokumen
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                        <option value="Ditolak">
                            Ditolak
                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-6">

                <label class="block font-medium mb-2">

                    Catatan

                </label>

                <textarea
                    name="notes"
                    rows="5"
                    class="w-full rounded-xl border px-4 py-3"
                    placeholder="Catatan untuk pemohon (opsional)"></textarea>

            </div>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">

            <div class="flex gap-3">

                <i class="bi bi-info-circle-fill text-blue-600 text-xl"></i>

                <div>

                    <h3 class="font-semibold text-blue-700">

                        Informasi

                    </h3>

                    <p class="text-blue-700 text-sm mt-1">

                        Kode Tracking akan dibuat otomatis oleh sistem
                        setelah data berhasil disimpan.

                    </p>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a
                href="tracking.php?service_id=<?= $service_id; ?>"
                class="px-6 py-3 rounded-xl border">

                Batal

            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                <i class="bi bi-check-circle"></i>

                Simpan Tracking

            </button>

        </div>

    </form>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>