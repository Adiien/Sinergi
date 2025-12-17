<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background-color: #e9effd; } /* Warna background sesuai gambar */
  </style>
</head>
<body class="font-sans">

  <div class="max-w-7xl mx-auto mt-10 flex gap-8 px-4">

    <main class="w-3/4 space-y-6">
      
      <div class="bg-white rounded-2xl shadow-sm p-0 overflow-hidden">
        <div class="px-6 py-3 border-b border-gray-100 flex items-center gap-2">
           <span class="text-sm font-bold text-gray-700">Create Announcement</span>
        </div>
        <div class="p-6">
          <div onclick="openAnnouncementModal()" 
               class="bg-gray-50 rounded-xl p-4 border border-transparent hover:border-blue-200 cursor-pointer transition">
            <p class="text-gray-400">Write here...</p>
          </div>
        </div>
      </div>

    </main>
  </div>

  <div id="announcement-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40" onclick="closeAnnouncementModal()"></div>
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative z-10 overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm uppercase">
        Create Announcement
        </h3>
        <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-700 text-xl">✕</button>
      </div>

      <form method="POST" class="p-6 space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-[#a020f0] text-white flex items-center justify-center rounded-full font-bold">S</div>
          <span class="font-bold text-gray-800 text-sm tracking-wide">SINERGI</span>
        </div>

        <textarea name="content"
          class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
          rows="5"
          placeholder="Write announcement..."></textarea>

          <div class="flex items-center justify-end pt-2">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-bold text-sm transition shadow-md">
                POST
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openAnnouncementModal() {
      document.getElementById('announcement-modal').classList.remove('hidden');
    }

    function closeAnnouncementModal() {
      document.getElementById('announcement-modal').classList.add('hidden');
    }
  </script>
</body>
</html>