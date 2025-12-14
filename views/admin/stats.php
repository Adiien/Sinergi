<?php
// Guard kecil biar ga berisik kalau controller lupa kirim data
$stats = $stats ?? [
    'total_users'  => 0,
    'active_users' => 0,
    'total_forums' => 0,
    'total_posts'  => 0,
];
?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

  <!-- Total User -->
  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-gray-500 text-sm mb-1">Total User</p>
    <p class="text-3xl font-bold text-gray-900">
      <?= (int)$stats['total_users'] ?>
    </p>
  </div>

  <!-- User Aktif -->
  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-gray-500 text-sm mb-1">User Aktif</p>
    <p class="text-3xl font-bold text-green-600">
      <?= (int)$stats['active_users'] ?>
    </p>
  </div>

  <!-- Total Forum -->
  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-gray-500 text-sm mb-1">Total Forum</p>
    <p class="text-3xl font-bold text-indigo-600">
      <?= (int)$stats['total_forums'] ?>
    </p>
  </div>

  <!-- Total Post -->
  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-gray-500 text-sm mb-1">Total Post</p>
    <p class="text-3xl font-bold text-purple-600">
      <?= (int)$stats['total_posts'] ?>
    </p>
  </div>

</div>
