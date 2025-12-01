<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        /* Animasi Tab */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-[#eff3f8] pt-32 pb-10">

    <?php require_once 'views/partials/navbar.php'; ?>

    <main class="container mx-auto px-4 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 text-center relative">
                    <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </button>

                    <div class="w-32 h-32 bg-[#4ade80] rounded-full flex items-center justify-center text-white text-6xl font-bold mx-auto mb-4">
                        <?php echo strtoupper(substr($data['nama'], 0, 1)); ?>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($data['nama']); ?></h2>
                    <p class="text-sm text-blue-500 font-medium">@<?php echo strtolower(str_replace(' ', '', $data['nama'])); ?></p>
                    <p class="text-xs text-gray-500 mt-1">Member at SINERGI</p>

                    <button class="mt-6 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 w-full transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Social Link</span>
                    </button>

                    <div class="flex justify-center mt-8 border-t border-gray-100 pt-6">
                        <div class="px-6 text-center border-r border-gray-100">
                            <span class="block text-sm text-gray-500">Followers</span>
                            <span class="block text-lg font-bold text-gray-800"><?php echo $data['followers']; ?></span>
                        </div>
                        <div class="px-6 text-center">
                            <span class="block text-sm text-gray-500">Following</span>
                            <span class="block text-lg font-bold text-gray-800"><?php echo $data['following']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="grid grid-cols-3 text-center text-sm font-medium">
                        <button onclick="switchTab('about')" id="tab-btn-about" class="py-4 border-b-2 border-blue-600 text-blue-600 hover:bg-gray-50 transition">
                            About
                        </button>
                        <button onclick="switchTab('activity')" id="tab-btn-activity" class="py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition">
                            Activity
                        </button>
                        <button onclick="switchTab('content')" id="tab-btn-content" class="py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition">
                            Content
                        </button>
                    </div>
                </div>

                <div id="tab-content-about" class="tab-content active space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                        <div class="mb-4 flex justify-center text-gray-700">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">About me</h3>
                        <p class="text-sm text-gray-500 mb-6 px-4 leading-relaxed">
                            Personalize your profile to reflect who you are and make it easier for others to connect with you on Sinergi.
                        </p>
                        <button class="border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center mx-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Write about me
                        </button>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                        <div class="mb-4 flex justify-center text-gray-700">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Education</h3>
                        <p class="text-sm text-gray-500 mb-6 px-4 leading-relaxed">
                            Enhance your profile by adding your educational history.
                        </p>
                        <button class="border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 flex items-center mx-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Education
                        </button>
                    </div>
                </div>

                <div id="tab-content-activity" class="tab-content space-y-6">
                    <?php if (!empty($data['activity_posts'])): ?>
                        <?php foreach ($data['activity_posts'] as $post): ?>
                            <?php
                            // Gunakan partial post_card yang sudah ada agar tampilan konsisten
                            // Variable $post akan otomatis dipakai oleh post_card.php
                            include 'views/partials/post_card.php';
                            ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl shadow-sm p-10 text-center">
                            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No recent activity</h3>
                            <p class="text-gray-500 text-sm mt-2">Posts you liked or commented on will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div id="tab-content-content" class="tab-content space-y-6">
                    <?php if (!empty($data['content_posts'])): ?>
                        <?php foreach ($data['content_posts'] as $post): ?>
                            <?php include 'views/partials/post_card.php'; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl shadow-sm p-10 text-center">
                            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No posts yet</h3>
                            <p class="text-gray-500 text-sm mt-2">Share your thoughts with the world.</p>
                            <a href="<?= BASE_URL ?>/home" class="inline-block mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700">Create Post</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-[#36364c] rounded-2xl shadow-sm p-6 text-white flex items-center justify-center min-h-[100px]">
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-bold">My Impact</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Groups</h3>

                    <div class="flex space-x-6 border-b border-gray-100 mb-4">
                        <button class="text-blue-600 font-bold border-b-2 border-blue-600 pb-2 text-sm">Admin</button>
                        <button class="text-gray-500 font-medium hover:text-gray-800 pb-2 text-sm">Member</button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 font-medium">Group name</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 font-medium">Group name</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script src="<?= BASE_URL ?>/public/assets/js/profiledropdown.js"></script>

    <script>
        function switchTab(tabName) {
            // 1. Sembunyikan semua konten
            const allContents = document.querySelectorAll('.tab-content');
            allContents.forEach(el => el.classList.remove('active'));

            // 2. Reset style semua tombol tab (jadi abu-abu)
            const allButtons = document.querySelectorAll('[id^="tab-btn-"]');
            allButtons.forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // 3. Tampilkan konten yang dipilih
            const selectedContent = document.getElementById('tab-content-' + tabName);
            if (selectedContent) selectedContent.classList.add('active');

            // 4. Highlight tombol yang dipilih (jadi biru)
            const selectedBtn = document.getElementById('tab-btn-' + tabName);
            if (selectedBtn) {
                selectedBtn.classList.remove('border-transparent', 'text-gray-500');
                selectedBtn.classList.add('border-blue-600', 'text-blue-600');
            }
        }
    </script>
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>/public/assets/js/LikeToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/CommentToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/FollowToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/Report.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/PostMenu.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/profiledropdown.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/CommentAjax.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>


</body>

</html>