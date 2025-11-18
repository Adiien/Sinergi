  <aside class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-xl shadow-lg p-5 text-center">
          <div
              class="w-20 h-20 bg-green-500 rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold mb-3">
              <?php if (isset($_SESSION['nama'])): ?>
                  <span><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></span>
              <?php else: ?>
                  <span>?</span>
              <?php endif; ?>
          </div>
          <h2 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['nama'] ?? 'Guest User'); ?></h2>
          <p class="text-sm text-gray-500">
              @<?php echo htmlspecialchars($_SESSION['nama'] ?? 'guest.user'); ?>
          </p>
          <?php 
              // Ambil role dari session
              $myRole = strtolower($_SESSION['role_name'] ?? 'mahasiswa');
              
              // Warna badge
              $myBadgeClass = 'bg-green-100 text-green-800';
              if ($myRole == 'admin') {
                  $myBadgeClass = 'bg-indigo-100 text-indigo-800';
              } elseif ($myRole == 'dosen') {
                  $myBadgeClass = 'bg-blue-100 text-blue-800';
              }
              ?>
              
              <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $myBadgeClass; ?>">
                  <?php echo ucfirst($myRole); ?>
              </span>
          <div class="flex justify-around mt-4 pt-4 border-t">
              <div class="text-center">
                  <span class="font-bold text-gray-900">0</span>
                  <p class="text-sm text-gray-500">Followers</p>
              </div>
              <div class="text-center">
                  <span class="font-bold text-gray-900">0</span>
                  <p class="text-sm text-gray-500">Following</p>
              </div>
              <div class="text-center">
                  <span class="font-bold text-gray-900"><?php echo $myPostCount ?? 0; ?></span>
                  <p class="text-sm text-gray-500">Posts</p>
              </div>
          </div>
      </div>
      <div class="bg-white rounded-xl shadow-lg p-5">
          <nav class="space-y-4">
              <a
                  href="#"
                  class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium">
                  <img src="<?= BASE_URL ?>/public/assets/images/GroupIcon.png" alt="Groups" class="w-6 h-6" />
                  <span>Groups</span>
              </a>
              <a
                  href="#"
                  class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium">
                  <img src="<?= BASE_URL ?>/public/assets/images/MessageIconBiru.png" alt="Messages" class="w-6 h-6" />

                  <span>Messages</span>
              </a>
          </nav>
      </div>
  </aside>