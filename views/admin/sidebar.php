<div class="bg-white rounded-xl shadow p-4 space-y-2 sticky top-24">
  <a href="<?= BASE_URL ?>/admin?tab=users"
     class="block px-4 py-2 rounded <?= $tab === 'users' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     Manajemen Pengguna
  </a>

  <a href="<?= BASE_URL ?>/admin?tab=stats"
     class="block px-4 py-2 rounded <?= $tab === 'stats' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     Statistik
  </a>

  <a href="<?= BASE_URL ?>/admin?tab=reports"
     class="block px-4 py-2 rounded <?= $tab === 'reports' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     Laporan Masuk
  </a>
</div>
