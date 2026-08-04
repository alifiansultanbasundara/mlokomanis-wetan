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
// Ambil Data Tracking
// ======================================================

$query = mysqli_query($conn, "
    
    SELECT 

        letter_trackings.*,

        service_letters.name AS service_name


    FROM letter_trackings


    LEFT JOIN service_letters

    ON service_letters.id = letter_trackings.service_id


    WHERE letter_trackings.id='$id'


    LIMIT 1

");




if (mysqli_num_rows($query) == 0) {

    header("Location:index.php");

    exit;
}



$tracking = mysqli_fetch_assoc($query);





$service_id = $tracking['service_id'];





$title = "Edit Tracking Surat";

$page  = "pelayanan-surat";


include APP_PATH . "includes/admin/layout-top.php";

?>



<main class="p-8 space-y-8">



    <!-- HEADER -->


    <div class="flex items-center justify-between">


        <div>


            <h1 class="text-3xl font-bold text-slate-800">

                Edit Tracking Surat

            </h1>


            <p class="text-slate-500 mt-2">


                <?= htmlspecialchars($tracking['service_name']); ?>


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
        action="tracking-update.php"
        method="POST"
        class="space-y-8">


        <input
            type="hidden"
            name="id"
            value="<?= $tracking['id']; ?>">


        <input
            type="hidden"
            name="service_id"
            value="<?= $service_id; ?>">






        <!-- DATA TRACKING -->


        <div class="bg-white rounded-2xl border shadow-sm p-6">


            <h2 class="text-lg font-semibold mb-6">

                Informasi Tracking

            </h2>





            <div class="grid md:grid-cols-2 gap-6">



                <div>


                    <label class="block font-medium mb-2">

                        Kode Tracking

                    </label>


                    <input
                        type="text"
                        value="<?= htmlspecialchars($tracking['tracking_code']); ?>"
                        readonly
                        class="w-full rounded-xl border px-4 py-3 bg-slate-100">


                    <p class="text-xs text-slate-500 mt-2">

                        Kode tracking dibuat otomatis sistem.

                    </p>


                </div>





                <div>


                    <label class="block font-medium mb-2">

                        Layanan Surat

                    </label>


                    <input
                        type="text"
                        value="<?= htmlspecialchars($tracking['service_name']); ?>"
                        readonly
                        class="w-full rounded-xl border px-4 py-3 bg-slate-100">


                </div>



            </div>


        </div>







        <!-- DATA PEMOHON -->


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
                        value="<?= htmlspecialchars($tracking['applicant_name']); ?>"
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
                        value="<?= htmlspecialchars($tracking['nik']); ?>"
                        class="w-full rounded-xl border px-4 py-3">


                </div>








                <div>


                    <label class="block font-medium mb-2">

                        Nomor HP

                    </label>


                    <input
                        type="text"
                        name="phone"
                        value="<?= htmlspecialchars($tracking['phone']); ?>"
                        class="w-full rounded-xl border px-4 py-3">


                </div>








                <div>


                    <label class="block font-medium mb-2">

                        Email

                    </label>


                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($tracking['email']); ?>"
                        class="w-full rounded-xl border px-4 py-3">


                </div>




            </div>


        </div>






        <!-- STATUS PENGAJUAN -->


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





                        <option value="Menunggu Verifikasi"
                            <?= $tracking['status'] == "Menunggu Verifikasi" ? 'selected' : ''; ?>>

                            Menunggu Verifikasi

                        </option>






                        <option value="Diproses"
                            <?= $tracking['status'] == "Diproses" ? 'selected' : ''; ?>>

                            Diproses

                        </option>







                        <option value="Menunggu Dokumen"
                            <?= $tracking['status'] == "Menunggu Dokumen" ? 'selected' : ''; ?>>

                            Menunggu Dokumen

                        </option>








                        <option value="Selesai"
                            <?= $tracking['status'] == "Selesai" ? 'selected' : ''; ?>>

                            Selesai

                        </option>








                        <option value="Ditolak"
                            <?= $tracking['status'] == "Ditolak" ? 'selected' : ''; ?>>

                            Ditolak

                        </option>




                    </select>



                </div>









                <div>


                    <label class="block font-medium mb-2">

                        Tanggal Pengajuan

                    </label>


                    <input
                        type="text"
                        readonly
                        value="<?= date(
                                    'd F Y H:i',
                                    strtotime($tracking['submitted_at'])
                                ); ?>"
                        class="w-full rounded-xl border px-4 py-3 bg-slate-100">


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
                    placeholder="Catatan untuk pemohon"><?= htmlspecialchars($tracking['notes']); ?></textarea>


            </div>





        </div>









        <!-- INFORMASI DATA -->


        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">


            <div class="flex gap-3">


                <i class="bi bi-info-circle-fill text-blue-600 text-xl"></i>


                <div>



                    <h3 class="font-semibold text-blue-700">

                        Informasi Tracking

                    </h3>



                    <div class="text-sm text-blue-700 mt-2 space-y-1">


                        <p>

                            Kode Tracking:

                            <strong>

                                <?= htmlspecialchars($tracking['tracking_code']); ?>

                            </strong>

                        </p>



                        <p>

                            Status saat ini:

                            <strong>

                                <?= htmlspecialchars($tracking['status']); ?>

                            </strong>

                        </p>




                        <?php if ($tracking['completed_at']): ?>


                            <p>

                                Selesai pada:

                                <strong>

                                    <?= date(
                                        'd F Y H:i',
                                        strtotime($tracking['completed_at'])
                                    ); ?>

                                </strong>

                            </p>


                        <?php endif; ?>



                    </div>



                </div>


            </div>


        </div>









        <!-- ACTION -->


        <div class="flex justify-end gap-3">



            <a
                href="tracking.php?service_id=<?= $service_id; ?>"
                class="px-6 py-3 rounded-xl border hover:bg-slate-50">


                Batal


            </a>






            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">


                <i class="bi bi-save"></i>


                Simpan Perubahan


            </button>




        </div>





    </form>



</main>



<?php include APP_PATH . "includes/admin/layout-bottom.php"; ?>