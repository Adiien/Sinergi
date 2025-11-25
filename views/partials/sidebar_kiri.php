<aside class="lg:col-span-1 space-y-6 sticky top-32 self-start">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="shrink-0">
                <div class="w-16 h-16 bg-[#4ade80] rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    <?php if (isset($_SESSION['nama'])): ?>
                        <span><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></span>
                    <?php else: ?>
                        <span>?</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="overflow-hidden flex-1">
                <div class="flex items-center flex-wrap gap-x-2">
                    <h2 class="text-lg font-bold text-gray-900 leading-tight break-words">
                        <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Guest User'); ?>
                    </h2>
                </div>

                <p class="text-sm text-blue-400 font-medium truncate mt-0.5">
                    @<?php echo strtolower(str_replace(' ', '', $_SESSION['nama'] ?? 'guest.user')); ?>
                </p>
            </div>
        </div>

        <div class="flex justify-around text-center">
            <div class="flex flex-col">
                <span class="text-gray-500 text-sm mb-1">Followers</span>
                <span class="font-bold text-gray-600 text-lg"><?php echo $followerCount ?? 0; ?></span>
            </div>
            <div class="flex flex-col">
                <span class="text-gray-500 text-sm mb-1">Following</span>
                <span class="font-bold text-gray-600 text-lg"><?php echo $followingCount ?? 0; ?></span>
            </div>
            <div class="flex flex-col">
                <span class="text-gray-500 text-sm mb-1">Posts</span>
                <span class="font-bold text-gray-600 text-lg"><?php echo $myPostCount ?? 0; ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-5">
        <nav class="space-y-4">
            <a href="<?= BASE_URL ?>/forum" class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium">
                <img src="<?= BASE_URL ?>/public/assets/image/GroupIcon.png" alt="Forums" class="w-6 h-6" />
                <span>Forums</span>
            </a>
            <a href="<?= BASE_URL ?>/messages" class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium">
                <img src="<?= BASE_URL ?>/public/assets/image/MessageIconBiru.png" alt="Messages" class="w-6 h-6" />
                <span>Messages</span>
            </a>
        </nav>
    </div>
</aside>