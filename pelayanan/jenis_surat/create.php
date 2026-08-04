<?php

include "../../auth/auth.php";
include "../../config/database.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Jenis Surat</title>

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

                            Tambah Jenis Surat

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="store.php"
                            method="POST">

                            <!-- Nama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Surat

                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Contoh : Surat Keterangan Domisili"
                                    required>

                            </div>

                            <!-- Slug -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Slug

                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    class="form-control"
                                    placeholder="surat-keterangan-domisili">

                                <small class="text-muted">

                                    Kosongkan jika ingin dibuat otomatis.

                                </small>

                            </div>

                            <!-- Icon -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Icon (Emoji)

                                </label>

                                <input
                                    type="text"
                                    name="icon"
                                    class="form-control"
                                    placeholder="📄">

                                <small class="text-muted">

                                    Contoh: 📄 🏠 👨‍👩‍👧 💼

                                </small>

                            </div>

                            <!-- Estimasi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Estimasi Hari Selesai

                                </label>

                                <input
                                    type="number"
                                    name="estimasi_hari"
                                    class="form-control"
                                    value="1"
                                    min="1"
                                    required>

                            </div>

                            <!-- Google Form -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Link Google Form

                                </label>

                                <input
                                    type="url"
                                    name="google_form"
                                    class="form-control"
                                    placeholder="https://forms.google.com/...">

                            </div>

                            <!-- Persyaratan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Persyaratan

                                </label>

                                <textarea
                                    name="persyaratan"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Contoh:

- Fotokopi KTP
- Fotokopi KK
- Surat Pengantar RT/RW"></textarea>

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="deskripsi"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Deskripsi singkat mengenai surat ini..."></textarea>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Status

                                    </label>

                                    <select
                                        name="is_active"
                                        class="form-select">

                                        <option value="1">

                                            Aktif

                                        </option>

                                        <option value="0">

                                            Nonaktif

                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Urutan

                                    </label>

                                    <input
                                        type="number"
                                        name="urutan"
                                        class="form-control"
                                        value="0">

                                </div>

                            </div>

                            <br>

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