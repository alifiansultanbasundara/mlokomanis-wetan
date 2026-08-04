<?php

require_once '../../config/app.php';

$title = "Tambah Pelayanan Surat";
$page  = "pelayanan-surat";

include APP_PATH . "includes/admin/layout-top.php";

?>

<main class="p-8">

    <!-- Header -->

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Tambah Pelayanan Surat

            </h1>

            <p class="text-slate-500 mt-2">

                Tambahkan jenis pelayanan surat yang tersedia.

            </p>

        </div>

        <a
            href="index.php"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <form
        action="store.php"
        method="POST"
        class="space-y-8">

        <!-- Informasi -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">

                Informasi Surat

            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="font-medium">

                        Nama Surat

                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label class="font-medium">

                        Slug

                    </label>

                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        required
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label>

                        Bootstrap Icon

                    </label>

                    <input
                        type="text"
                        name="icon"
                        value="bi-file-earmark-text"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label>

                        Warna

                    </label>

                    <select
                        name="color"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                        <option>emerald</option>
                        <option>blue</option>
                        <option>red</option>
                        <option>amber</option>
                        <option>purple</option>
                        <option>cyan</option>

                    </select>

                </div>

                <div>

                    <label>

                        Lama Proses

                    </label>

                    <input
                        type="text"
                        name="processing_time"
                        placeholder="1 Hari Kerja"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label>

                        Biaya

                    </label>

                    <input
                        type="text"
                        name="fee"
                        value="Gratis"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label>

                        Contact Person

                    </label>

                    <input
                        type="text"
                        name="contact_person"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

                <div>

                    <label>

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="w-full mt-2 rounded-xl border px-4 py-3">

                </div>

            </div>

            <div class="mt-6">

                <label>

                    Deskripsi

                </label>

                <textarea
                    rows="5"
                    name="description"
                    class="w-full mt-2 rounded-xl border px-4 py-3"></textarea>

            </div>

        </div>

        <!-- Persyaratan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">

                Persyaratan & Prosedur

            </h2>

            <label>Persyaratan</label>

            <textarea
                rows="6"
                name="requirements"
                class="w-full mt-2 rounded-xl border px-4 py-3"></textarea>

            <label class="block mt-6">

                Prosedur

            </label>

            <textarea
                rows="6"
                name="service_procedure"
                class="w-full mt-2 rounded-xl border px-4 py-3"></textarea>

        </div>

        <!-- Metode -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <h2 class="text-lg font-semibold mb-6">

                Metode Pengajuan

            </h2>

            <div class="space-y-4">

                <label class="flex items-center gap-3">

                    <input
                        type="checkbox"
                        id="has_google_form"
                        name="has_google_form"
                        value="Yes">

                    Google Form

                </label>

                <div id="googleFormBox" class="hidden">

                    <input
                        type="url"
                        name="google_form_url"
                        placeholder="https://forms.gle/..."
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <label class="flex items-center gap-3">

                    <input
                        type="checkbox"
                        id="has_template"
                        name="has_template"
                        value="Yes">

                    Template Dokumen

                </label>

                <div id="templateBox" class="hidden">

                    <input
                        type="url"
                        name="template_url"
                        placeholder="https://drive.google.com/..."
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <label class="flex items-center gap-3">

                    <input
                        type="checkbox"
                        id="has_tracking"
                        name="has_tracking"
                        value="Yes"
                        checked>

                    Tracking Online

                </label>

                <div id="trackingBox">

                    <input
                        type="url"
                        name="spreadsheet_url"
                        placeholder="Spreadsheet Google"
                        class="w-full rounded-xl border px-4 py-3 mb-4">

                    <input
                        type="url"
                        name="tracking_url"
                        placeholder="Tracking URL"
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <label class="block mt-6">

                    Panduan (Opsional)

                </label>

                <input
                    type="url"
                    name="guide_url"
                    placeholder="https://..."
                    class="w-full mt-2 rounded-xl border px-4 py-3">

            </div>

        </div>

        <!-- Pengaturan -->

        <div class="bg-white rounded-2xl border shadow-sm p-6">

            <label>Status</label>

            <select
                name="status"
                class="w-full mt-2 rounded-xl border px-4 py-3">

                <option value="Published">Published</option>
                <option value="Draft">Draft</option>

            </select>

        </div>

        <div class="flex justify-end gap-3">

            <a
                href="index.php"
                class="px-6 py-3 rounded-xl border">

                Batal

            </a>

            <button
                class="px-6 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">

                <i class="bi bi-check-circle"></i>

                Simpan

            </button>

        </div>

    </form>

</main>

<script>
    const toggle = (checkId, boxId) => {

        const check = document.getElementById(checkId);
        const box = document.getElementById(boxId);

        function update() {
            box.classList.toggle('hidden', !check.checked);
        }

        check.addEventListener('change', update);
        update();
    };

    toggle('has_google_form', 'googleFormBox');
    toggle('has_template', 'templateBox');
    toggle('has_tracking', 'trackingBox');

    const name = document.getElementById('name');
    const slug = document.getElementById('slug');

    name.addEventListener('keyup', () => {

        slug.value = name.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

    });
</script>

<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>