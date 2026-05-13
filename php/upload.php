<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "config.php";

function makeSafeFolderName($name) {
    $name = trim((string) $name);
    $name = str_replace(['/', '\\'], '-', $name);
    $name = preg_replace('/[^A-Za-z0-9_\- ]+/', '', $name);
    $name = preg_replace('/\s+/', '-', $name);
    return trim($name, "- ");
}

function normalizeUploadPath($path) {
    $path = trim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));
    return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}

// DEBUG LOG
function debugLog($msg) {
    error_log("[UPLOAD] " . $msg);
}

debugLog("Page loaded. POST: " . json_encode($_POST));
debugLog("FILES: " . json_encode($_FILES));

if (isset($_POST['uploadFoto'])) {
    debugLog("uploadFoto detected");
    $photoTitleRaw = trim($_POST['photoTitle'] ?? '');
    $safePhotoFolder = makeSafeFolderName($photoTitleRaw);
    $baseFolder = normalizeUploadPath(__DIR__ . "/uploads/foto");
    $publicFolder = "uploads/foto/";
    $folderTujuan = !empty($safePhotoFolder) ? normalizeUploadPath($baseFolder . $safePhotoFolder) : $baseFolder;
    $folderUrl = !empty($safePhotoFolder) ? $publicFolder . $safePhotoFolder . "/" : $publicFolder;
    $folderDB = $photoTitleRaw;

    debugLog("Title raw: '$photoTitleRaw', Safe folder: '$safePhotoFolder', Folder: '$folderTujuan'");

    if (!is_dir($folderTujuan)) {
        if (!mkdir($folderTujuan, 0777, true)) {
            debugLog("Failed to create folder: $folderTujuan");
        } else {
            debugLog("Folder created: $folderTujuan");
        }
    }

    if (isset($_FILES['photos']['tmp_name']) && is_array($_FILES['photos']['tmp_name'])) {
        debugLog("Photos count: " . count($_FILES['photos']['tmp_name']));
        
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            debugLog("Processing photo $key: $tmp_name");
            
            if (!is_uploaded_file($tmp_name)) {
                debugLog("Not uploaded file: $tmp_name");
                continue;
            }

            $namaFile = basename($_FILES['photos']['name'][$key]);
            $newName = uniqid("foto_") . "_" . $namaFile;
            $fullPath = $folderTujuan . $newName;

            debugLog("Moving $tmp_name to $fullPath");

            if (move_uploaded_file($tmp_name, $fullPath)) {
                debugLog("File moved successfully. Inserting to DB");
                
                $stmt = $conn->prepare("INSERT INTO galeri (nama_file, folder, type) VALUES (?, ?, ?)");
                if (!$stmt) {
                    debugLog("Prepare failed: " . $conn->error);
                    continue;
                }
                
                $type = "foto";
                $stmt->bind_param("sss", $newName, $folderDB, $type);
                
                if (!$stmt->execute()) {
                    debugLog("Execute failed: " . $stmt->error);
                } else {
                    debugLog("DB insert success for $newName");
                }
                $stmt->close();
            } else {
                debugLog("move_uploaded_file failed for $tmp_name");
            }
        }
    } else {
        debugLog("No photos found in FILES");
    }

    header("Location: tampilan.php");
    exit;
}

