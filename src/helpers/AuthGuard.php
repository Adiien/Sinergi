<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL);
    exit;
}

$db = koneksi_oracle();
$sql = "SELECT status FROM users WHERE user_id = :id";
$stmt = oci_parse($db, $sql);
oci_bind_by_name($stmt, ":id", $_SESSION['user_id']);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

if (!$row || strtolower($row['STATUS']) === 'suspended') {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}
