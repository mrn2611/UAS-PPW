<?php
// =============================================
// KONEKSI DATABASE
// =============================================

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bioskop'; // <-- NAMA DATABASE BARU

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal! Error: " . mysqli_connect_error());
}

// Set karakter menjadi UTF-8
mysqli_set_charset($conn, "utf8");

// =============================================
// FUNGSI-FUNGSI HELPER
// =============================================

// Fungsi untuk menjalankan query
function query($sql) {
    global $conn;
    return mysqli_query($conn, $sql);
}

// Fungsi untuk mengambil semua data
function fetchAll($result) {
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fungsi untuk mengambil satu data
function fetchOne($result) {
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menghitung jumlah data
function countRows($result) {
    return mysqli_num_rows($result);
}

// Fungsi untuk mengamankan input dari user
function escapeString($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Fungsi untuk mendapatkan ID terakhir
function lastInsertId() {
    global $conn;
    return mysqli_insert_id($conn);
}
?>