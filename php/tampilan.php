<?php
include "config.php";

function makeSafeFolderName($name) {
    $name = trim((string) $name);
    $name = str_replace(['/', '\\'], '-', $name);
    $name = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $name);
    $name = preg_replace('/\s+/', '-', $name);
    return trim($name, "- ");
}

function deleteGalleryItem($conn, $id) {
    $id = intval($id);
    $data = $conn->query("SELECT * FROM galeri WHERE id = $id")->fetch_assoc();
    if (!$data) {
        return;
    }

    $baseFolder = ($data['type'] == 'foto') ? 'uploads/foto/' : 'uploads/video/';

    if (!empty($data['folder'])) {
        $safeFolder = makeSafeFolderName($data['folder']);
        $folderPath = $baseFolder . $safeFolder . '/';

        if (is_dir($folderPath)) {
            foreach (glob($folderPath . '*') as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            @rmdir($folderPath);
        }

        $stmt = $conn->prepare("DELETE FROM galeri WHERE folder = ? AND type = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $data['folder'], $data['type']);
            $stmt->execute();
            $stmt->close();
        } else {
            $conn->query("DELETE FROM galeri WHERE folder = '" . $conn->real_escape_string($data['folder']) . "' AND type = '" . $conn->real_escape_string($data['type']) . "'");
        }
    } else {
        $file = $baseFolder . $data['nama_file'];
        if (file_exists($file)) {
            unlink($file);
        }
        $conn->query("DELETE FROM galeri WHERE id = $id");
    }
}

// =======================
// HAPUS SINGLE
// =======================
if (isset($_GET['hapus'])) {
    deleteGalleryItem($conn, $_GET['hapus']);
    header("Location: tampilan.php");
    exit;
}

// =======================
// UPLOAD FILE
// =======================
if (isset($_POST['upload'])) {

    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {

        $namaFile = $_FILES['files']['name'][$key];
        $tmpFile  = $_FILES['files']['tmp_name'][$key];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        // TYPE FILE
        if (in_array($ext, ['mp4', 'webm', 'ogg'])) {
            $type = "video";
            $folder = "Video Presentasi";
            $baseFolder = "uploads/video/";
        } else {
            $type = "foto";
            $folder = "Kegiatan Belajar";
            $baseFolder = "uploads/foto/";
        }

        // NAMA BARU
        $newName = time() . "_" . $namaFile;

        // UPLOAD
        move_uploaded_file(
            $tmpFile,
            $baseFolder . $newName
        );

        // SIMPAN DATABASE
        $stmt = $conn->prepare("
            INSERT INTO galeri
            (nama_file, folder, type)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "sss",
            $newName,
            $folder,
            $type
        );

        $stmt->execute();
    }

    header("Location: tampilan.php");
    exit;
}

// =======================
// HAPUS MULTIPLE
// =======================
if (isset($_POST['hapusMultiple'])) {
    if (isset($_POST['hapus_ids']) && is_array($_POST['hapus_ids'])) {
        foreach ($_POST['hapus_ids'] as $id) {
            deleteGalleryItem($conn, $id);
        }
    }
    header("Location: tampilan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Galeri Innotech 24</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/tampilan.css">
    <link rel="stylesheet" href="../css/top.css">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<!-- HEADER -->
<header class="top-header">

    <!-- LOGO -->
    <div class="top-logo"
        onclick="window.location.href='../html/dasboard.html'">

        <img src="../dasborfoto/logo.png" alt="Logo Kelas">

        <h1 class="top-logo-title">
            Welcome Too Website InnotechG
        </h1>

    </div>

    <!-- NAVIGATION -->
    <nav class="top-navbar">

        <ul class="top-menu">

            <li>
                <a href="../html/dasboard.html">
                    Beranda
                </a>
            </li>

            <li>
                <a href="../html/jadwal.html">
                    Jadwal Kelas
                </a>
            </li>

            <li>
                <a href="../html/profilmember.html">
                    Profil Member
                </a>
            </li>

            <li>
                <a href="#">
                    Dokumentasi Kelas
                </a>
            </li>

            <li>
                <a href="../html/hubungikami.html">
                    Hubungi Kami
                </a>
            </li>

        </ul>

    </nav>

</header>

<!-- HERO -->
<header class="hero"></header>

<div class="bottom-title">

    <h2>
        Selamat Datang di Galeri Innotech 24
    </h2>

</div>

<!-- HALAMAN UTAMA -->
<section class="gallery-section">

    <form method="POST" id="galleryForm">

        <div class="gallery">

<?php

            $result = $conn->query("
                SELECT * FROM galeri
                ORDER BY id DESC
            ");

            $folderGroups = [];
            $galleryItems = [];

            while ($row = $result->fetch_assoc()) {
                if (!empty($row['folder'])) {
                    $groupKey = $row['folder'] . '|' . $row['type'];
                    if (!isset($folderGroups[$groupKey])) {
                        $folderGroups[$groupKey] = $row;
                    }
                } else {
                    $galleryItems[] = $row;
                }
            }

            $galleryItems = array_merge(array_values($folderGroups), $galleryItems);

            foreach ($galleryItems as $row) :

                $baseFolder = ($row['type'] == 'foto') ? 'uploads/foto/' : 'uploads/video/';
                $file = '';
                if (!empty($row['folder'])) {
                    $file = $baseFolder . makeSafeFolderName($row['folder']) . '/' . $row['nama_file'];
                    if (!file_exists($file)) {
                        $file = $baseFolder . $row['nama_file'];
                    }
                } else {
                    $file = $baseFolder . $row['nama_file'];
                }

            ?><!-- CARD -->
            <div class="card">

                <input type="checkbox" name="hapus_ids[]" value="<?= $row['id'] ?>" class="delete-checkbox" style="display: none;">

                <?php if ($row['type'] == 'foto') : ?>

                    <?php if (!empty($row['folder'])) : ?>
                        <a href="folder.php?folder=<?= urlencode($row['folder']) ?>">
                            <img src="<?= htmlspecialchars($file, ENT_QUOTES) ?>" alt="">
                        </a>
                    <?php else : ?>
                        <img src="<?= htmlspecialchars($file, ENT_QUOTES) ?>" alt="" onclick="openPreview('<?= htmlspecialchars($file, ENT_QUOTES) ?>', 'image')">
                    <?php endif; ?>

                    <div class="label foto">
                        FOTO
                    </div>

                <?php else : ?>

                    <?php if (!empty($row['folder'])) : ?>
                        <a href="folder.php?folder=<?= urlencode($row['folder']) ?>">
                            <video>
                                <source src="<?= $file ?>" type="video/mp4">
                            </video>
                        </a>
                    <?php else : ?>
                        <video onclick="openPreview('<?= htmlspecialchars($file, ENT_QUOTES) ?>', 'video')">
                            <source src="<?= htmlspecialchars($file, ENT_QUOTES) ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>

                    <div class="play-icon">
                        ▶
                    </div>

                    <div class="label video">
                        VIDEO
                    </div>

                <?php endif; ?>

                <div class="card-info">

                    <?php if (!empty($row['folder'])) : ?>
                        <h3>
                            <a class="folder-link" href="folder.php?folder=<?= urlencode($row['folder']) ?>">
                                <?= htmlspecialchars($row['folder']) ?>
                            </a>
                        </h3>
                    <?php else : ?>
                        <h3>Tidak ada judul</h3>
                    <?php endif; ?>

                </div>

                <!-- HAPUS -->
                <?php if (empty($row['folder'])) : ?>
                    <a class="delete-btn"
                        href="?hapus=<?= $row['id'] ?>">

                        Hapus

                    </a>
                <?php endif; ?>

            </div>

            <?php endforeach; ?>

        </div>

        <input type="hidden" name="hapusMultiple">

    </form>

</section>

<!-- PREVIEW -->
<div class="preview" id="preview">

    <span class="close"
        onclick="closePreview()">

        ✕

    </span>

    <img id="previewImage">

    <video id="previewVideo" controls></video>

</div>

<!-- BUTTON -->
<section class="top-action">

    <!-- KE HALAMAN UPLOAD -->
    <button
        onclick="window.location.href='../php/upload.php'">

        Upload

    </button>

        <!-- EDIT -->
        <button
            type="button"
            onclick="editMode()">

            Edit

        </button>

        <input
            type="hidden"
            name="upload">

</section>

<!-- BACKGROUND BALLS -->
<div class="bg-balls" id="bgBalls"></div>

<!-- JS -->
<script src="../js/tampilan.js"></script>

</body>
</html>