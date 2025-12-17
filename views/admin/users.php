<div class="bg-white rounded-xl shadow p-6">
  <h2 class="text-xl font-bold mb-4">Manajemen Pengguna</h2>

  <form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">

  <input type="hidden" name="tab" value="users">

  <!-- FILTER NAMA -->
  <div>
    <label class="block text-xs text-gray-500 mb-1">Nama</label>
    <input
      type="text"
      name="nama"
      value="<?= htmlspecialchars($_GET['nama'] ?? '') ?>"
      class="border rounded px-3 py-2 text-sm"
      placeholder="Cari nama">
  </div>

  <!-- FILTER NIM -->
  <div>
    <label class="block text-xs text-gray-500 mb-1">NIM / NIP</label>
    <input
      type="text"
      name="nim"
      value="<?= htmlspecialchars($_GET['nim'] ?? '') ?>"
      class="border rounded px-3 py-2 text-sm"
      placeholder="Cari NIM/NIP">
  </div>

  <!-- FILTER ROLE -->
  <div>
    <label class="block text-xs text-gray-500 mb-1">Role</label>
    <select name="role" class="border rounded px-3 py-2 text-sm">
      <option value="">Semua</option>
      <?php foreach (['admin','mahasiswa','dosen','alumni', 'mitra'] as $r): ?>
        <option value="<?= $r ?>"
          <?= ($_GET['role'] ?? '') === $r ? 'selected' : '' ?>>
          <?= ucfirst($r) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- BUTTON -->
  <div class="flex gap-2">
    <button
      type="submit"
      class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
      Filter
    </button>

    <a href="?tab=users"
       class="bg-gray-200 px-4 py-2 rounded text-sm hover:bg-gray-300">
      Reset
    </a>
  </div>

</form>

  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">Nama</th>
        <th class="p-3 text-left">Email</th>
        <th class="p-3 text-left">Role</th>
        <th class="p-3 text-left">NIM/NIP</th>
        <th class="p-3 text-left">Aksi</th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($users as $u): ?>
  <tr class="border-t align-middle">

    <!-- NAMA -->
    <td class="p-3 font-medium text-gray-800">
      <?= htmlspecialchars($u['NAMA']) ?>
    </td>

    <!-- EMAIL -->
    <td class="p-3 text-gray-600">
      <?= htmlspecialchars($u['EMAIL']) ?>
    </td>

    <!-- ROLE (EDITABLE) -->
    <td class="p-3">
      <form action="<?= BASE_URL ?>/admin/update-role" method="POST">
        <input type="hidden" name="user_id" value="<?= $u['USER_ID'] ?>">

        <select name="role"
          onchange="this.form.submit()"
          class="text-xs rounded px-2 py-1 border
            <?= $u['ROLE_NAME']=='admin'
              ? 'border-indigo-300 bg-indigo-50 text-indigo-700'
              : 'border-green-300 bg-green-50 text-green-700' ?>">

          <?php foreach (['admin','mahasiswa','dosen','alumni', 'mitra'] as $r): ?>
            <option value="<?= $r ?>" <?= $u['ROLE_NAME']===$r?'selected':'' ?>>
              <?= ucfirst($r) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </td>

    <!-- NIM / NIP -->
    <td class="p-3 text-gray-600">
      <?= $u['NIM'] ?? $u['NIP'] ?? '-' ?>
    </td>

    <!-- AKSI -->
    <td class="p-3 flex items-center gap-4 text-sm">

      <!-- SUSPEND / ACTIVATE -->
      <?php if (strtolower($u['STATUS']) === 'active'): ?>
        <a href="<?= BASE_URL ?>/admin/toggle-status?id=<?= $u['USER_ID'] ?>&status=suspended"
           onclick="return confirm('Suspend user ini?')"
           class="text-orange-600 hover:underline font-medium">
          Suspend
        </a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>/admin/toggle-status?id=<?= $u['USER_ID'] ?>&status=active"
           class="text-green-600 hover:underline font-medium">
          Activate
        </a>
      <?php endif; ?>

      <!-- DELETE -->
      <a href="<?= BASE_URL ?>/admin/delete?id=<?= $u['USER_ID'] ?>"
         onclick="return confirm('Hapus user ini permanen?')"
         class="text-red-600 hover:underline font-medium">
        Delete
      </a>

    </td>
  </tr>
<?php endforeach ?>

</tbody>
  </table>
  <?php
  $query = $_GET;
  $query['tab'] = 'users'; // pastikan tab tetap
  $range = 2; // jumlah halaman kiri-kanan page aktif
  $start = max(1, $page - $range);
  $end   = min($totalPages, $page + $range);
?>

  <div class="flex justify-center mt-6 gap-1 items-center text-sm">

  <!-- PREV -->
  <?php if ($page > 1): ?>
  <a href="?<?= http_build_query(array_merge($query, ['page' => $page - 1])) ?>"
     class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
    «
  </a>
  <?php endif; ?>


  <!-- FIRST PAGE -->
  <?php if ($start > 1): ?>
    <a href="?<?= http_build_query(array_merge($query, ['page' => 1])) ?>"
      class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
        1
    </a>

    <span class="px-2 text-gray-500">…</span>
  <?php endif; ?>

  <!-- PAGE WINDOW -->
  <?php for ($i = $start; $i <= $end; $i++): ?>
  <a href="?<?= http_build_query(array_merge($query, ['page' => $i])) ?>"
     class="px-3 py-1 rounded
     <?= $i == $page
        ? 'bg-indigo-600 text-white'
        : 'bg-gray-200 hover:bg-gray-300' ?>">
    <?= $i ?>
  </a>
  <?php endfor; ?>


  <!-- LAST PAGE -->
  <?php if ($end < $totalPages): ?>
    <span class="px-2 text-gray-500">…</span>
      <a href="?<?= http_build_query(array_merge($query, ['page' => $totalPages])) ?>"
        class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
        <?= $totalPages ?>
      </a>
  <?php endif; ?>

  <!-- NEXT -->
  <?php if ($page < $totalPages): ?>
  <a href="?<?= http_build_query(array_merge($query, ['page' => $page + 1])) ?>"
     class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
    »
  </a>
  <?php endif; ?>
  </div>
</div>
