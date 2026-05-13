<?php
include "config.php";

function makeSafeFolderName($name) {
    $name = trim((string) $name);
    $name = str_replace(['/', '\\'], '-', $name);
    $name = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $name);
    $name = preg_replace('/\s+/', '-', $name);
    return trim($name, "- ");
}

$folderName = isset($_GET['folder']) ? trim($_GET['folder']) : '';
if ($folderName === '') {
    header("Location: tampilan.php");
    exit;
}

$safeFolderName = makeSafeFolderName($folderName);

if (isset($_POST['upload'])) {
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            if (!is_uploaded_file($tmp_name)) {
                continue;
            }

            $namaFile = basename($_FILES['files']['name'][$key]);
            $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
            $allowedImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowedVideo = ['mp4', 'webm', 'ogg'];

            if (in_array($ext, $allowedImage)) {
                $type = 'foto';
                $baseFolder = 'uploads/foto/';
            } elseif (in_array($ext, $allowedVideo)) {
                $type = 'video';
                $baseFolder = 'uploads/video/';
            } else {
                continue;
            }

            $folderTujuan = !empty($safeFolderName)
                ? $baseFolder . $safeFolderName . "/"
                : $baseFolder;

            if (!file_exists($folderTujuan)) {
                mkdir($folderTujuan, 0777, true);
            }

            $newName = time() . '_' . $namaFile;
            if (move_uploaded_file($tmp_name, $folderTujuan . $newName)) {
                $stmt = $conn->prepare("INSERT INTO galeri (nama_file, folder, type) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $newName, $folderName, $type);
                $stmt->execute();
            }
        }
    }
    header("Location: folder.php?folder=" . urlencode($folderName));
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $data = $conn->query("SELECT * FROM galeri WHERE id = $id")->fetch_assoc();
    if ($data) {
        $baseFolder = ($data['type'] == 'foto') ? 'uploads/foto/' : 'uploads/video/';
        $folderPath = !empty($data['folder']) ? $baseFolder . makeSafeFolderName($data['folder']) . '/' : $baseFolder;
        $file = $folderPath . $data['nama_file'];
        if (file_exists($file)) {
            unlink($file);
        }
        $conn->query("DELETE FROM galeri WHERE id = $id");
    }
    header("Location: folder.php?folder=" . urlencode($folderName));
    exit;
}

$gallery = $conn->query("SELECT * FROM galeri WHERE folder = '" . $conn->real_escape_string($folderName) . "' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Gallery</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/folder.css">
</head>

<body>

    <!-- HEADER -->
    <div class="topbar">

        <a href="tampilan.php" class="back-btn">
            <span>←</span> Kembali
        </a>

        <h2 class="logo-title">
            Gallery Innotech24
        </h2>

        <!-- FORM UPLOAD -->
        <form method="POST" enctype="multipart/form-data">

            <label for="filesInput" class="upload-btn">
                + Tambah Dokumentasi
            </label>

            <input
                type="file"
                id="filesInput"
                name="files[]"
                accept="image/*,video/*"
                multiple
                hidden
                onchange="this.form.submit()"
            >

            <input type="hidden" name="upload">

        </form>

    </div>

    <!-- CONTENT -->
    <div class="container">

        <div class="folder-title">
            <h1 id="folderName">
                <?= htmlspecialchars($folderName) ?>
            </h1>
        </div>

        <!-- GRID -->
        <div class="gallery-grid" id="galleryGrid">

            <?php while ($row = $gallery->fetch_assoc()) :
                $baseFolder = ($row['type'] == 'foto') ? 'uploads/foto/' : 'uploads/video/';
                $folderPath = !empty($row['folder']) ? $baseFolder . makeSafeFolderName($row['folder']) . '/' : $baseFolder;
                $file = $folderPath . $row['nama_file'];
            ?>

                <div class="gallery-item media-card">

                    <?php if ($row['type'] == 'foto') : ?>
                        <img
                            src="<?= htmlspecialchars($file) ?>"
                            onclick="openPreview('<?= htmlspecialchars($file) ?>', 'image')"
                        >
                    <?php else : ?>
                        <video
                            onclick="openPreview('<?= htmlspecialchars($file) ?>', 'video')"
                            muted
                        >
                            <source src="<?= htmlspecialchars($file) ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>

                    <div class="gallery-item-footer">
                        <span class="media-type"><?= strtoupper($row['type']) ?></span>
                        <a class="delete-btn" href="folder.php?folder=<?= urlencode($folderName) ?>&hapus=<?= $row['id'] ?>">
                            Hapus
                        </a>
                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

    <div class="preview" id="preview">

        <span class="close-preview" onclick="closePreview()">
            ✕
        </span>

        <img id="previewImage">
        <video id="previewVideo" controls></video>

    </div>

    <!-- JS -->
    <script src="../js/folder.js"></script>

    <!-- BACKGROUND BALL -->
    <div class="bg-animation">
        <span class="ball ball1"></span>
        <span class="ball ball2"></span>
        <span class="ball ball3"></span>
        <span class="ball ball4"></span>
        <span class="ball ball5"></span>
        <span class="ball ball6"></span>
        <span class="ball ball7"></span>
        <span class="ball ball8"></span>
        <span class="ball ball9"></span>
        <span class="ball ball10"></span>
    </div>

</body>

</html>