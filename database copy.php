<?php
function koneksi_oracle() {
    $username = 'system';
    $password = '123';
    $conn_str = 'localhost/freepdb1';
    $conn = oci_connect($username, $password, $conn_str, 'AL32UTF8');

    if (!$conn) {
        $e = oci_error();
        die("Koneksi ke Oracle gagal: " . $e['message']);
    }

    return $conn;
}
?>
