<?php

include "../auth/auth.php";
include "../config/database.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Produk Hukum</title>

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

                            Tambah Produk Hukum

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="store.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <!-- Jenis -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jenis Produk Hukum

                                </label>

                                <select
                                    name="jenis"
                                    class="form-select"
                                    required>

                                    <option value="">-- Pilih Jenis --</option>

                                    <option>Peraturan Desa</option>

                                    <option>Peraturan Kepala Desa</option>

                                    <option>Keputusan Kepala Desa</option>

                                    <option>Surat Keputusan</option>

                                    <option>Instruksi</option>

                                    <option>Lainnya</option>

                                </select>

                            </div>

                            <!-- Nomor -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nomor Dokumen

                                </label>

                                <input
                                    type="text"
                                    name="nomor"
                                    class="form-control"
                                    placeholder="Contoh : 3 Tahun 2026"
                                    required>

                            </div>

                            <!-- Tahun -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Tahun

                                </label>

                                <input
                                    type="number"
                                    name="tahun"
                                    class="form-control"
                                    value="<?= date('Y'); ?>"
                                    min="2000"
                                    max="<?= date('Y') + 5; ?>"
                                    required>

                            </div>

                            <!-- Judul -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Judul

                                </label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    required>

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="deskripsi"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Deskripsi singkat..."></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Tanggal Ditetapkan

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_ditetapkan"
                                        class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Tanggal Diundangkan

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_diundangkan"
                                        class="form-control">

                                </div>

                            </div>

                            <br>

                            <!-- PDF -->

                            <div class="mb-4">

                                <label class="form-label">

                                    File PDF

                                </label>

                                <input
                                    type="file"
                                    name="file_pdf"
                                    class="form-control"
                                    accept=".pdf"
                                    required>

                                <small class="text-muted">

                                    Format PDF, maksimal 10 MB.

                                </small>

                            </div>

                            <!-- Status -->

                            <div class="mb-4">

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

                            <div class="d-flex justify-content-between">

                                <a
                                    href="index.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-success">

                                    Simpan

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