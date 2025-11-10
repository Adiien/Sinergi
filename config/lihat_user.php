<?php
include '../database copy.php'; // pastikan file ini berisi fungsi koneksi_oracle()

$conn = koneksi_oracle(); // 🔹 panggil fungsi agar $conn terisi

// 🔹 Ambil kolom tambahan program_studi dan tahun_masuk
$sql = "SELECT 
            user_id, 
            nama, 
            email, 
            role_name, 
            nim, 
            nip, 
            program_studi, 
            tahun_masuk 
        FROM users 
        ORDER BY user_id";

$stid = oci_parse($conn, $sql);
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar User Terdaftar</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; background: #f6f6f6; }
    table { border-collapse: collapse; width: 100%; background: white; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #5e5e8f; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    h2 { color: #333; }
  </style>
</head>
<body>
  <h2>👥 Daftar User Terdaftar</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Role</th>
      <th>NIM</th>
      <th>NIP</th>
      <th>Program Studi</th>
      <th>Tahun Masuk</th>
    </tr>
    <?php while (($row = oci_fetch_assoc($stid)) != false) : ?>
    <tr>
      <td><?= htmlspecialchars($row['USER_ID']) ?></td>
      <td><?= htmlspecialchars($row['NAMA']) ?></td>
      <td><?= htmlspecialchars($row['EMAIL']) ?></td>
      <td><?= htmlspecialchars($row['ROLE_NAME']) ?></td>
      <td><?= htmlspecialchars($row['NIM']) ?></td>
      <td><?= htmlspecialchars($row['NIP']) ?></td>
      <td><?= htmlspecialchars($row['PROGRAM_STUDI']) ?></td>
      <td><?= htmlspecialchars($row['TAHUN_MASUK']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>

<?php
oci_free_statement($stid);
oci_close($conn);
?>
