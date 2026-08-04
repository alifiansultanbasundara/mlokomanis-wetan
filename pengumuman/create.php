<?php

include "../auth/auth.php";
include "../config/database.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Pengumuman</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="card shadow">

                    <div class="card-header bg-success text-white">

                        <h4 class="mb-0">

                            Tambah Pengumuman

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="store.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <!-- Judul -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Judul Pengumuman

                                </label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    required>

                            </div>

                            <!-- Isi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Isi Pengumuman

                                </label>

                                <textarea
                                    name="isi"
                                    rows="6"
                                    class="form-control"
                                    required></textarea>

                            </div>

                            <div class="row">

                                <!-- Kategori -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Kategori

                                    </label>

                                    <select
                                        name="kategori"
                                        class="form-select"
                                        required>

                                        <option value="Pelayanan">Pelayanan</option>

                                        <option value="Bansos">Bansos</option>

                                        <option value="Kesehatan">Kesehatan</option>

                                        <option value="Kegiatan">Kegiatan</option>

                                        <option value="Keuangan">Keuangan</option>

                                        <option value="Lainnya">Lainnya</option>

                                    </select>

                                </div>

                                <!-- Prioritas -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Prioritas

                                    </label>

                                    <select
                                        name="prioritas"
                                        class="form-select">

                                        <option value="Biasa">

                                            Biasa

                                        </option>

                                        <option value="Penting">

                                            Penting

                                        </option>

                                        <option value="Sangat Penting">

                                            Sangat Penting

                                        </option>

                                    </select>

                                </div>

                            </div>

                            <div class="row">

                                <!-- Tanggal Mulai -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Tanggal Mulai

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_mulai"
                                        class="form-control">

                                </div>

                                <!-- Tanggal Selesai -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Tanggal Selesai

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_selesai"
                                        class="form-control">

                                </div>

                            </div>

                            <!-- Status -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option value="Publish">

                                        Publish

                                    </option>

                                    <option value="Draft">

                                        Draft

                                    </option>

                                </select>

                            </div>

                            <!-- Gambar -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Gambar (Opsional)

                                </label>

                                <input
                                    type="file"
                                    name="gambar"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <small class="text-muted">

                                    Format: JPG, JPEG, PNG, WEBP

                                </small>

                            </div>

                            <div class="d-flex justify-content-between">

                                <a
                                    href="index.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-success">

                                    Simpan Pengumuman

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>