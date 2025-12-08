<?php

// 1. Fungsi bantu untuk load .env
// Kita cek function_exists agar tidak error jika file di-include 2 kali
if (!function_exists('loadEnv')) {
    function loadEnv($path)
    {
        if (!file_exists($path)) {
            // Jika tidak ada .env, kita diam saja atau return false
            // agar tidak fatal error di production jika pakai env server
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}

// 2. INI FUNGSI UTAMA YANG DICARI CONTROLLER ANDA
function koneksi_oracle()
{
    // Load file .env (lokasinya di folder yang sama dengan file ini)
    loadEnv(__DIR__ . '/.env');

    // Ambil variabel
    $username = getenv('ORACLE_USER');
    $password = getenv('ORACLE_PASS');
    $connection_string = getenv('ORACLE_STR');

    // Cek apakah variabel terbaca (Optional: untuk debugging)
    if (!$username || !$password) {
        die("Error: Kredensial database tidak ditemukan di .env atau environment server.");
    }

    // Buat Koneksi
    $conn = oci_connect($username, $password, $connection_string);

    if (!$conn) {
        $e = oci_error();
        // Tampilkan error jika gagal
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        return false;
    }

    // PENTING: Kembalikan objek koneksi agar bisa dipakai AuthController
    return $conn;
}
