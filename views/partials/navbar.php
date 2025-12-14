 <?php
  // Cek URL
  $current_uri = $_SERVER['REQUEST_URI'];
  $is_home = strpos($current_uri, '/home') !== false;
  $is_message = strpos($current_uri, '/messages') !== false;
  $is_forum = strpos($current_uri, '/forum') !== false;

  // 1. Style untuk TEKS (Home) - Tetap pakai Underline
  $activeText = 'text-white font-semibold border-b-2 border-white pb-1';
  $inactiveText = 'text-gray-300 hover:text-white border-b-2 border-transparent pb-1 transition-all';

  // 2. Style untuk IKON (Messages) - Pakai Background & Rounded
  // Aktif: Ada background putih transparan, rounded
  $activeIcon = 'bg-white/20 text-white rounded-lg p-1.5 shadow-inner transition-all';
  // Tidak Aktif: Transparan, tapi hover ada efek sedikit
  $inactiveIcon = 'text-gray-300 hover:bg-white/10 hover:text-white rounded-lg p-1.5 transition-all';
  ?>
 <nav id="main-nav" class="bg-[#36364c] h-20 shadow-lg fixed top-0 w-full z-40">
   <div
     class="container mx-auto px-6 py-3 flex justify-between items-center">
     <a href="<?= BASE_URL ?>/home" class="flex items-center space-x-1">
       <div class="p-0.5">
         <img src="<?= BASE_URL ?>/public/assets/image/LOGOSINERGIBORDER.png" alt="Logo" class="w-10 h-10" />
       </div>
       <span class="text-white text-xl tracking-widest font-azeret">SINERGI</span>
     </a>

     <div class="relative w-1/3" id="search-container">

       <div class="relative">
         <input
           type="text"
           id="search-input"
           class="bg-gray-100 rounded-lg py-2 px-4 pl-10 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
           placeholder="Search ..."
           autocomplete="off" />
         <img src="<?= BASE_URL ?>/public/assets/image/SearchIcon.png" alt="Search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2" />

         <div id="search-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
           <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
             <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
             <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
           </svg>
         </div>
       </div>

       <div id="search-dropdown" class="hidden absolute top-full left-0 w-full bg-white rounded-xl shadow-xl mt-2 border border-gray-100 overflow-hidden z-50">
         <div id="search-results-list" class="max-h-80 overflow-y-auto custom-scroll"></div>
       </div>

     </div>

     <script>
       document.addEventListener("DOMContentLoaded", () => {
         const searchInput = document.getElementById('search-input');
         const searchDropdown = document.getElementById('search-dropdown');
         const resultsList = document.getElementById('search-results-list');
         const loadingIcon = document.getElementById('search-loading');
         const searchContainer = document.getElementById('search-container');
         let debounceTimeout = null;

         // --- TEMPLATE RENDER USER ---
         function renderUser(user) {
           const initial = user.NAMA.charAt(0).toUpperCase();
           const handle = '@' + user.NAMA.replace(/\s+/g, '').toLowerCase();

           // Badge Role Logic
           let roleBadge = '';
           const role = (user.ROLE_NAME || 'mahasiswa').toLowerCase();
           if (role === 'dosen') roleBadge = '<span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full ml-2">Dosen</span>';
           else if (role === 'admin') roleBadge = '<span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full ml-2">Admin</span>';

           // Status Follow
           const isFollowing = (user.IS_FOLLOWING > 0);
           let buttonText = "Follow";
           let buttonClass = "border-blue-500 text-blue-500 hover:bg-blue-50";

           if (isFollowing) {
             buttonText = "Following";
             buttonClass = "bg-gray-100 text-gray-500 border-gray-200";
           }

           return `
          <a href="${window.BASE_URL}/profile?id=${user.USER_ID}" class="block">
            <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition cursor-pointer border-b border-gray-50 last:border-none">
              <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                    ${initial}
                </div>
                <div class="min-w-0">
                  <div class="flex items-center">
                      <h4 class="font-bold text-gray-800 text-sm truncate">${user.NAMA}</h4>
                      ${roleBadge}
                  </div>
                  <p class="text-xs text-gray-500 truncate">${handle}</p>
                </div>
              </div>
            </div>
        </a>
        `;
         }

         // --- [BARU] TEMPLATE RENDER FORUM ---
         function renderForum(forum) {
           const initial = forum.NAME.charAt(0).toUpperCase();
           // Gunakan cover image jika ada, jika tidak pakai inisial
           let iconHtml = '';
           if (forum.COVER_IMAGE) {
             iconHtml = `<img src="<?= BASE_URL ?>/public/uploads/forums/${forum.COVER_IMAGE}" class="w-full h-full object-cover">`;
           } else {
             iconHtml = `${initial}`;
           }

           return `
            <a href="<?= BASE_URL ?>/forum/show?id=${forum.FORUM_ID}" class="block">
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition cursor-pointer border-b border-gray-50 last:border-none">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shrink-0 overflow-hidden">
                            ${iconHtml}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">${forum.NAME}</h4>
                            <p class="text-xs text-gray-500 truncate">${forum.MEMBER_COUNT} members</p>
                        </div>
                    </div>
                    
                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-lg font-bold hover:bg-gray-200 transition">
                        View
                    </span>
                </div>
            </a>
            `;
         }

         // Event Listener saat mengetik
         searchInput.addEventListener('input', (e) => {
           const query = e.target.value.trim();

           clearTimeout(debounceTimeout);

           if (query.length === 0) {
             searchDropdown.classList.add('hidden');
             loadingIcon.classList.add('hidden');
             return;
           }

           loadingIcon.classList.remove('hidden');

           debounceTimeout = setTimeout(async () => {
             try {
               // [BARU] Fetch Users DAN Forums secara paralel
               const [usersRes, forumsRes] = await Promise.all([
                 fetch(`<?= BASE_URL ?>/api/search/users?q=${encodeURIComponent(query)}`),
                 fetch(`<?= BASE_URL ?>/api/search/forums?q=${encodeURIComponent(query)}`)
               ]);

               const users = await usersRes.json();
               const forums = await forumsRes.json();

               resultsList.innerHTML = '';

               let hasResults = false;

               // 1. Render FORUMS (Tampilkan paling atas)
               if (forums.length > 0) {
                 hasResults = true;
                 resultsList.innerHTML += `<div class="px-4 py-2 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">Forums</div>`;
                 forums.forEach(forum => {
                   resultsList.innerHTML += renderForum(forum);
                 });
               }

               // 2. Render USERS
               if (users.length > 0) {
                 hasResults = true;
                 // Tambahkan header jika ada hasil forum sebelumnya
                 const mt = forums.length > 0 ? 'mt-2' : '';
                 resultsList.innerHTML += `<div class="px-4 py-2 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider ${mt}">People</div>`;

                 users.forEach(user => {
                   resultsList.innerHTML += renderUser(user);
                 });
               }

               // 3. Handle No Results
               if (!hasResults) {
                 resultsList.innerHTML = `
                        <div class="px-4 py-6 text-center text-gray-500">
                            <p class="text-sm">No results found for "${query}".</p>
                        </div>
                    `;
               }

               searchDropdown.classList.remove('hidden');

             } catch (err) {
               console.error("Search Error:", err);
             } finally {
               loadingIcon.classList.add('hidden');
             }
           }, 300);
         });

         // Tutup dropdown jika klik di luar
         document.addEventListener('click', (e) => {
           if (!searchContainer.contains(e.target)) {
             searchDropdown.classList.add('hidden');
           }
         });

         // Buka kembali dropdown jika input diklik dan ada isinya
         searchInput.addEventListener('focus', () => {
           if (searchInput.value.trim().length > 0) {
             searchDropdown.classList.remove('hidden');
           }
         });
       });
     </script>

     <div class="flex items-center space-x-6">
       <a href="<?= BASE_URL ?>/home"
         class="<?= $is_home ? $activeText : $inactiveText ?>">
         Home
       </a>
       <a href="<?= BASE_URL ?>/forum" class="<?= $is_forum ? $activeText : $inactiveText ?>">Forums</a>
       <?php if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin'): ?>
         <a href="<?= BASE_URL ?>/admin" class="text-yellow-400 hover:text-white font-bold pb-1">
           Admin Panel
         </a>
       <?php endif; ?>
       <a href="<?= BASE_URL ?>/messages"
         class="<?= $is_message ? $activeIcon : $inactiveIcon ?> flex items-center justify-center">
         <img src="<?= BASE_URL ?>/public/assets/image/MessageIcon.png" alt="Messages" class="w-6 h-6" />
       </a>
       <div class="relative" id="notification-container">
         <button id="notification-btn" class="relative text-gray-300 hover:text-white hover:bg-white/10 rounded-lg p-1.5 transition-all focus:outline-none">
           <img src="<?= BASE_URL ?>/public/assets/image/NotifIcon.png" alt="Notifications" class="w-6 h-6" />

         </button>

         <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-[360px] bg-white rounded-xl shadow-2xl z-50 border border-gray-100 overflow-hidden origin-top-right transform transition-all duration-200">

           <div class="absolute top-0 right-3 -mt-2 w-4 h-4 bg-white transform rotate-45 border-t border-l border-gray-100"></div>

           <div class="px-4 py-3 flex justify-between items-center bg-white relative z-10">
             <h3 class="font-bold text-gray-900 text-lg">Notifications</h3>
           </div>

           <div class="bg-white relative z-10 min-h-[100px] flex flex-col items-center justify-center text-center p-6">

             <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
               <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
               </svg>
             </div>

             <p class="text-gray-600 font-medium text-base">No new notifications</p>
           </div>

           <div class="p-3 text-center bg-white border-t border-gray-100 relative z-10">
             <a href="#" class="text-blue-600 font-bold text-sm hover:bg-blue-50 py-2 px-4 rounded-lg transition block">
               See All
             </a>
           </div>
         </div>
       </div>

       <div class="relative" id="profile-dropdown-container">
         <button
           id="profile-dropdown-button"
           class="w-9 h-9 bg-white rounded-full flex items-center justify-center font-bold text-indigo-900 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#36364c] focus:ring-white">
           <?php if (isset($_SESSION['nama'])): ?>
             <span class="text-lg"><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></span>
           <?php else: ?>
             <svg
               class="w-8 h-8 text-gray-400"
               xmlns="http://www.w3.org/2000/svg"
               fill="currentColor"
               viewBox="0 0 24 24">
               <path
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
             </svg>
           <?php endif; ?>
         </button>

         <div
           id="profile-dropdown-menu"
           class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
           role="menu"
           aria-orientation="vertical"
           aria-labelledby="profile-dropdown-button">
           <a
             href="<?= BASE_URL ?>/profile"
             class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
             role="menuitem">
             <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
             </svg>
             My Profile
           </a>
           <a
             href="<?= BASE_URL ?>/settings"
             class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
             role="menuitem">
             <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
               <path d="M13.586 3.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0l-1.5-1.5a2 2 0 010-2.828l3-3z" />
               <path d="M11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
             </svg>
             Edit Setting
           </a>
           <a
             href="#"
             class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
             role="menuitem">
             <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
             </svg>
             My Bin
           </a>
           <a
             href="<?= BASE_URL ?>/logout"
             class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
             role="menuitem">
             <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
             </svg>
             Logout
           </a>
         </div>
       </div>
     </div>
   </div>
 </nav>
 <script>
   window.BASE_URL = "<?= BASE_URL ?>";
 </script>
 <script src="<?= BASE_URL ?>/public/assets/js/profiledropdown.js"></script>