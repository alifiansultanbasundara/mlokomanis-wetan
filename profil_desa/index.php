<?php

include "../auth/auth.php";
include "../config/database.php";

// ======================================
// Ambil Data Profil
// ======================================

$query = mysqli_query($conn, "SELECT * FROM profil_desa LIMIT 1");

if (mysqli_num_rows($query) == 0) {

    mysqli_query($conn, "

    INSERT INTO profil_desa(

        nama_desa,

        kecamatan,

        kabupaten,

        provinsi,

        jumlah_penduduk,

        jumlah_kk,

        created_at,

        updated_at

    )

    VALUES(

        '',

        '',

        '',

        '',

        0,

        0,

        NOW(),

        NOW()

    )

    ");

    $query = mysqli_query($conn, "SELECT * FROM profil_desa LIMIT 1");
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Profil Desa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <?php

        if (isset($_SESSION['success'])) {

        ?>

            <div class="alert alert-success">

                <?= $_SESSION['success']; ?>

            </div>

        <?php

            unset($_SESSION['success']);
        }

        ?>

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3 class="mb-0">

                    Profil Desa

                </h3>

            </div>

            <div class="card-body">

                <form
                    action="update.php"
                    method="POST"
                    enctype="multipart/form-data">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $data['id']; ?>">

                    <!-- ========================= -->

                    <h5 class="mb-3">

                        Identitas Desa

                    </h5>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Nama Desa</label>

                            <input
                                type="text"
                                name="nama_desa"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama_desa']); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Kecamatan</label>

                            <input
                                type="text"
                                name="kecamatan"
                                class="form-control"
                                value="<?= htmlspecialchars($data['kecamatan']); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Kabupaten</label>

                            <input
                                type="text"
                                name="kabupaten"
                                class="form-control"
                                value="<?= htmlspecialchars($data['kabupaten']); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Provinsi</label>

                            <input
                                type="text"
                                name="provinsi"
                                class="form-control"
                                value="<?= htmlspecialchars($data['provinsi']); ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Kode Pos</label>

                            <input
                                type="text"
                                name="kode_pos"
                                class="form-control"
                                value="<?= htmlspecialchars($data['kode_pos'] ?? ''); ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Luas Wilayah (Ha)</label>

                            <input
                                type="number"
                                step="0.01"
                                name="luas_wilayah"
                                class="form-control"
                                value="<?= $data['luas_wilayah']; ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Jumlah Penduduk</label>

                            <input
                                type="number"
                                name="jumlah_penduduk"
                                class="form-control"
                                value="<?= $data['jumlah_penduduk']; ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Jumlah KK</label>

                            <input
                                type="number"
                                name="jumlah_kk"
                                class="form-control"
                                value="<?= $data['jumlah_kk']; ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Alamat</label>

                            <textarea
                                name="alamat"
                                rows="3"
                                class="form-control"><?= htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">

                        Profil Desa

                    </h5>

                    <div class="mb-3">

                        <label>Sejarah Desa</label>

                        <textarea
                            name="sejarah"
                            rows="6"
                            class="form-control"><?= htmlspecialchars($data['sejarah'] ?? ''); ?></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Visi</label>

                        <textarea
                            name="visi"
                            rows="4"
                            class="form-control"><?= htmlspecialchars($data['visi'] ?? ''); ?></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Misi</label>

                        <textarea
                            name="misi"
                            rows="6"
                            class="form-control"><?= htmlspecialchars($data['misi'] ?? ''); ?></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Motto</label>

                        <input
                            type="text"
                            name="motto"
                            class="form-control"
                            value="<?= htmlspecialchars($data['motto'] ?? ''); ?>">

                    </div>

                    <hr>

                    <h5 class="mb-3">

                        Kepala Desa

                    </h5>

                    <div class="mb-3">

                        <label>Nama Kepala Desa</label>

                        <input
                            type="text"
                            name="nama_kepala_desa"
                            class="form-control"
                            value="<?= htmlspecialchars($data['nama_kepala_desa'] ?? ''); ?>">

                    </div>

                    <div class="mb-3">

                        <label>Sambutan Kepala Desa</label>

                        <textarea
                            name="sambutan_kepala"
                            rows="6"
                            class="form-control"><?= htmlspecialchars($data['sambutan_kepala'] ?? ''); ?></textarea>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label>Foto Kepala Desa</label>

                            <input
                                type="hidden"
                                name="old_foto_kepala"
                                value="<?= $data['foto_kepala']; ?>">

                            <input
                                type="file"
                                name="foto_kepala"
                                class="form-control">

                            <?php if (!empty($data['foto_kepala'])) { ?>

                                <img
                                    src="uploads/profil_desa/<?= $data['foto_kepala']; ?>"
                                    class="img-thumbnail mt-2"
                                    width="150">

                            <?php } ?>

                        </div>

                        <div class="col-md-6">

                            <label>Logo Desa</label>

                            <input
                                type="hidden"
                                name="old_logo_desa"
                                value="<?= $data['logo_desa']; ?>">

                            <input
                                type="file"
                                name="logo_desa"
                                class="form-control">

                            <?php if (!empty($data['logo_desa'])) { ?>

                                <img
                                    src="uploads/profil_desa/<?= $data['logo_desa']; ?>"
                                    class="img-thumbnail mt-2"
                                    width="150">

                            <?php } ?>

                        </div>

                    </div>

                    <br>

                    <div class="mb-3">

                        <label>Foto Kantor Desa</label>

                        <input
                            type="hidden"
                            name="old_foto_kantor"
                            value="<?= $data['foto_kantor']; ?>">

                        <input
                            type="file"
                            name="foto_kantor"
                            class="form-control">

                        <?php if (!empty($data['foto_kantor'])) { ?>

                            <img
                                src="uploads/profil_desa/<?= $data['foto_kantor']; ?>"
                                class="img-thumbnail mt-2"
                                width="200">

                        <?php } ?>

                    </div>

                    <hr>

                    <h5 class="mb-3">

                        Kontak & Media Sosial

                    </h5>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Telepon</label>

                            <input
                                type="text"
                                name="telepon"
                                class="form-control"
                                value="<?= htmlspecialchars($data['telepon'] ?? ''); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($data['email'] ?? ''); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Website</label>

                            <input
                                type="url"
                                name="website"
                                class="form-control"
                                value="<?= htmlspecialchars($data['website'] ?? ''); ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Google Maps</label>

                            <input
                                type="text"
                                name="google_maps"
                                class="form-control"
                                value="<?= htmlspecialchars($data['google_maps'] ?? ''); ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Facebook</label>

                            <input
                                type="url"
                                name="facebook"
                                class="form-control"
                                value="<?= htmlspecialchars($data['facebook'] ?? ''); ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Instagram</label>

                            <input
                                type="url"
                                name="instagram"
                                class="form-control"
                                value="<?= htmlspecialchars($data['instagram'] ?? ''); ?>">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>YouTube</label>

                            <input
                                type="url"
                                name="youtube"
                                class="form-control"
                                value="<?= htmlspecialchars($data['youtube'] ?? ''); ?>">

                        </div>

                        <div class="col-md-12 mb-4">

                            <label>TikTok</label>

                            <input
                                type="url"
                                name="tiktok"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tiktok'] ?? ''); ?>">

                        </div>

                    </div>

                    <div class="text-end">

                        <button
                            type="submit"
                            class="btn btn-success btn-lg">

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>