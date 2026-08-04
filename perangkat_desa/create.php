<?php

include "../auth/auth.php";
include "../config/database.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Perangkat Desa</title>

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

                            Tambah Perangkat Desa

                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="store.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <!-- Nama -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Lengkap

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    required>

                            </div>

                            <!-- Jabatan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jabatan

                                </label>

                                <input
                                    type="text"
                                    name="position"
                                    class="form-control"
                                    placeholder="Contoh: Kepala Desa"
                                    required>

                            </div>

                            <!-- NIP -->

                            <div class="mb-3">

                                <label class="form-label">

                                    NIP

                                </label>

                                <input
                                    type="text"
                                    name="nip"
                                    class="form-control">

                            </div>

                            <!-- No HP -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Nomor HP

                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control">

                            </div>

                            <!-- Email -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control">

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi / Tugas

                                </label>

                                <textarea
                                    name="description"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Deskripsi singkat perangkat desa..."></textarea>

                            </div>

                            <!-- Urutan -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Urutan Tampil

                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    class="form-control"
                                    value="0">

                            </div>

                            <!-- Foto -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Foto

                                </label>

                                <input
                                    type="file"
                                    name="photo"
                                    accept="image/*"
                                    class="form-control">

                                <small class="text-muted">

                                    Format:
                                    JPG, JPEG, PNG, WEBP (Maks. 2 MB)

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

                                    <option value="Aktif">

                                        Aktif

                                    </option>

                                    <option value="Tidak Aktif">

                                        Tidak Aktif

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