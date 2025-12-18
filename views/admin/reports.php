<div class="bg-white rounded-xl shadow p-6">
  <h2 class="text-xl font-bold mb-4">Laporan Masuk</h2>

  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">Pelapor</th>
        <th class="p-3 text-left">Terlapor</th>
        <th class="p-3 text-left">Alasan</th>
        <th class="p-3 text-left">Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($pendingReports)): ?>
        <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada laporan baru.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($pendingReports as $r): ?>
          <tr class="border-t hover:bg-gray-50">
            <td class="p-3"><?= htmlspecialchars($r['REPORTER_EMAIL'] ?? '-') ?></td>
            <td class="p-3"><?= htmlspecialchars($r['TARGET_EMAIL'] ?? '-') ?></td>
            <td class="p-3"><?= htmlspecialchars($r['REASON'] ?? '-') ?></td>
            <td class="p-3">
              <a href="<?= BASE_URL ?>/admin/review?id=<?= $r['REPORT_ID'] ?>"
                 class="text-indigo-600 font-medium hover:text-indigo-800">Lihat</a>
            </td>
          </tr>
        <?php endforeach ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
