<?php
include '../database copy.php';
$conn = koneksi_oracle();

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// 🔹 Program studi dari input form
$program_studi = isset($_POST['program_studi']) ? $_POST['program_studi'] : null;

$nim = null;
$nip = null;
$tahun_masuk = null;
$role = 'Mahasiswa'; // default

if (isset($_POST['nip'])) {
    $role = 'Dosen';
    $nip = $_POST['nip'];

} elseif (isset($_POST['nim'])) {
    $role = 'Mahasiswa';
    $nim = $_POST['nim'];

    // 🔹 Ambil dua digit pertama dan ubah ke tahun
    $prefix = substr($nim, 0, 2); // contoh: "24"
    $tahun_masuk = 2000 + intval($prefix); // hasil: 2024

} elseif (isset($_POST['nim_nip'])) {
    $role = 'Alumni';
    $nim = $_POST['nim_nip'];

    // Alumni juga bisa dihitung otomatis
    $prefix = substr($nim, 0, 2);
    $tahun_masuk = 2000 + intval($prefix);
}

// 🔹 Siapkan SQL Oracle
$sql = "INSERT INTO users 
        (user_id, nama, email, password, role_name, nim, nip, program_studi, tahun_masuk)
        VALUES 
        (users_seq.NEXTVAL, :nama, :email, :password, :role, :nim, :nip, :program_studi, :tahun_masuk)";

$stid = oci_parse($conn, $sql);

// 🔹 Bind semua parameter ke SQL
oci_bind_by_name($stid, ":nama", $nama);
oci_bind_by_name($stid, ":email", $email);
oci_bind_by_name($stid, ":password", $password);
oci_bind_by_name($stid, ":role", $role);
oci_bind_by_name($stid, ":nim", $nim);
oci_bind_by_name($stid, ":nip", $nip);
oci_bind_by_name($stid, ":program_studi", $program_studi);
oci_bind_by_name($stid, ":tahun_masuk", $tahun_masuk);

if (oci_execute($stid)) {
    echo "✅ Registrasi berhasil! Tahun masuk: $tahun_masuk, Program Studi: $program_studi";
} else {
    $e = oci_error($stid);
    echo "❌ Gagal: " . $e['message'];
}

oci_free_statement($stid);
oci_close($conn);
?>
