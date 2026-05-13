<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "innotech24"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$conn->set_charset('utf8mb4');

$conn->query(
    "CREATE TABLE IF NOT EXISTS galeri (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama_file VARCHAR(255) NOT NULL,
        folder VARCHAR(255) DEFAULT '',
        type ENUM('foto','video') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

?>