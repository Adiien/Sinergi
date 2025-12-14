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
    <?php foreach ($pendingReports as $r): ?>
      <tr class="border-t">
        <td class="p-3"><?= htmlspecialchars($r['REPORTER_EMAIL']) ?></td>
        <td class="p-3"><?= htmlspecialchars($r['TARGET_EMAIL']) ?></td>
        <td class="p-3"><?= htmlspecialchars($r['REASON']) ?></td>
        <td class="p-3">
          <a href="<?= BASE_URL ?>/admin/review?id=<?= $r['REPORT_ID'] ?>"
             class="text-indigo-600">Lihat</a>
        </td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</div>
