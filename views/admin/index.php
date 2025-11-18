<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - SINERGI</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100">
  <?php
  // Kita tetap menggunakan navbar yang sama
  require_once 'views/partials/navbar.php';
  ?>

  <main id="main-content" class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>

    <div class="bg-white rounded-xl shadow-lg p-5">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Pengguna</h2>

      <?php if (isset($_SESSION['success_message'])): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
          <?php unset($_SESSION['success_message']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
          <?php unset($_SESSION['error_message']); ?>
        </div>
      <?php endif; ?>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM/NIP</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php if (isset($users) && !empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['NAMA']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['EMAIL']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['ROLE_NAME'] == 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800'; ?>">
                      <?php echo htmlspecialchars($user['ROLE_NAME']); ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?php echo htmlspecialchars($user['NIM'] ?? $user['NIP'] ?? 'N/A'); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    <a href="<?= BASE_URL ?>/admin/delete?id=<?php echo $user['USER_ID']; ?>"
                      class="text-red-600 hover:text-red-900 ml-4"
                      onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                      Hapus
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data pengguna tidak ditemukan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="bg-white rounded-xl shadow-lg p-5 mt-8">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Laporan Masuk (Pending)</h2>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konten Dilaporkan (Singkat)</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Lapor</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php if (isset($pendingReports) && !empty($pendingReports)): ?>
              <?php foreach ($pendingReports as $report): ?>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <?php echo htmlspecialchars($report['REPORTER_NAME']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                      <?php echo htmlspecialchars($report['TARGET_TYPE']); ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                    <?php if ($report['TARGET_TYPE'] == 'post' && isset($report['POST_CONTENT'])): ?>
                      <em>"<?php echo htmlspecialchars(substr($report['POST_CONTENT'], 0, 100)); ?>..."</em>
                    <?php else: ?>
                      (Data Komentar) ID: <?php echo $report['TARGET_ID']; ?>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-500 max-w-sm whitespace-normal">
                    <?php echo htmlspecialchars($report['REASON']); ?>
                  </td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?php echo htmlspecialchars($report['CREATED_AT']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="#" class="text-green-600 hover:text-green-900">Review</a>
                    <a href="#" class="text-red-600 hover:text-red-900 ml-4">Dismiss</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada laporan masuk yang pending.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <?php // Script untuk alert, jika Anda mau
  ?>
  <script>
    // Script ini sekarang akan menangani kedua jenis alert
    setTimeout(function() {
      const alertBox = document.querySelector('[role="alert"]');
      if (alertBox) {
        alertBox.style.transition = 'opacity 0.5s ease-out';
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 3000);
  </script>
</body>

</html>