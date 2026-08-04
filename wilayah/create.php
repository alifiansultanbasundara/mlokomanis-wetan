<?php
include "../auth/auth.php";
include "../config/database.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Data Wilayah</title>

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
                            Tambah Data Wilayah
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

                                    Judul

                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control"
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
                                    id="slug"
                                    class="form-control"
                                    readonly
                                    required>

                                <small class="text-muted">
                                    Slug dibuat otomatis.
                                </small>

                            </div>

                            <!-- Jenis -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Jenis Data

                                </label>

                                <select
                                    name="type"
                                    class="form-select"
                                    required>

                                    <option value="Profil Wilayah">
                                        Profil Wilayah
                                    </option>

                                    <option value="Luas Wilayah">
                                        Luas Wilayah
                                    </option>

                                    <option value="Peta Desa">
                                        Peta Desa
                                    </option>

                                    <option value="Peta Blok SPPT">
                                        Peta Blok SPPT
                                    </option>

                                    <option value="Peta RT">
                                        Peta RT
                                    </option>

                                    <option value="Peta Dusun">
                                        Peta Dusun
                                    </option>

                                    <option value="Batas Wilayah">
                                        Batas Wilayah
                                    </option>

                                    <option value="Lainnya">
                                        Lainnya
                                    </option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option value="Published">
                                        Published
                                    </option>

                                    <option value="Draft">
                                        Draft
                                    </option>

                                </select>

                            </div>

                            <!-- Deskripsi -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Deskripsi

                                </label>

                                <textarea
                                    name="description"
                                    rows="8"
                                    class="form-control"
                                    placeholder="Masukkan deskripsi wilayah..."></textarea>

                            </div>

                            <!-- Thumbnail -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Thumbnail

                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    class="form-control">

                                <small class="text-muted">

                                    Format:
                                    JPG, JPEG, PNG, WEBP

                                </small>

                            </div>

                            <!-- File -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Upload File

                                </label>

                                <input
                                    type="file"
                                    name="file"
                                    class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp">

                                <small class="text-muted">

                                    PDF / JPG / PNG / WEBP

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

                                    Simpan Data

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        const title = document.getElementById("title");
        const slug = document.getElementById("slug");

        title.addEventListener("keyup", function() {

            let value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            slug.value = value;

        });
    </script>

</body>

</html>