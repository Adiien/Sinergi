<div class="bg-white rounded-xl shadow p-6">
  <h2 class="text-xl font-bold mb-4">Manajemen Pengguna</h2>

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

          <?php foreach (['admin','mahasiswa','dosen','alumni'] as $r): ?>
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
</div>
