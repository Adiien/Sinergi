<?php
$activeUsers  = $activeUsers  ?? [];
$activeForums = $activeForums ?? [];
?>

<div class="grid grid-cols-12 gap-6">

  <!-- HEADER -->
  <div class="col-span-12 flex items-center justify-between">
    <h1 class="text-xl font-bold">Overview Report</h1>

    <div class="flex bg-gray-100 rounded-lg p-1 text-xs">
      <button
        onclick="showUsers()"
        id="btnUsers"
        class="px-3 py-1 rounded-md bg-white shadow-sm text-indigo-600 font-medium">
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
  <div class="col-span-12 lg:col-span-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4">

    <!-- USER LIST -->
    <div id="usersList">
      <?php foreach ($activeUsers as $index => $u): ?>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">

          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
              <?= $index === 0 ? 'bg-yellow-100 text-yellow-700'
                 : ($index === 1 ? 'bg-gray-200 text-gray-700'
                 : ($index === 2 ? 'bg-orange-100 text-orange-700'
                 : 'bg-indigo-100 text-indigo-600')) ?>">
              <?= $index < 3 ? '#' . ($index + 1) : strtoupper(substr($u['NAMA'], 0, 1)) ?>
            </div>

            <div>
              <p class="text-sm font-medium"><?= htmlspecialchars($u['NAMA']) ?></p>
              <p class="text-xs text-gray-500">User aktif</p>
            </div>
          </div>

          <span class="text-xs text-gray-400">
            <?= (int)$u['TOTAL_POSTS'] ?> post
          </span>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- FORUM LIST -->
    <div id="forumsList" class="hidden">
      <?php foreach ($activeForums as $index => $f): ?>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">

          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
              <?= $index === 0 ? 'bg-yellow-100 text-yellow-700'
                 : ($index === 1 ? 'bg-gray-200 text-gray-700'
                 : ($index === 2 ? 'bg-orange-100 text-orange-700'
                 : 'bg-green-100 text-green-600')) ?>">
              <?= $index < 3 ? '#' . ($index + 1) : '#' ?>
            </div>

            <div>
              <p class="text-sm font-medium"><?= htmlspecialchars($f['NAME']) ?></p>
              <p class="text-xs text-gray-500">Forum aktif</p>
            </div>
          </div>

          <span class="text-xs text-gray-400">
            <?= (int)$f['TOTAL_POSTS'] ?> post
          </span>

        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- PANEL KANAN -->
  <div class="col-span-12 lg:col-span-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">

  <h2 class="text-sm font-semibold text-gray-700 mb-4">
    Traffic Aktivitas
  </h2>

  <div class="space-y-3">

    <?php
    // ambil max post buat skala bar
    $maxPosts = 1;
    foreach ($activeUsers as $u) {
        $maxPosts = max($maxPosts, (int)$u['TOTAL_POSTS']);
    }
    ?>

    <?php foreach ($activeUsers as $u): 
      $percent = round(($u['TOTAL_POSTS'] / $maxPosts) * 100);
    ?>
      <div>
        <div class="flex justify-between text-xs text-gray-500 mb-1">
          <span><?= htmlspecialchars($u['NAMA']) ?></span>
          <span><?= (int)$u['TOTAL_POSTS'] ?> post</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
          <div
            class="bg-indigo-500 h-2 rounded-full"
            style="width: <?= $percent ?>%">
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>

  </div>


</div>

<div class="col-span-12 bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-3xl font-bold"><?= $stats['total_users'] ?></p>
    </div>  

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Forums</p>
        <p class="text-3xl font-bold"><?= $stats['total_forums'] ?></p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Posts</p>
        <p class="text-3xl font-bold"><?= $stats['total_posts'] ?></p>
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

  btnUsers.classList.add('bg-white','shadow-sm','text-indigo-600');
  btnForums.classList.remove('bg-white','shadow-sm','text-indigo-600');
  btnForums.classList.add('text-gray-500');
}

function showForums() {
  forumsList.classList.remove('hidden');
  usersList.classList.add('hidden');

  btnForums.classList.add('bg-white','shadow-sm','text-indigo-600');
  btnUsers.classList.remove('bg-white','shadow-sm','text-indigo-600');
  btnUsers.classList.add('text-gray-500');
}
</script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>

