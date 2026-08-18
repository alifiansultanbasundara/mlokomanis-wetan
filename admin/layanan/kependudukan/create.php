<?php

require_once '../../../config/app.php';

$page = 'kependudukan';

include APP_PATH . "includes/admin/layout-top.php"

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Tambah Penduduk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen">


        <!-- HEADER -->

        <header class="border-b border-slate-200 bg-white">

            <div class="mx-auto px-6 py-5">

                <div class="flex items-center gap-4">

                    <a
                        href="index.php"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50">

                        <i class="bi bi-arrow-left"></i>

                    </a>

                    <div>

                        <h1 class="text-2xl font-bold text-slate-900">
                            Tambah Penduduk
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Tambahkan data penduduk baru.
                        </p>

                    </div>

                </div>

            </div>

        </header>


        <!-- CONTENT -->

        <main class="mx-auto px-6 py-8">

            <form
                action="store.php"
                method="POST"
                class="space-y-6">


                <!-- DATA UTAMA -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-6 py-5">

                        <h2 class="font-semibold text-slate-900">
                            Data Penduduk
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi dasar penduduk.
                        </p>

                    </div>


                    <div class="grid gap-6 p-6 md:grid-cols-2">

                        <!-- NIK -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                NIK

                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                name="nik"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                required
                                placeholder="Masukkan 16 digit NIK"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <p class="mt-1 text-xs text-slate-500">
                                NIK harus terdiri dari 16 digit.
                            </p>

                        </div>


                        <!-- NO. KK -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                No. KK

                            </label>

                            <input
                                type="text"
                                name="no_kk"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                placeholder="Masukkan 16 digit No. KK"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                            <p class="mt-1 text-xs text-slate-500">
                                Nomor Kartu Keluarga terdiri dari 16 digit.
                            </p>

                        </div>


                        <!-- Nama -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Nama Lengkap

                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                required
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Kepala Keluarga -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Kepala Keluarga

                            </label>

                            <input
                                type="text"
                                name="head_of_family"
                                maxlength="150"
                                placeholder="Masukkan nama kepala keluarga"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Tempat Lahir -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Tempat Lahir

                            </label>

                            <input
                                type="text"
                                name="birth_place"
                                placeholder="Contoh: Surakarta"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Tanggal Lahir -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Tanggal Lahir

                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Jenis Kelamin -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Jenis Kelamin

                            </label>

                            <select
                                name="gender"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option value="Laki-laki">
                                    Laki-laki
                                </option>

                                <option value="Perempuan">
                                    Perempuan
                                </option>

                            </select>

                        </div>


                        <!-- Agama -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Agama

                            </label>

                            <select
                                name="religion"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option value="Islam">
                                    Islam
                                </option>

                                <option value="Kristen">
                                    Kristen
                                </option>

                                <option value="Katolik">
                                    Katolik
                                </option>

                                <option value="Hindu">
                                    Hindu
                                </option>

                                <option value="Buddha">
                                    Buddha
                                </option>

                                <option value="Konghucu">
                                    Konghucu
                                </option>

                            </select>

                        </div>


                        <!-- Pendidikan -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Pendidikan

                            </label>

                            <select
                                name="education"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option value="">
                                    Pilih Pendidikan
                                </option>

                                <option value="Tidak/Belum Sekolah">
                                    Tidak/Belum Sekolah
                                </option>

                                <option value="SD">
                                    SD
                                </option>

                                <option value="SMP">
                                    SMP
                                </option>

                                <option value="SMA">
                                    SMA
                                </option>

                                <option value="D1">
                                    D1
                                </option>

                                <option value="D2">
                                    D2
                                </option>

                                <option value="D3">
                                    D3
                                </option>

                                <option value="D4">
                                    D4
                                </option>

                                <option value="S1">
                                    S1
                                </option>

                                <option value="S2">
                                    S2
                                </option>

                                <option value="S3">
                                    S3
                                </option>

                            </select>

                        </div>


                        <!-- Pekerjaan -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">

                                Pekerjaan

                            </label>

                            <input
                                type="text"
                                name="occupation"
                                placeholder="Contoh: Petani"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>

                    </div>

                </div>


                <!-- ALAMAT -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-6 py-5">

                        <h2 class="font-semibold text-slate-900">
                            Alamat
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi tempat tinggal penduduk.
                        </p>

                    </div>


                    <div class="grid gap-6 p-6 md:grid-cols-2">


                        <!-- Alamat -->

                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>

                        </div>


                        <!-- RT -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                RT
                            </label>

                            <input
                                type="text"
                                name="rt"
                                maxlength="5"
                                placeholder="Contoh: 001"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- RW -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                RW
                            </label>

                            <input
                                type="text"
                                name="rw"
                                maxlength="5"
                                placeholder="Contoh: 002"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Dusun -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Dusun
                            </label>

                            <input
                                type="text"
                                name="hamlet"
                                placeholder="Contoh: Dusun Krajan"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>


                        <!-- Status Perkawinan -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Status Perkawinan
                            </label>

                            <select
                                name="marital_status"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                                <option value="Belum Kawin">
                                    Belum Kawin
                                </option>

                                <option value="Kawin">
                                    Kawin
                                </option>

                                <option value="Cerai">
                                    Cerai
                                </option>

                                <option value="Cerai Mati">
                                    Cerai Mati
                                </option>

                            </select>

                        </div>


                        <!-- Kewarganegaraan -->

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Kewarganegaraan
                            </label>

                            <input
                                type="text"
                                name="citizenship"
                                value="WNI"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-100">

                        </div>

                    </div>

                </div>


                <!-- BUTTON -->

                <div class="flex items-center justify-end gap-3">

                    <a
                        href="index.php"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-50">

                        Batal

                    </a>


                    <button
                        type="submit"
                        name="save"
                        class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 font-semibold text-white transition hover:bg-teal-700">

                        <i class="bi bi-check-lg"></i>

                        Simpan Penduduk

                    </button>

                </div>


            </form>

        </main>

    </div>

</body>

</html>

<?php include APP_PATH . "includes/admin/layout-bottom.php" ?>