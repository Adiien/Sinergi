<?php
$stats        = $stats ?? [];
$activeUsers  = $activeUsers ?? [];
$activeForums = $activeForums ?? [];
?>

<div class="grid grid-cols-12 gap-6">

  <!-- HEADER -->
  <div class="col-span-12 flex items-center justify-between">
    <h1 class="text-xl font-bold">Overview Report</h1>

    <div class="flex bg-gray-100 rounded-lg p-1 text-sm">
      <button
        onclick="showUsers()"
        id="btnUsers"
        class="px-3 py-1 rounded-md bg-white shadow text-indigo-600 font-medium">
        User Aktif
      </button>
      <button
        onclick="showForums()"
        id="btnForums"
        class="px-3 py-1 rounded-md text-gray-500">
        Forum Aktif
      </button>
    </div>
  </div>

  <!-- AKTIVITAS CARD -->
  <div class="col-span-12 lg:col-span-4 bg-white rounded-xl shadow p-4">

    <!-- USER LIST -->
    <div id="usersList">
      <?php foreach ($activeUsers as $index => $u): ?>
        <div class="flex items-center justify-between py-3 border-b last:border-b-0">

          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600
                        flex items-center justify-center text-sm font-bold">
              <?= strtoupper(substr($u['NAMA'], 0, 1)) ?>
            </div>

            <div>
              <p class="text-sm font-medium"><?= htmlspecialchars($u['NAMA']) ?></p>
              <p class="text-xs text-gray-500">
                <?= $index === 0 ? 'Paling aktif' : 'User aktif' ?>
              </p>
            </div>
          </div>

          <span class="text-xs text-gray-600">
            <?= (int)$u['TOTAL_POSTS'] ?> post
          </span>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- FORUM LIST -->
    <div id="forumsList" class="hidden">
      <?php foreach ($activeForums as $index => $f): ?>
        <div class="flex items-center justify-between py-3 border-b last:border-b-0">

          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-green-100 text-green-600
                        flex items-center justify-center text-sm font-bold">
              #
            </div>

            <div>
              <p class="text-sm font-medium"><?= htmlspecialchars($f['NAME']) ?></p>
              <p class="text-xs text-gray-500">
                <?= $index === 0 ? 'Forum teraktif' : 'Forum aktif' ?>
              </p>
            </div>
          </div>

          <span class="text-xs text-gray-600">
            <?= (int)$f['TOTAL_POSTS'] ?> post
          </span>

        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- AREA KANAN (CHART / KPI) -->
  <div class="col-span-12 lg:col-span-8 bg-white rounded-xl shadow p-6 flex items-center justify-center text-gray-400">
    Statistik Aktivitas (Chart nanti)
  </div>

</div>

<script>
const usersList  = document.getElementById('usersList');
const forumsList = document.getElementById('forumsList');
const btnUsers   = document.getElementById('btnUsers');
const btnForums  = document.getElementById('btnForums');

function showUsers() {
  usersList.classList.remove('hidden');
  forumsList.classList.add('hidden');

  btnUsers.classList.add('bg-white','shadow','text-indigo-600');
  btnForums.classList.remove('bg-white','shadow','text-indigo-600');
  btnForums.classList.add('text-gray-500');
}

function showForums() {
  forumsList.classList.remove('hidden');
  usersList.classList.add('hidden');

  btnForums.classList.add('bg-white','shadow','text-indigo-600');
  btnUsers.classList.remove('bg-white','shadow','text-indigo-600');
  btnUsers.classList.add('text-gray-500');
}
</script>
