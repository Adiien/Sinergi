<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <style>
    /* Sembunyikan scrollbar untuk Chrome, Safari, Opera */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    /* Sembunyikan scrollbar untuk IE, Edge, Firefox */
    .no-scrollbar {
      -ms-overflow-style: none;  /* IE dan Edge */
      scrollbar-width: none;  /* Firefox */
    }
  </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden pt-24">
  <?php
  require_once 'views/partials/navbar.php';
  ?>
  <main id="main-content" class="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6 h-full">
    <?php
    require_once 'views/partials/sidebar_kiri.php';
    ?>


    <div class="lg:col-span-2 space-y-6 h-full overflow-y-auto no-scrollbar">
      <div class="bg-white rounded-xl shadow-lg p-5">
        <div class="flex space-x-4 border-b pb-4 mb-4">
          <button class="font-medium text-gray-700">Create Post</button>
          <button class="font-medium text-gray-500 hover:text-gray-700">
            Create Poll
          </button>
        </div>
        <div>
          <div
            id="create-post-trigger"
            class="w-full flex-1 bg-gray-100 border-none rounded-lg p-3 text-gray-500 cursor-pointer hover:bg-gray-200">
            Write here...
          </div>
        </div>
      </div>
      <?php
      if (isset($posts) && !empty($posts)):
        foreach ($posts as $post):
          require 'views/partials/post_card.php';
        endforeach;
      else:
      ?>
        <div class="bg-white rounded-xl shadow-lg p-5 text-center text-gray-500">
          <p>Belum ada postingan. Jadilah yang pertama!</p>
        </div>
      <?php
      endif;

      ?>
    </div>
    <?php
    require_once 'views/partials/sidebar_kanan.php';
    ?>
  </main>
  <?php // 
  ?>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-red-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['error_message']; ?>
      <?php unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan 
      ?>
    </div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['success_message']; ?>
      <?php unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan 
      ?>
    </div>
  <?php endif; ?>

  <script>
    // Script untuk menyembunyikan alert setelah 3 detik
    setTimeout(function() {
      const alertBox = document.getElementById('alert-box');
      if (alertBox) {
        // Buat transisi fade out
        alertBox.style.transition = 'opacity 0.5s ease-out';
        alertBox.style.opacity = '0';
        // Hapus elemen setelah transisi selesai
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 3000); // 3000 milidetik = 3 detik
  </script>

  <?php // 
  ?>

  <section id="create-post-modal"
    class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50 transition-all duration-300"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-xl relative">

      <div class="flex justify-between items-center border-b pb-3 mb-4">
        <div class="flex items-center space-x-4">
          <button class="flex items-center space-x-2 text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">
            <svg class<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
            <span>Create Post</span>
          </button>
          <button class="flex items-center space-x-2 text-gray-500 hover:text-gray-800 font-medium pb-1">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 019.75 19.875V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span>Create Poll</span>
          </button>
        </div>

        <button id="close-post-modal" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
          <span><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></span>
        </div>
        <div>
          <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['nama']); ?></h4>
          <button class="flex items-center space-x-1 bg-gray-100 rounded-md px-2 py-1 text-xs text-gray-700 hover:bg-gray-200">
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.55a.75.75 0 011.06 0l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zM13.89 5.67a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06zM10 5.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9zM2.75 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM15 9.25a.75.75 0 01.75.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM5.05 16.45a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 010 1.06zM13.89 14.33a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06z" clip-rule="evenodd" />
            </svg>
            <span>Public</span>
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>

      <form action="<?= BASE_URL ?>/post/create" method="POST" class="mt-4">

        <div class="w-full">
          <textarea
            name="content" <?php // <-- Ini adalah satu-satunya data yang akan terkirim 
                            ?>
            class="w-full border-none rounded-lg p-2 focus:ring-0 min-h-[120px]"
            rows="5"
            placeholder="Write here..."></textarea>
        </div>

        <div class="flex justify-between items-center border border-gray-200 rounded-lg p-3 mt-2">
          <span class="text-sm font-medium text-gray-700">Add to your post</span>
          <div class="flex space-x-3">
            <button type="button" class="text-gray-500 hover:text-indigo-600">
              <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25z" />
              </svg>
            </button>
            <button type="button" class="text-gray-500 hover:text-indigo-600">
              <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
              </svg>
            </button>
          </div>
        </div>

        <div class="flex justify-between items-center mt-4">
          <div class="flex items-center space-x-4">
            <button type="button" class="text-gray-500 hover:text-indigo-600" title="Add Title">
              <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25M19.5 19.5L4.5 4.5m0 0V8.25m0-3.75h11.25" />
              </svg>
            </button>
            <label class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer">
              <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500" />
              <span>Add Title</span>
            </label>
            <label class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer">
              <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500" />
              <span>Disable Comments</span>
            </label>
          </div>

          <button type="submit" class="bg-indigo-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-indigo-700 transition duration-300">
            Post
          </button>
        </div>
      </form>

    </div>
  </section>

  <section id="report-modal"
    class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden fixed inset-0 z-50 bg-[#5e5e8f]/50 transition-all duration-300"
    role="dialog" aria-modal="true" aria-labelledby="report-modal-title">

    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-md relative">

      <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h2 id="report-modal-title" class="text-xl font-bold text-gray-900">Laporkan Konten</h2>
        <button id="close-report-modal" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <form id="report-form">
        <p class="text-sm text-gray-700 mb-4">Mengapa Anda melaporkan konten ini?</p>

        <div class="space-y-3">
          <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
            <input type="radio" name="reason" value="Spam" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <span class="text-gray-800 font-medium">Spam</span>
          </label>
          <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
            <input type="radio" name="reason" value="Ujaran Kebencian atau Pelecehan" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <span class="text-gray-800 font-medium">Ujaran Kebencian atau Pelecehan</span>
          </label>
          <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
            <input type="radio" name="reason" value="Informasi Palsu" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <span class="text-gray-800 font-medium">Informasi Palsu</span>
          </label>
          <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
            <input type="radio" name="reason" value="Konten Sensitif atau Tidak Pantas" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <span class="text-gray-800 font-medium">Konten Sensitif atau Tidak Pantas</span>
          </label>
          <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
            <input type="radio" name="reason" value="Lainnya" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
            <span class="text-gray-800 font-medium">Lainnya</span>
          </label>
        </div>

        <input type="hidden" id="report-target-id" name="target_id" value="">
        <input type="hidden" id="report-target-type" name="target_type" value="">

        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" id="cancel-report-button" class="bg-gray-200 text-gray-700 font-semibold py-2 px-5 rounded-lg hover:bg-gray-300 transition duration-300">
            Batal
          </button>
          <button type="submit" class="bg-indigo-600 text-white font-semibold py-2 px-5 rounded-lg hover:bg-indigo-700 transition duration-300">
            Kirim Laporan
          </button>
        </div>
      </form>

    </div>
  </section>
  <script>
    window.BASE_URL = '<?= BASE_URL ?>';
  </script>
  <script src="<?= BASE_URL ?>/public/assets/js/LikeToggle.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/ModalPost.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/CommentToggle.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/Report.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/PostMenu.js"></script>
</body>

</html>