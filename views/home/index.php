<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    /* --- [Sembunyikan Scrollbar Utama] --- */
    html,
    body {
      -ms-overflow-style: none;
      /* IE and Edge */
      scrollbar-width: none;
      /* Firefox */
    }

    html::-webkit-scrollbar,
    body::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari, Opera */
    }

    /* --- [Scrollbar Modal] --- */
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 8px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 8px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      /* IE and Edge */
      scrollbar-width: none;
      /* Firefox */
    }
  </style>
</head>

<body class="bg-gray-100 pt-24 no-scrollbar">

  <?php require_once 'views/partials/navbar.php'; ?>

  <main id="main-content" class="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">

    <?php require_once 'views/partials/sidebar_kiri.php'; ?>

    <div class="lg:col-span-2 space-y-6">

      <div class="bg-white rounded-xl shadow-lg p-5">
        <div class="flex space-x-4 border-b pb-4 mb-4">
          <button class="font-medium text-gray-700">Create Post</button>
          <button class="font-medium text-gray-500 hover:text-gray-700">Create Poll</button>
        </div>
        <div id="create-post-trigger" class="w-full flex-1 bg-gray-100 border-none rounded-lg p-3 text-gray-500 cursor-pointer hover:bg-gray-200">
          Write here...
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
      <?php endif; ?>

    </div>

    <?php require_once 'views/partials/sidebar_kanan.php'; ?>

  </main>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-red-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['error_message']; ?>
      <?php unset($_SESSION['error_message']); ?>
    </div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['success_message']; ?>
      <?php unset($_SESSION['success_message']); ?>
    </div>
  <?php endif; ?>

  <script>
    setTimeout(function() {
      const alertBox = document.getElementById('alert-box');
      if (alertBox) {
        alertBox.style.transition = 'opacity 0.5s ease-out';
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 3000);
  </script>

  <section id="create-post-modal"
    class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50 transition-all duration-300"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-xl relative flex flex-col max-h-[90vh] overflow-y-auto custom-scroll">

      <div class="flex justify-between items-center border-b pb-3 mb-4 shrink-0">
        <div class="flex items-center space-x-4">
          <button class="flex items-center space-x-2 text-indigo-600 font-bold border-b-2 border-indigo-600 pb-1">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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

      <div class="flex items-center space-x-3 shrink-0">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
          <span><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></span>
        </div>
        <div>
          <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['nama']); ?></h4>

          <button type="button" onclick="toggleVisibility()" class="flex items-center space-x-1 bg-gray-100 rounded-md px-2 py-1 text-xs text-gray-700 hover:bg-gray-200 cursor-pointer">
            <svg id="visibilityIcon" class="w-3 h-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.55a.75.75 0 011.06 0l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zM13.89 5.67a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06zM10 5.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9zM2.75 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM15 9.25a.75.75 0 01.75.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM5.05 16.45a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 010 1.06zM13.89 14.33a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06z" clip-rule="evenodd" />
            </svg>

            <span id="visibilityText" class="font-medium">Public</span>

            <svg class="w-3 h-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>

      <form action="<?= BASE_URL ?>/post/create" method="POST" enctype="multipart/form-data" class="mt-4 flex-1 flex flex-col">

        <input type="hidden" name="visibility" id="visibilityInput" value="public">

        <input type="file" name="post_images[]" id="post_image_input" class="hidden" accept="image/*" multiple>

        <input type="file" name="post_files[]" id="post_file_input" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt" multiple>

        <div class="w-full">
          <textarea name="content" class="w-full border-none rounded-lg p-2 focus:ring-0 min-h-[100px]" rows="5" placeholder="Write here..."></textarea>
        </div>

        <div id="custom-media-preview" class="hidden relative w-full bg-gray-50 rounded-lg border border-gray-200 mb-4 p-2">
          <button type="button" id="btn-remove-media" class="absolute top-2 right-2 z-10 bg-white hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-full p-1 shadow-md transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
          <div id="preview-grid" class="grid grid-cols-2 gap-2"></div>
        </div>

        <div class="flex justify-between items-center border border-gray-200 rounded-lg p-3 mt-2 shrink-0">
          <span class="text-sm font-medium text-gray-700">Add to your post</span>
          <div class="flex space-x-3">
            <button type="button" id="trigger-upload-btn" class="text-gray-500 hover:text-indigo-600 transition p-1 hover:bg-gray-100 rounded-full" title="Add Images">
              <img src="<?= BASE_URL ?>/public/assets/image/postpict.png" alt="post pict" class="w-6 h-6" />
            </button>

            <button type="button" id="trigger-file-btn" class="text-gray-500 hover:text-indigo-600 transition p-1 hover:bg-gray-100 rounded-full" title="Add Files">
              <img src="<?= BASE_URL ?>/public/assets/image/postfile.png" alt="post file" class="w-6 h-6" />
            </button>
          </div>
        </div>

        <div class="flex justify-between items-center mt-4 shrink-0">
          <div class="flex items-center space-x-4">
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

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const triggerImgBtn = document.getElementById('trigger-upload-btn');
          const triggerFileBtn = document.getElementById('trigger-file-btn'); // Tombol File

          const imgInput = document.getElementById('post_image_input');
          const fileInput = document.getElementById('post_file_input'); // Input File

          const previewArea = document.getElementById('custom-media-preview');
          const gridContainer = document.getElementById('preview-grid');
          const removeBtn = document.getElementById('btn-remove-media');

          // 1. Handle Klik Tombol
          if (triggerImgBtn) triggerImgBtn.addEventListener('click', () => imgInput.click());
          if (triggerFileBtn) triggerFileBtn.addEventListener('click', () => fileInput.click());

          // Fungsi Render Preview
          function handlePreview(files, isImage) {
            if (files.length > 0) {
              previewArea.classList.remove('hidden');

              Array.from(files).forEach(file => {
                const wrapper = document.createElement('div');
                wrapper.className = "relative group border border-gray-200 rounded-lg overflow-hidden bg-white";

                if (isImage) {
                  const reader = new FileReader();
                  reader.onload = (e) => {
                    wrapper.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover">`;
                    gridContainer.appendChild(wrapper);
                  }
                  reader.readAsDataURL(file);
                } else {
                  // Preview untuk File Dokumen
                  wrapper.innerHTML = `
                        <div class="flex items-center justify-center h-32 bg-gray-50 p-2 text-center">
                            <div>
                                <svg class="w-8 h-8 mx-auto text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs text-gray-600 font-medium truncate max-w-[120px]">${file.name}</p>
                                <p class="text-[10px] text-gray-400">${(file.size/1024).toFixed(1)} KB</p>
                            </div>
                        </div>
                    `;
                  gridContainer.appendChild(wrapper);
                }
              });
            }
          }

          // 2. Listener Change Input
          if (imgInput) {
            imgInput.addEventListener('change', function() {
              handlePreview(this.files, true);
            });
          }

          if (fileInput) {
            fileInput.addEventListener('change', function() {
              handlePreview(this.files, false);
            });
          }

          // 3. Reset Preview
          if (removeBtn) {
            removeBtn.addEventListener('click', () => {
              imgInput.value = '';
              fileInput.value = '';
              gridContainer.innerHTML = '';
              previewArea.classList.add('hidden');
            });
          }
        });
      </script>

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

          <div>
            <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
              <input type="radio" name="reason" value="Lainnya" class="reason-radio focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
              <span class="text-gray-800 font-medium">Lainnya</span>
            </label>

            <div id="other-reason-container" class="hidden mt-2 ml-8 mr-2">
              <textarea
                id="other-reason-text"
                rows="3"
                class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Tuliskan detail alasan laporan Anda..."></textarea>
            </div>
          </div>
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
  <script src="<?= BASE_URL ?>/public/assets/js/RealTime.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/FollowToggle.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/CommentLikeReply.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/Carousel.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>

  <script>
    // GANTI BAGIAN SCRIPT INI DI views/home/index.php
    document.addEventListener("DOMContentLoaded", () => {
      const triggerBtn = document.getElementById('trigger-upload-btn');
      const addMoreBtn = document.getElementById('btn-add-more');
      const fileInput = document.getElementById('post_image_input');

      // Container preview
      const previewArea = document.getElementById('custom-media-preview');
      // Hapus img tag ID 'real-image-preview' yang lama, kita akan buat container baru
      // Ganti struktur HTML previewArea menjadi container grid di kode HTML Anda atau biarkan JS membuatnya

      const removeBtn = document.getElementById('btn-remove-media');

      function openFile() {
        fileInput.click();
      }

      if (triggerBtn) triggerBtn.addEventListener('click', openFile);
      if (addMoreBtn) addMoreBtn.addEventListener('click', openFile);

      if (fileInput) {
        fileInput.addEventListener('change', function() {
          const files = this.files;

          // Bersihkan preview lama (kecuali tombol action)
          // Kita akan sembunyikan img lama dan buat grid baru jika belum ada
          let gridContainer = document.getElementById('preview-grid');
          if (!gridContainer) {
            gridContainer = document.createElement('div');
            gridContainer.id = 'preview-grid';
            gridContainer.className = 'grid grid-cols-2 gap-2 p-2';
            previewArea.appendChild(gridContainer);

            // Sembunyikan img preview tunggal yang lama jika ada
            const oldImg = document.getElementById('real-image-preview');
            if (oldImg) oldImg.style.display = 'none';
          }

          gridContainer.innerHTML = ''; // Reset isi grid

          if (files.length > 0) {
            previewArea.classList.remove('hidden');

            Array.from(files).forEach(file => {
              const reader = new FileReader();
              reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-32 object-cover rounded-lg border border-gray-200';
                gridContainer.appendChild(img);
              }
              reader.readAsDataURL(file);
            });
          }
        });
      }

      if (removeBtn) {
        removeBtn.addEventListener('click', () => {
          fileInput.value = '';
          const grid = document.getElementById('preview-grid');
          if (grid) grid.innerHTML = '';
          previewArea.classList.add('hidden');
        });
      }
    });
  </script>

  <script>
    function toggleVisibility() {
      const input = document.getElementById('visibilityInput');
      const textSpan = document.getElementById('visibilityText');
      const iconSvg = document.getElementById('visibilityIcon');

      // SVG Gembok (Private)
      const lockIcon = '<path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />';

      // SVG Globe (Public)
      const globeIcon = '<path fill-rule="evenodd" d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.55a.75.75 0 011.06 0l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zM13.89 5.67a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 01-1.06 1.06l-1.06-1.06zM10 5.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9zM2.75 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM15 9.25a.75.75 0 01.75.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM5.05 16.45a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 010 1.06zM13.89 14.33a.75.75 0 01-1.06 1.06l-1.06-1.06a.75.75 0 011.06-1.06l1.06 1.06z" clip-rule="evenodd" />';

      if (input.value === 'public') {
        // Ubah ke Private
        input.value = 'private';
        textSpan.textContent = 'Private';
        iconSvg.innerHTML = lockIcon;
      } else {
        // Ubah ke Public
        input.value = 'public';
        textSpan.textContent = 'Public';
        iconSvg.innerHTML = globeIcon;
      }
    }
  </script>
  <template id="reply-form-template">
    <form action="<?= BASE_URL ?>/post/comment" method="POST" class="reply-form flex items-start space-x-2 mt-2 animate-fade-in-up">
      <input type="hidden" name="post_id" value="">
      <input type="hidden" name="parent_id" value="">

      <div class="flex-1">
        <textarea name="content" rows="1" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none" placeholder="Tulis balasan..."></textarea>
      </div>

      <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700 transition">
        Kirim
      </button>
      <button type="button" class="cancel-reply-button text-gray-400 hover:text-red-500 p-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </form>
  </template>
</body>

</html>