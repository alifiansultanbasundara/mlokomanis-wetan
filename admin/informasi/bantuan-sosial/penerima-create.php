<?php

require_once '../../../config/app.php';


// =====================================
// Validasi ID Bantuan
// =====================================

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location:index.php");
    exit;
}


$assistance_id = (int) $_GET['id'];




// =====================================
// Ambil Program Bantuan
// =====================================

$query = mysqli_query(

    $conn,

    "
    SELECT *

    FROM social_assistances

    WHERE id='$assistance_id'

    LIMIT 1

    "

);



if (!$query || mysqli_num_rows($query) == 0) {


    $_SESSION['error'] =
        "Program bantuan tidak ditemukan.";


    header("Location:index.php");
    exit;
}



$program = mysqli_fetch_assoc($query);





$title = "Tambah Penerima Bantuan";

$page = "bantuan-sosial";


include APP_PATH . 'includes/admin/layout-top.php';

?>



<div class="p-8">



    <!-- HEADER -->

    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h2 class="text-3xl font-bold text-slate-900">

                Tambah Penerima Bantuan

            </h2>


            <p class="mt-2 text-slate-500">

                Program:
                <?= htmlspecialchars($program['title']); ?>

            </p>


        </div>




        <div>

            <a

                href="penerima.php?id=<?= $assistance_id; ?>"

                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700">

                Kembali

            </a>

        </div>


    </div>








    <form

        action="penerima-store.php"

        method="POST"

        class="space-y-8">



        <input

            type="hidden"

            name="assistance_id"

            value="<?= $assistance_id; ?>">







        <div class="grid gap-8 lg:grid-cols-3">






            <!-- LEFT -->

            <div class="lg:col-span-2">


                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">


                        <h3 class="font-semibold text-slate-900">

                            Data Penerima

                        </h3>


                    </div>





                    <div class="space-y-5 p-6">





                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nama Lengkap

                                <span class="text-red-500">*</span>

                            </label>


                            <input

                                type="text"

                                name="name"

                                required

                                placeholder="Nama penerima bantuan"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-teal-600">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                NIK

                            </label>


                            <input

                                type="text"

                                name="nik"

                                maxlength="16"

                                placeholder="Nomor Induk Kependudukan"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Nomor KK

                            </label>


                            <input

                                type="text"

                                name="kk"

                                maxlength="16"

                                placeholder="Nomor Kartu Keluarga"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Alamat

                            </label>


                            <textarea

                                name="address"

                                rows="4"

                                placeholder="Alamat lengkap penerima"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>


                        </div>






                    </div>


                </div>


            </div>








            <!-- RIGHT -->

            <div class="space-y-8">






                <div class="rounded-2xl border bg-white">


                    <div class="border-b px-6 py-5">


                        <h3 class="font-semibold text-slate-900">

                            Wilayah

                        </h3>


                    </div>





                    <div class="space-y-5 p-6">






                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                RT/RW

                            </label>


                            <input

                                type="text"

                                name="rtrw"

                                placeholder="001/002"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Dusun

                            </label>


                            <input

                                type="text"

                                name="dusun"

                                placeholder="Nama dusun"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3">


                        </div>







                        <div>


                            <label class="mb-2 block font-medium text-slate-700">

                                Keterangan

                            </label>


                            <textarea

                                name="description"

                                rows="3"

                                placeholder="Catatan tambahan"

                                class="w-full rounded-xl border border-slate-300 px-4 py-3"></textarea>


                        </div>






                    </div>


                </div>








                <div class="flex justify-end gap-3">


                    <a

                        href="penerima.php?id=<?= $assistance_id; ?>"

                        class="rounded-xl border px-6 py-3 font-medium">

                        Batal

                    </a>



                    <button

                        type="submit"

                        class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                        Simpan Penerima

                    </button>


                </div>







            </div>





        </div>






    </form>





</div>


<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>