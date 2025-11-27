<?php
function koneksi_oracle()
{
    $username = 'Adien';
    $password = 'Sinergi_7';
    $conn_str = 'localhost:1522/orclpdb';
    $conn = oci_connect($username, $password, $conn_str);

    if (!$conn) {
        $e = oci_error();
        die("Koneksi ke Oracle gagal: " . $e['message']);
    }

    return $conn;
}
?>
