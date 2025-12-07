<?php
// [BARU] Tentukan tab aktif (default: feed)
$activeTab = $_GET['view'] ?? 'feed';

// [BARU] Helper Style untuk Sidebar Tab
$tabActive = 'bg-blue-50 border-l-4 border-blue-600 text-blue-700 font-bold';
$tabInactive = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium border-l-4 border-transparent';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($forum['NAME']) ?> - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff3f8;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="pt-20 pb-10">

    <?php require_once 'views/partials/navbar.php'; ?>

    <div class="bg-white shadow-sm border-b border-gray-200 mb-6">

        <div class="h-48 md:h-48 w-full relative bg-gray-200 overflow-hidden group">
            <?php if (!empty($forum['COVER_IMAGE'])): ?>
                <img src="<?= BASE_URL ?>/public/uploads/forums/<?= $forum['COVER_IMAGE'] ?>"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-black/10"></div> <?php else: ?>
                <div class="w-full h-full bg-white"></div>
            <?php endif; ?>
        </div>

        <div class="container mx-auto px-4 lg:px-8 pb-6">
            <div class="relative -mt-18 flex flex-col md:flex-row items-end md:items-end justify-between gap-6">

                <div class="flex items-end gap-6 w-full md:w-auto relative z-10">

                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-full ring-4 ring-white shadow-xl overflow-hidden bg-white flex-shrink-0 flex items-center justify-center">
                        <div class="w-full h-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-6xl font-extrabold">
                            <?= substr($forum['NAME'], 0, 1) ?>
                        </div>
                    </div>

                    <div class="mb-1 md:mb-3 flex-1 min-w-0">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight truncate" title="<?= htmlspecialchars($forum['NAME']) ?>">
                            <?= htmlspecialchars($forum['NAME']) ?>
                        </h1>
                        <p class="text-gray-500 font-medium mt-1 flex items-center">
                            <?php if (strtolower($forum['VISIBILITY']) == 'private'): ?>
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Private Group
                            <?php else: ?>
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Public Group
                            <?php endif; ?>
                            <span class="mx-2">&bull;</span>
                            <?= $forum['MEMBER_COUNT'] ?> members
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0 md:mb-3">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $forum['CREATED_BY']): ?>
                        <a href="<?= BASE_URL ?>/forum/settings?id=<?= $forum['FORUM_ID'] ?>"
                            class="bg-white/90 hover:bg-white text-gray-700 font-bold py-2.5 px-3 rounded-xl flex items-center transition shadow-sm"
                            title="Forum Settings">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl flex items-center transition shadow-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Share
                    </button>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl flex items-center transition shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Invite
                    </button>

                    <?php if ($isMember): ?>
                        <button class="bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold py-2.5 px-5 rounded-xl flex items-center transition cursor-default">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Joined
                        </button>
                    <?php else: ?>
                        <form action="<?= BASE_URL ?>/forum/join" method="POST">
                            <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">
                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 font-bold py-2.5 px-6 rounded-xl transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Join Group
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <main id="main-content" class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-2 text-lg">About</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?= !empty($forum['DESCRIPTION']) ? htmlspecialchars($forum['DESCRIPTION']) : 'Welcome to the group! No description provided yet.' ?>
                        </p>
                    </div>

                    <nav class="flex flex-col py-2">
                        <a href="<?= BASE_URL ?>/forum/show?id=<?= $forum['FORUM_ID'] ?>&view=feed"
                            class="flex items-center px-6 py-4 transition <?= $activeTab == 'feed' ? $tabActive : $tabInactive ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            Feed
                        </a>

                        <a href="<?= BASE_URL ?>/forum/show?id=<?= $forum['FORUM_ID'] ?>&view=members"
                            class="flex items-center px-6 py-4 transition <?= $activeTab == 'members' ? $tabActive : $tabInactive ?>">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Members
                        </a>

                        <a href="#" class="flex items-center px-6 py-4 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition border-l-4 border-transparent">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Photos
                        </a>
                    </nav>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">

                <?php if ($activeTab == 'members'): ?>

                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 text-xl mb-6 flex items-center">
                            Members
                            <span class="ml-2 bg-gray-100 text-gray-600 text-sm py-0.5 px-2.5 rounded-full">
                                <?= isset($members) ? count($members) : 0 ?>
                            </span>
                        </h3>

                        <?php if (empty($members)): ?>
                            <p class="text-gray-500 text-center py-10">Belum ada anggota.</p>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($members as $m): ?>
                                    <?php
                                    $m_initial = strtoupper(substr($m['NAMA'], 0, 1));

                                    // 1. Ambil Role Sistem (default)
                                    $sys_role = strtolower($m['ROLE_NAME'] ?? 'member');

                                    // 2. Cek apakah ini Pembuat Forum? (Group Admin)
                                    // Pastikan key array $forum['CREATED_BY'] tersedia (dari query getForumById)
                                    $is_creator = ($m['USER_ID'] == $forum['CREATED_BY']);

                                    // 3. Tentukan Label & Warna
                                    if ($is_creator) {
                                        $display_label = 'Group Admin';
                                        $badge_color = 'bg-purple-100 text-purple-700 border border-purple-200'; // Warna Khusus Admin Group
                                    } elseif ($sys_role == 'dosen') {
                                        $display_label = 'Dosen';
                                        $badge_color = 'bg-blue-100 text-blue-700 border border-blue-200';
                                    } elseif ($sys_role == 'admin') {
                                        $display_label = 'System Admin';
                                        $badge_color = 'bg-red-100 text-red-700 border border-red-200';
                                    } else {
                                        $display_label = 'Member';
                                        $badge_color = 'bg-gray-100 text-gray-600 border border-gray-200';
                                    }
                                    ?>

                                    <div class="flex items-center p-4 border border-gray-100 rounded-xl hover:shadow-md transition bg-gray-50">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm shrink-0">
                                            <?= $m_initial ?>
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-bold text-gray-900 text-sm truncate"><?= htmlspecialchars($m['NAMA']) ?></h4>
                                                    <p class="text-xs text-gray-500 mt-0.5">Joined <?= $m['JOINED_DATE'] ?></p>
                                                </div>
                                                <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-lg ml-2 whitespace-nowrap <?= $badge_color ?>">
                                                    <?= $display_label ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>

                    <?php if ($isMember): ?>
                        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold overflow-hidden">
                                <?php if (isset($_SESSION['nama'])): ?>
                                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
                                <?php else: ?>
                                    ?
                                <?php endif; ?>
                            </div>
                            <div id="create-post-trigger" class="flex-1 bg-gray-100 hover:bg-gray-200 transition rounded-full px-5 py-3 text-gray-500 cursor-pointer text-sm font-medium">
                                Write something to the group...
                            </div>
                            <button class="text-gray-400 hover:text-indigo-600 transition p-2 hover:bg-gray-100 rounded-full">
                                <img src="<?= BASE_URL ?>/public/assets/image/postpict.png" class="w-6 h-6">
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Anda harus bergabung dengan forum ini untuk membuat postingan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <?php require 'views/partials/post_card.php'; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">No posts yet</h3>
                            <p class="text-gray-500 text-sm mt-1">Be the first to share something in this group!</p>
                        </div>
                    <?php endif; ?>

                <?php endif; // End If Members vs Feed 
                ?>

            </div>

        </div>
    </main>

    <section id="create-post-modal"
        class="h-screen flex flex-col items-center justify-center pt-2 hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/60 backdrop-blur-sm transition-all duration-300">

        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-xl relative flex flex-col max-h-[90vh] overflow-y-auto custom-scroll">

            <div class="flex justify-between items-center border-b pb-4 mb-4 shrink-0">
                <h3 class="text-lg font-bold text-gray-800">Create Post</h3>
                <button id="close-post-modal" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4 text-sm text-gray-600">
                Posting to <span class="font-bold text-indigo-600"><?= htmlspecialchars($forum['NAME']) ?></span>
            </div>

            <form action="<?= BASE_URL ?>/post/create" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">

                <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">
                <input type="hidden" name="visibility" value="public">

                <input type="file" name="post_images[]" id="post_image_input" class="hidden" accept="image/*" multiple>

                <div class="w-full mb-4">
                    <textarea name="content" class="w-full border border-gray-200 rounded-xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent min-h-[120px] bg-gray-50 focus:bg-white transition resize-none text-base"
                        rows="4" placeholder="What's on your mind?"></textarea>
                </div>

                <div id="custom-media-preview" class="hidden relative w-full bg-gray-50 rounded-xl overflow-hidden border border-gray-200 mb-4 p-2">
                    <div id="preview-grid" class="grid grid-cols-2 gap-2"></div>
                    <button type="button" id="btn-remove-media" class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-md hover:bg-gray-100 text-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex justify-between items-center mt-2 pt-2">
                    <button type="button" id="trigger-upload-btn" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2 px-3 py-2 hover:bg-gray-50 rounded-lg transition">
                        <img src="<?= BASE_URL ?>/public/assets/image/postpict.png" class="w-6 h-6">
                        <span class="text-sm font-medium">Add Photo/Video</span>
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2.5 px-8 rounded-xl hover:bg-indigo-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Post
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const fileInput = document.getElementById('post_image_input');
            const triggerBtn = document.getElementById('trigger-upload-btn');
            const previewArea = document.getElementById('custom-media-preview');
            const gridContainer = document.getElementById('preview-grid');
            const removeBtn = document.getElementById('btn-remove-media');

            if (triggerBtn && fileInput) {
                triggerBtn.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', function() {
                    gridContainer.innerHTML = '';
                    if (this.files.length > 0) {
                        previewArea.classList.remove('hidden');
                        Array.from(this.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'w-full h-32 object-cover rounded-lg border border-gray-200';
                                gridContainer.appendChild(img);
                            }
                            reader.readAsDataURL(file);
                        });
                    }
                });

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        fileInput.value = '';
                        gridContainer.innerHTML = '';
                        previewArea.classList.add('hidden');
                    });
                }
            }
        });
    </script>

    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>/public/assets/js/LikeToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/CommentToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/CommentLikeReply.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/ModalPost.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/Carousel.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/PostMenu.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/Report.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/FollowToggle.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>

</body>

</html>