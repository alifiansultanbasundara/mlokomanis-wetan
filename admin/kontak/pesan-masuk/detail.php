<?php

require_once '../../../config/app.php';

// ======================================================
// Validasi
// ======================================================

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}

$id = (int) $_GET['id'];

// ======================================================
// Auto Read
// ======================================================

mysqli_query($conn, "
    UPDATE contact_messages
    SET status='Sudah Dibaca'
    WHERE id='$id'
    AND status='Belum Dibaca'
");

// ======================================================
// Ambil Data
// ======================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM contact_messages
    WHERE id='$id'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Pesan tidak ditemukan.";

    header("Location:index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

$title = "Detail Pesan";
$page  = "pesan";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Detail Pesan

            </h1>

            <p class="text-slate-500 mt-2">

                Informasi lengkap pesan dari masyarakat.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="index.php"
                class="px-5 py-3 rounded-xl border hover:bg-slate-50">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <?php if ($data['status'] != 'Ditindaklanjuti') : ?>

                <a
                    href="follow-up.php?id=<?= $data['id']; ?>"
                    onclick="return confirm('Tandai sebagai ditindaklanjuti?')"
                    class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">

                    <i class="bi bi-check-circle"></i>

                    Tindak Lanjuti

                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- Informasi Pengirim -->

    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-6">

        <h2 class="text-lg font-semibold mb-6">

            Informasi Pengirim

        </h2>

        <div class="grid md:grid-cols-2 gap-6">

            <div>

                <label class="text-sm text-slate-500">

                    Nama

                </label>

                <p class="font-medium mt-1">

                    <?= htmlspecialchars($data['name']); ?>

                </p>

            </div>

            <div>

                <label class="text-sm text-slate-500">

                    Email

                </label>

                <p class="font-medium mt-1">

                    <?= !empty($data['email']) ? htmlspecialchars($data['email']) : '-'; ?>

                </p>

            </div>

            <div>

                <label class="text-sm text-slate-500">

                    Nomor HP

                </label>

                <p class="font-medium mt-1">

                    <?= !empty($data['phone']) ? htmlspecialchars($data['phone']) : '-'; ?>

                </p>

            </div>

            <div>

                <label class="text-sm text-slate-500">

                    Tanggal

                </label>

                <p class="font-medium mt-1">

                    <?= date('d F Y H:i', strtotime($data['created_at'])); ?>

                </p>

            </div>

        </div>

    </div>

    <!-- Detail Pesan -->

    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-6">

        <h2 class="text-lg font-semibold mb-6">

            Isi Pesan

        </h2>

        <div class="mb-6">

            <label class="text-sm text-slate-500">

                Subjek

            </label>

            <p class="font-semibold text-lg mt-1">

                <?= htmlspecialchars($data['subject']); ?>

            </p>

        </div>

        <div>

            <label class="text-sm text-slate-500">

                Pesan

            </label>

            <div class="mt-3 p-5 rounded-xl bg-slate-50 border leading-7">

                <?= nl2br(htmlspecialchars($data['message'])); ?>

            </div>

        </div>

    </div>

    <!-- Status -->

    <div class="bg-white rounded-2xl border shadow-sm p-6">

        <h2 class="text-lg font-semibold mb-6">

            Status

        </h2>

        <?php

        $badge = "bg-slate-100 text-slate-700";

        if ($data['status'] == 'Belum Dibaca') {
            $badge = "bg-red-100 text-red-700";
        }

        if ($data['status'] == 'Sudah Dibaca') {
            $badge = "bg-blue-100 text-blue-700";
        }

        if ($data['status'] == 'Ditindaklanjuti') {
            $badge = "bg-emerald-100 text-emerald-700";
        }

        ?>

        <span class="px-4 py-2 rounded-full text-sm font-semibold <?= $badge; ?>">

            <?= $data['status']; ?>

        </span>

    </div>

</main>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>