if (isset($_POST['uploadVideo'])) {
    debugLog("uploadVideo detected");
    $videoTitleRaw = trim($_POST['videoTitle'] ?? '');
    $safeVideoFolder = makeSafeFolderName($videoTitleRaw);
    $baseFolder = normalizeUploadPath(__DIR__ . "/uploads/video");
    $publicFolder = "uploads/video/";
    $folderTujuan = !empty($safeVideoFolder) ? normalizeUploadPath($baseFolder . $safeVideoFolder) : $baseFolder;
    $folderUrl = !empty($safeVideoFolder) ? $publicFolder . $safeVideoFolder . "/" : $publicFolder;
    $folderDB = $videoTitleRaw;

    debugLog("Title raw: '$videoTitleRaw', Safe folder: '$safeVideoFolder', Folder: '$folderTujuan'");

    if (!is_dir($folderTujuan)) {
        if (!mkdir($folderTujuan, 0777, true)) {
            debugLog("Failed to create folder: $folderTujuan");
        } else {
            debugLog("Folder created: $folderTujuan");
        }
    }

    if (isset($_FILES['videos']['tmp_name']) && is_array($_FILES['videos']['tmp_name'])) {
        debugLog("Videos count: " . count($_FILES['videos']['tmp_name']));
        
        foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
            debugLog("Processing video $key: $tmp_name");
            
            if (!is_uploaded_file($tmp_name)) {
                debugLog("Not uploaded file: $tmp_name");
                continue;
            }

            $namaFile = basename($_FILES['videos']['name'][$key]);
            $newName = uniqid("video_") . "_" . $namaFile;
            $fullPath = $folderTujuan . $newName;

            debugLog("Moving $tmp_name to $fullPath");

            if (move_uploaded_file($tmp_name, $fullPath)) {
                debugLog("File moved successfully. Inserting to DB");
                
                $stmt = $conn->prepare("INSERT INTO galeri (nama_file, folder, type) VALUES (?, ?, ?)");
                if (!$stmt) {
                    debugLog("Prepare failed: " . $conn->error);
                    continue;
                }
                
                $type = "video";
                $stmt->bind_param("sss", $newName, $folderDB, $type);
                
                if (!$stmt->execute()) {
                    debugLog("Execute failed: " . $stmt->error);
                } else {
                    debugLog("DB insert success for $newName");
                }
                $stmt->close();
            } else {
                debugLog("move_uploaded_file failed for $tmp_name");
            }
        }
    } else {
        debugLog("No videos found in FILES");
    }

    header("Location: tampilan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Upload Gallery</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/upload.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<!-- BACKGROUND -->
<div class="bg-animation">

    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>

</div>

<div class="container">

    <!-- TITLE -->
    <div class="title">

        <h1>
            Upload Gallery
        </h1>

        <p>
            Upload Foto dan Video Dokumentasi
        </p>

    </div>

    <div class="wrapper">

        <!-- ================= FOTO ================= -->
        <div class="card">

            <form method="POST" enctype="multipart/form-data">

                <div class="icon">

                    <i class="fa-solid fa-image"></i>

                </div>

                <h2>
                    Upload Foto
                </h2>

                <!-- INPUT JUDUL -->
                <input
                    id="photoTitle"
                    type="text"
                    name="photoTitle"
                    class="title-input"
                    placeholder="Masukkan Judul Folder Foto (opsional)">

                <!-- BUTTON -->
                <label
                    for="photoInput"
                    class="btn">

                    Pilih Banyak Foto

                </label>

                <!-- INPUT FOTO -->
                <input
                    type="file"
                    id="photoInput"
                    name="photos[]"
                    accept="image/*"
                    multiple
                    hidden>

                <!-- PREVIEW -->
                <div
                    class="gallery"
                    id="photoPreview">

                </div>

                <!-- SUBMIT -->
                <button
                    type="submit"
                    name="uploadFoto"
                    class="submit-btn">

                    Upload Foto

                </button>

            </form>

        </div>

        <!-- ================= VIDEO ================= -->
        <div class="card">

            <form method="POST" enctype="multipart/form-data">

                <div class="icon video-icon">

                    <i class="fa-solid fa-video"></i>

                </div>

                <h2>
                    Upload Video
                </h2>

                <!-- INPUT JUDUL -->
                <input
                    id="videoTitle"
                    type="text"
                    name="videoTitle"
                    class="title-input"
                    placeholder="Masukkan Judul Folder Video (opsional)">

                <!-- BUTTON -->
                <label
                    for="videoInput"
                    class="btn video-btn">

                    Pilih Video

                </label>

                <!-- INPUT VIDEO -->
                <input
                    type="file"
                    id="videoInput"
                    name="videos[]"
                    accept="video/*"
                    multiple
                    hidden>

                <!-- PREVIEW -->
                <div
                    class="gallery"
                    id="videoPreview">

                </div>

                <!-- SUBMIT -->
                <button
                    type="submit"
                    name="uploadVideo"
                    class="submit-btn video-submit">

                    Upload Video

                </button>

            </form>

        </div>

    </div>

</div>

<!-- JS -->
<script src="../js/upload.js"></script>

</body>
</html>