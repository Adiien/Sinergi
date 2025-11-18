    <nav id="main-nav" class="bg-[#36364c] p-4 shadow-lg fixed top-0 w-full z-40">
      <div
        class="container mx-auto px-6 py-3 flex justify-between items-center">
        <a href="<?= BASE_URL ?>/home" class="flex items-center space-x-1">
          <div class="p-0.5">
            <img src="<?= BASE_URL ?>/public/assets/images/LOGOSINERGIBORDER.png" alt="Logo" class="w-10 h-10" />
          </div>
          <span class="text-white text-xl tracking-widest font-azeret">SINERGI</span>
        </a>

        <div class="relative w-1/3">
          <input
            type="text"
            class="bg-gray-100 rounded-lg py-2 px-4 pl-10 w-full focus:outline-none"
            placeholder="Search...." />
          <svg
            class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
          </svg>
        </div>

        <div class="flex items-center space-x-6">
          <a
            href="<?= BASE_URL ?>/home"
            class="text-white font-semibold border-b-2 border-white pb-1">Home</a>
          <a href="#" class="text-gray-300 hover:text-white">Discussion</a>
          <?php if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin'): ?>
            <a href="<?= BASE_URL ?>/admin" class="text-yellow-400 hover:text-white font-bold pb-1">
              Admin Panel
            </a>
          <?php endif; ?>
          <a
            href="#"
            class="text-gray-300 hover:text-white">
            <img src="<?= BASE_URL ?>/public/assets/images/MessageIcon.png" alt="Messages" class="w-6 h-6" />
          </a>
          <a
            href="#"
            class="text-gray-300 hover:text-white">
            <img src="<?= BASE_URL ?>/public/assets/images/NotifIcon.png" alt="Notifications" class="w-6 h-6" />
          </a>

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
                href="#"
                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">
                <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                My Profile
              </a>
              <a
                href="#"
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
    <script src="<?= BASE_URL ?>/public/assets/js/profiledropdown.js"></script>