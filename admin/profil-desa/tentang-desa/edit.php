<?php

require_once '../../../config/app.php';


// ===============================
// Ambil Data Profil Desa
// ===============================

$query = mysqli_query($conn, "

    SELECT *
    FROM village_profiles
    LIMIT 1

");


$data = mysqli_fetch_assoc($query);


// Jika belum ada data

if (!$data) {

    $data = [

        'village_name' => '',
        'village_head' => '',
        'office_photo' => '',
        'description' => '',
        'history' => '',
        'vision' => '',
        'mission' => '',
        'office_address' => '',
        'latitude' => '',
        'longitude' => '',
        'google_maps' => '',

        'total_areas' => 0,
        'total_hamlets' => 0,
        'total_rw' => 0,
        'total_rt' => 0,
        'total_population' => 0,

        'north_boundary' => '',
        'east_boundary' => '',
        'south_boundary' => '',
        'west_boundary' => ''

    ];
}



$title = "Edit Tentang Desa";
$page  = "tentang-desa";


include APP_PATH . 'includes/admin/layout-top.php';

?>


<main class="p-8 space-y-8">


    <!-- HEADER -->

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-900">
                Edit Tentang Desa
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola informasi profil dan keadaan wilayah desa.
            </p>

        </div>


        <div class="flex gap-3">


            <a href="index.php"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 hover:bg-slate-50">

                Kembali

            </a>


            <button form="formProfile"
                class="rounded-xl bg-teal-600 px-6 py-3 font-medium text-white hover:bg-teal-700">

                Simpan

            </button>


        </div>


    </div>
    <form
        id="formProfile"
        action="update.php"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8">


        <!-- ==========================
        INFORMASI DESA
    =========================== -->


        <div class="rounded-2xl border bg-white">


            <div class="border-b px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Desa
                </h2>

            </div>



            <div class="space-y-6 p-6">


                <div class="grid gap-6 lg:grid-cols-2">


                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Nama Desa
                        </label>

                        <input
                            type="text"
                            name="village_name"
                            value="<?= htmlspecialchars($data['village_name']); ?>"
                            placeholder="Contoh: Desa Mlokomanis Wetan"
                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                    </div>



                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Nama Kepala Desa
                        </label>

                        <input
                            type="text"
                            name="village_head"
                            value="<?= htmlspecialchars($data['village_head']); ?>"
                            placeholder="Masukkan nama kepala desa"
                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                    </div>


                </div>




                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Deskripsi Desa
                    </label>


                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Tuliskan gambaran umum desa..."
                        class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($data['description']); ?></textarea>


                </div>






                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Foto Balai Desa
                    </label>


                    <?php if (!empty($data['office_photo'])): ?>

                        <img
                            src="<?= APP_URL ?>uploads/village/<?= $data['office_photo']; ?>"
                            class="mb-4 h-52 w-full rounded-xl object-cover">


                    <?php endif; ?>



                    <input
                        type="file"
                        name="office_photo"
                        accept="image/*"
                        class="w-full rounded-xl border px-4 py-3">


                    <p class="mt-2 text-sm text-slate-500">
                        Format JPG/PNG maksimal 2MB.
                    </p>


                </div>



            </div>


        </div>








        <!-- ==========================
        PROFIL DESA
    =========================== -->


        <div class="rounded-2xl border bg-white">


            <div class="border-b px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Profil Desa
                </h2>

            </div>



            <div class="space-y-6 p-6">



                <div>

                    <label class="mb-2 block font-medium text-slate-700">
                        Sejarah Desa
                    </label>


                    <textarea
                        name="history"
                        rows="6"
                        placeholder="Tuliskan sejarah berdirinya desa..."
                        class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($data['history']); ?></textarea>


                </div>





                <div class="grid gap-6 lg:grid-cols-2">


                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Visi Desa
                        </label>


                        <textarea
                            name="vision"
                            rows="6"
                            placeholder="Tuliskan visi desa..."
                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($data['vision']); ?></textarea>


                    </div>





                    <div>

                        <label class="mb-2 block font-medium text-slate-700">
                            Misi Desa
                        </label>


                        <textarea
                            name="mission"
                            rows="6"
                            placeholder="Tuliskan misi desa..."
                            class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100"><?= htmlspecialchars($data['mission']); ?></textarea>


                    </div>



                </div>



            </div>


        </div>









        <!-- ==========================
        DATA DESA
    =========================== -->


        <div class="rounded-2xl border bg-white">


            <div class="border-b px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    Data Desa
                </h2>

            </div>



            <div class="space-y-8 p-6">





                <!-- Statistik -->


                <div>


                    <h3 class="mb-5 font-medium text-slate-800">
                        Statistik Desa
                    </h3>



                    <div class="grid gap-6 md:grid-cols-5">


                        <?php

                        $stats = [

                            [
                                'name' => 'total_areas',
                                'label' => 'Wilayah'
                            ],

                            [
                                'name' => 'total_hamlets',
                                'label' => 'Dusun'
                            ],

                            [
                                'name' => 'total_rw',
                                'label' => 'RW'
                            ],

                            [
                                'name' => 'total_rt',
                                'label' => 'RT'
                            ],

                            [
                                'name' => 'total_population',
                                'label' => 'Penduduk'
                            ]

                        ];


                        foreach ($stats as $item):

                        ?>


                            <div>

                                <label class="mb-2 block text-sm font-medium text-slate-700">

                                    Jumlah <?= $item['label']; ?>

                                </label>


                                <input
                                    type="number"
                                    name="<?= $item['name']; ?>"
                                    value="<?= htmlspecialchars($data[$item['name']] ?? 0); ?>"
                                    placeholder="0"
                                    class="w-full rounded-xl border px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-100">


                            </div>


                        <?php endforeach; ?>


                    </div>


                </div>







                <!-- Lokasi -->


                <div>


                    <h3 class="mb-5 font-medium text-slate-800">
                        Lokasi Desa
                    </h3>



                    <div class="space-y-5">


                        <div>

                            <label class="mb-2 block text-sm font-medium">
                                Alamat Kantor Desa
                            </label>


                            <textarea
                                name="office_address"
                                rows="3"
                                placeholder="Masukkan alamat lengkap kantor desa"
                                class="w-full rounded-xl border px-4 py-3"><?= htmlspecialchars($data['office_address']); ?></textarea>


                        </div>



                        <div class="grid gap-5 md:grid-cols-2">


                            <div>

                                <label class="mb-2 block text-sm font-medium">
                                    Latitude
                                </label>


                                <input
                                    name="latitude"
                                    value="<?= htmlspecialchars($data['latitude']); ?>"
                                    placeholder="-7.123456"
                                    class="w-full rounded-xl border px-4 py-3">


                            </div>



                            <div>

                                <label class="mb-2 block text-sm font-medium">
                                    Longitude
                                </label>


                                <input
                                    name="longitude"
                                    value="<?= htmlspecialchars($data['longitude']); ?>"
                                    placeholder="110.123456"
                                    class="w-full rounded-xl border px-4 py-3">


                            </div>


                        </div>





                        <div>

                            <label class="mb-2 block text-sm font-medium">
                                Embed Google Maps
                            </label>


                            <textarea
                                name="google_maps"
                                rows="3"
                                placeholder="Tempel kode embed Google Maps"
                                class="w-full rounded-xl border px-4 py-3"><?= htmlspecialchars($data['google_maps']); ?></textarea>


                        </div>



                    </div>



                </div>








                <!-- Batas Wilayah -->


                <div>


                    <h3 class="mb-5 font-medium text-slate-800">
                        Batas Wilayah Desa
                    </h3>



                    <div class="grid gap-6 md:grid-cols-2">



                        <input
                            name="north_boundary"
                            value="<?= htmlspecialchars($data['north_boundary']); ?>"
                            placeholder="Batas sebelah Utara"
                            class="w-full rounded-xl border px-4 py-3">



                        <input
                            name="east_boundary"
                            value="<?= htmlspecialchars($data['east_boundary']); ?>"
                            placeholder="Batas sebelah Timur"
                            class="w-full rounded-xl border px-4 py-3">



                        <input
                            name="south_boundary"
                            value="<?= htmlspecialchars($data['south_boundary']); ?>"
                            placeholder="Batas sebelah Selatan"
                            class="w-full rounded-xl border px-4 py-3">



                        <input
                            name="west_boundary"
                            value="<?= htmlspecialchars($data['west_boundary']); ?>"
                            placeholder="Batas sebelah Barat"
                            class="w-full rounded-xl border px-4 py-3">


                    </div>



                </div>




            </div>



        </div>



    </form>

</main>


<?php include APP_PATH . 'includes/admin/layout-bottom.php'; ?>