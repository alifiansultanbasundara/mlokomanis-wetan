<?php

require_once '../../config/app.php';


// ======================================================
// Validasi ID
// ======================================================

if (!isset($_GET['id'])) {

    header("Location:index.php");
    exit;
}


$id = (int) $_GET['id'];




// ======================================================
// Ambil Data Layanan
// ======================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM service_letters
    WHERE id='$id'
    LIMIT 1
");



if (mysqli_num_rows($query) == 0) {

    header("Location:index.php");
    exit;
}



$service = mysqli_fetch_assoc($query);






// ======================================================
// Page
// ======================================================

$title = "Edit Pelayanan Surat";

$page = "layanan";


include APP_PATH . "includes/admin/layout-top.php";

?>



<main class="p-8 space-y-6">



    <!-- HEADER -->


    <div class="flex justify-between items-center">


        <div>


            <h1 class="text-3xl font-bold text-slate-800">

                Edit Pelayanan Surat

            </h1>


            <p class="text-slate-500 mt-2">

                <?= htmlspecialchars($service['name']); ?>

            </p>


        </div>




        <a
            href="index.php"
            class="px-5 py-3 rounded-xl border hover:bg-slate-50">


            <i class="bi bi-arrow-left"></i>

            Kembali


        </a>


    </div>







    <!-- FORM -->


    <form
        action="update.php"
        method="POST"
        class="grid lg:grid-cols-3 gap-8">


        <input
            type="hidden"
            name="id"
            value="<?= $service['id']; ?>">




        <!-- LEFT -->


        <div class="lg:col-span-2 space-y-6">






            <!-- INFORMASI UTAMA -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Informasi Layanan

                </h2>







                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Nama Layanan *

                    </label>


                    <input
                        type="text"
                        name="name"
                        required
                        value="<?= htmlspecialchars($service['name']); ?>"
                        class="w-full mt-2 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-teal-500">


                </div>







                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Slug

                    </label>


                    <input
                        type="text"
                        name="slug"
                        value="<?= htmlspecialchars($service['slug']); ?>"
                        class="w-full mt-2 px-4 py-3 rounded-xl border bg-slate-50">


                    <p class="text-xs text-slate-500 mt-2">

                        Digunakan untuk URL layanan.

                    </p>


                </div>







                <div class="grid md:grid-cols-2 gap-5">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Icon Bootstrap

                        </label>


                        <input
                            type="text"
                            name="icon"
                            value="<?= htmlspecialchars($service['icon']); ?>"
                            placeholder="bi-file-earmark-text"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                        <p class="text-xs text-slate-500 mt-2">

                            Contoh: bi bi-file-earmark-text

                        </p>


                    </div>








                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Warna Icon

                        </label>



                        <select
                            name="color"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">



                            <?php

                            $colors = [

                                "emerald",

                                "blue",

                                "red",

                                "yellow",

                                "purple",

                                "teal",

                                "orange",

                                "indigo"

                            ];


                            foreach ($colors as $color):

                            ?>


                                <option
                                    value="<?= $color; ?>"
                                    <?= $service['color'] == $color ? 'selected' : ''; ?>>


                                    <?= ucfirst($color); ?>


                                </option>


                            <?php endforeach; ?>


                        </select>



                    </div>


                </div>








                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Deskripsi

                    </label>


                    <textarea
                        name="description"
                        rows="5"
                        class="w-full mt-2 px-4 py-3 rounded-xl border"><?= htmlspecialchars($service['description']); ?></textarea>


                </div>




            </div>








            <!-- DETAIL PELAYANAN -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Detail Pelayanan

                </h2>






                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Persyaratan

                    </label>


                    <textarea
                        name="requirements"
                        rows="6"
                        class="w-full mt-2 px-4 py-3 rounded-xl border"><?= htmlspecialchars($service['requirements']); ?></textarea>


                    <p class="text-xs text-slate-500 mt-2">

                        Pisahkan setiap persyaratan dengan baris baru.

                    </p>


                </div>






                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Prosedur Pelayanan

                    </label>


                    <textarea
                        name="service_procedure"
                        rows="6"
                        class="w-full mt-2 px-4 py-3 rounded-xl border"><?= htmlspecialchars($service['service_procedure']); ?></textarea>


                </div>




            </div>

            <!-- INFORMASI OPERASIONAL -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Informasi Operasional

                </h2>




                <div class="grid md:grid-cols-2 gap-5">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Waktu Proses

                        </label>


                        <input
                            type="text"
                            name="processing_time"
                            value="<?= htmlspecialchars($service['processing_time']); ?>"
                            placeholder="Contoh: 1-3 Hari Kerja"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Biaya

                        </label>


                        <input
                            type="text"
                            name="fee"
                            value="<?= htmlspecialchars($service['fee']); ?>"
                            placeholder="Contoh: Gratis"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>



                </div>







                <div class="grid md:grid-cols-2 gap-5">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Petugas / Kontak

                        </label>


                        <input
                            type="text"
                            name="contact_person"
                            value="<?= htmlspecialchars($service['contact_person']); ?>"
                            placeholder="Nama petugas"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>





                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Nomor Telepon

                        </label>


                        <input
                            type="text"
                            name="phone"
                            value="<?= htmlspecialchars($service['phone']); ?>"
                            placeholder="08xxxxxxxxxx"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>



                </div>



            </div>








            <!-- LINK ONLINE -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Integrasi Layanan Online

                </h2>






                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Google Form URL

                    </label>


                    <input
                        type="url"
                        name="google_form_url"
                        value="<?= htmlspecialchars($service['google_form_url']); ?>"
                        placeholder="https://forms.google.com/..."
                        class="w-full mt-2 px-4 py-3 rounded-xl border">


                </div>







                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Template Surat URL

                    </label>


                    <input
                        type="url"
                        name="template_url"
                        value="<?= htmlspecialchars($service['template_url']); ?>"
                        placeholder="Link template surat"
                        class="w-full mt-2 px-4 py-3 rounded-xl border">


                </div>







                <div>


                    <label class="text-sm font-medium text-slate-700">

                        Spreadsheet URL

                    </label>


                    <input
                        type="url"
                        name="spreadsheet_url"
                        value="<?= htmlspecialchars($service['spreadsheet_url']); ?>"
                        placeholder="Link spreadsheet"
                        class="w-full mt-2 px-4 py-3 rounded-xl border">


                </div>








                <div class="grid md:grid-cols-2 gap-5">



                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Tracking URL

                        </label>


                        <input
                            type="url"
                            name="tracking_url"
                            value="<?= htmlspecialchars($service['tracking_url']); ?>"
                            placeholder="Link tracking surat"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>






                    <div>


                        <label class="text-sm font-medium text-slate-700">

                            Panduan URL

                        </label>


                        <input
                            type="url"
                            name="guide_url"
                            value="<?= htmlspecialchars($service['guide_url']); ?>"
                            placeholder="Link panduan"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                    </div>



                </div>



            </div>









            <!-- PENGATURAN -->


            <div class="bg-white border rounded-2xl p-6 space-y-5">


                <h2 class="text-lg font-semibold text-slate-800">

                    Pengaturan

                </h2>






                <div class="grid md:grid-cols-4 gap-5">






                    <div>


                        <label class="text-sm font-medium">

                            Google Form

                        </label>


                        <select
                            name="has_google_form"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <option value="Yes"
                                <?= $service['has_google_form'] == "Yes" ? 'selected' : ''; ?>>

                                Yes

                            </option>


                            <option value="No"
                                <?= $service['has_google_form'] == "No" ? 'selected' : ''; ?>>

                                No

                            </option>


                        </select>


                    </div>







                    <div>


                        <label class="text-sm font-medium">

                            Template

                        </label>


                        <select
                            name="has_template"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <option value="Yes"
                                <?= $service['has_template'] == "Yes" ? 'selected' : ''; ?>>

                                Yes

                            </option>


                            <option value="No"
                                <?= $service['has_template'] == "No" ? 'selected' : ''; ?>>

                                No

                            </option>


                        </select>


                    </div>







                    <div>


                        <label class="text-sm font-medium">

                            Tracking

                        </label>


                        <select
                            name="has_tracking"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <option value="Yes"
                                <?= $service['has_tracking'] == "Yes" ? 'selected' : ''; ?>>

                                Yes

                            </option>


                            <option value="No"
                                <?= $service['has_tracking'] == "No" ? 'selected' : ''; ?>>

                                No

                            </option>


                        </select>


                    </div>







                    <div>


                        <label class="text-sm font-medium">

                            Status

                        </label>


                        <select
                            name="status"
                            class="w-full mt-2 px-4 py-3 rounded-xl border">


                            <option value="Published"
                                <?= $service['status'] == "Published" ? 'selected' : ''; ?>>

                                Published

                            </option>


                            <option value="Draft"
                                <?= $service['status'] == "Draft" ? 'selected' : ''; ?>>

                                Draft

                            </option>


                        </select>


                    </div>




                </div>




                <div>


                    <label class="text-sm font-medium">

                        Urutan Tampil

                    </label>


                    <input
                        type="number"
                        name="sort_order"
                        value="<?= $service['sort_order']; ?>"
                        class="w-full mt-2 px-4 py-3 rounded-xl border">


                </div>



            </div>







        </div>
        <!-- END LEFT -->








        <!-- RIGHT SIDEBAR -->


        <div class="space-y-6">



            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">

                    Aksi

                </h2>



                <button
                    type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">


                    <i class="bi bi-save"></i>


                    Simpan Perubahan


                </button>



                <a
                    href="index.php"
                    class="block text-center mt-3 px-5 py-3 rounded-xl border">


                    Batal


                </a>


            </div>







            <div class="bg-white border rounded-2xl p-6">


                <h2 class="font-semibold text-lg mb-5">

                    Informasi


                </h2>



                <div class="space-y-3 text-sm">



                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            ID

                        </span>


                        <span>

                            #<?= $service['id']; ?>

                        </span>


                    </div>





                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Dibuat

                        </span>


                        <span>

                            <?= date(
                                'd M Y',
                                strtotime($service['created_at'])
                            ); ?>

                        </span>


                    </div>





                    <div class="flex justify-between">


                        <span class="text-slate-500">

                            Update

                        </span>


                        <span>

                            <?= date(
                                'd M Y',
                                strtotime($service['updated_at'])
                            ); ?>

                        </span>


                    </div>



                </div>


            </div>






        </div>



    </form>





</main>


<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>