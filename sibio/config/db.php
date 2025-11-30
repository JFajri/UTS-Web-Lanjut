<?php
// config/db.php
// Koneksi database menggunakan mysqli
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sibio_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die("Gagal koneksi MySQL: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
