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
                    <button type="button" onclick="document.getElementById('shareForumModal').classList.remove('hidden')"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl flex items-center transition shadow-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Share
                    </button>

                    <?php if ($isMember): ?>
                        <button onclick="document.getElementById('inviteModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl flex items-center transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Invite
                        </button>
                    <?php endif; ?>

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

                    <nav class="flex flex-col py-2">
                        <a href="<?= BASE_URL ?>/forum/show?id=<?= $forum['FORUM_ID'] ?>&view=about"
                            class="flex items-center px-6 py-4 transition <?= $activeTab == 'about' ? $tabActive : $tabInactive ?>">
                            About
                        </a>
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

                <?php if (isset($accessDenied) && $accessDenied): ?>

                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 text-center px-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">This Forum is Private</h3>
                        <p class="text-gray-500 mb-8 max-w-md">
                            Join this community to view posts, discussions, and member lists.
                        </p>

                        <form action="<?= BASE_URL ?>/forum/join" method="POST">
                            <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                Join Community
                            </button>
                        </form>
                    </div>

                <?php else: ?>
                    <?php if ($activeTab == 'about'): ?>

                        <div class="bg-white rounded-2xl shadow-sm p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="font-bold text-gray-900 text-2xl">About this Group</h3>
                                <?php if (isset($forum['VISIBILITY'])): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?= strtolower($forum['VISIBILITY']) == 'private' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' ?>">
                                        <?= ucfirst($forum['VISIBILITY']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="prose max-w-none text-gray-600 leading-relaxed text-lg mb-8">
                                <?= !empty($forum['DESCRIPTION']) ? nl2br(htmlspecialchars($forum['DESCRIPTION'])) : 'Welcome to the group! No description provided yet.' ?>
                            </div>

                            <div class="pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Created At</span>
                                        <span class="text-sm font-medium text-gray-800">
                                            <?= isset($forum['CREATED_AT']) ? date('d M Y', strtotime($forum['CREATED_AT'])) : '-' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Total Members</span>
                                        <span class="text-sm font-medium text-gray-800"><?= $forum['MEMBER_COUNT'] ?? 0 ?> People</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($activeTab == 'members'): ?>

                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-900 text-xl mb-6 flex items-center">
                                Members <span class="ml-2 bg-gray-100 text-gray-600 text-sm py-0.5 px-2.5 rounded-full"><?= isset($members) ? count($members) : 0 ?></span>
                            </h3>
                            <?php if (empty($members)): ?>
                                <p class="text-gray-500 text-center py-10">Belum ada anggota.</p>
                            <?php else: ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php foreach ($members as $m): ?>
                                        <?php
                                        $m_initial = strtoupper(substr($m['NAMA'], 0, 1));
                                        $sys_role = strtolower($m['ROLE_NAME'] ?? 'member');
                                        $is_creator = ($m['USER_ID'] == $forum['CREATED_BY']);

                                        if ($is_creator) {
                                            $display_label = 'Group Admin';
                                            $badge_color = 'bg-purple-100 text-purple-700 border border-purple-200';
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
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm shrink-0"><?= $m_initial ?></div>
                                            <div class="ml-4 flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-900 text-sm truncate"><?= htmlspecialchars($m['NAMA']) ?></h4>
                                                <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-lg mt-1 inline-block <?= $badge_color ?>"><?= $display_label ?></span>
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
                                    <?php if (isset($_SESSION['nama'])): ?><?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?><?php else: ?>?<?php endif; ?>
                                </div>
                                <div id="create-post-trigger" class="flex-1 bg-gray-100 hover:bg-gray-200 transition rounded-full px-5 py-3 text-gray-500 cursor-pointer text-sm font-medium">Write something to the group...</div>
                                <button class="text-gray-400 hover:text-indigo-600 transition p-2 hover:bg-gray-100 rounded-full"><img src="<?= BASE_URL ?>/public/assets/image/postpict.png" class="w-6 h-6"></button>
                            </div>
                        <?php else: ?>
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg></div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">Anda harus bergabung dengan forum ini untuk membuat postingan.</p>
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
                                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                    </svg></div>
                                <h3 class="text-xl font-bold text-gray-900">No posts yet</h3>
                                <p class="text-gray-500 text-sm mt-1">Be the first to share something in this group!</p>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                <?php endif; // [AKHIR MODIFIKASI] End If Access Denied 
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

    <div id="inviteModal" class="fixed inset-0 z-50 overflow-y-auto bg-[#5e5e8f]/60 backdrop-blur-sm hidden flex items-center justify-center transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-xl font-bold text-gray-800">Invite Member</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 p-2 rounded-full"
                    onclick="document.getElementById('inviteModal').classList.add('hidden')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="<?= BASE_URL ?>/forum/invite" method="POST">
                <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">

                <div class="mb-6 relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Search User</label>

                    <input type="hidden" name="target_user_id" id="target_user_id">

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>

                        <input type="text" name="username" id="invite_search" autocomplete="off" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-800 bg-gray-50 focus:bg-white"
                            placeholder="Type name or NIM/NIP...">

                        <div id="search_loading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div id="invite_suggestions" class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 max-h-60 overflow-y-auto hidden">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                        onclick="document.getElementById('inviteModal').classList.add('hidden')">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Send Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="shareForumModal" class="fixed inset-0 z-50 overflow-y-auto bg-[#5e5e8f]/60 backdrop-blur-sm hidden flex items-center justify-center transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative transform transition-all scale-100">

            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-xl font-bold text-gray-800">Share to Feed</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition bg-gray-50 hover:bg-gray-100 p-2 rounded-full"
                    onclick="document.getElementById('shareForumModal').classList.add('hidden')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="<?= BASE_URL ?>/post/share-forum" method="POST">
                <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">

                <div class="mb-5 p-3 border border-gray-200 rounded-xl bg-gray-50 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold shrink-0 overflow-hidden">
                        <?php if (!empty($forum['COVER_IMAGE'])): ?>
                            <img src="<?= BASE_URL ?>/public/uploads/forums/<?= $forum['COVER_IMAGE'] ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?= strtoupper(substr($forum['NAME'], 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="font-bold text-gray-900 text-sm truncate"><?= htmlspecialchars($forum['NAME']) ?></h4>
                        <p class="text-xs text-gray-500"><?= $forum['MEMBER_COUNT'] ?> Members</p>
                    </div>
                </div>

                <div class="mb-6 relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Write a Caption</label>
                    <div class="relative">
                        <span class="absolute top-3.5 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </span>
                        <textarea name="content" rows="3"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-gray-800 bg-gray-50 focus:bg-white resize-none"
                            placeholder="Why should people join this group?"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                        onclick="document.getElementById('shareForumModal').classList.add('hidden')">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Share Now
                    </button>
                </div>
            </form>
        </div>
    </div>

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
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById('invite_search');
            const suggestionsBox = document.getElementById('invite_suggestions');
            const targetIdInput = document.getElementById('target_user_id');
            const loadingIcon = document.getElementById('search_loading');
            let debounceTimer;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    // 1. Reset hidden ID jika user mengetik ulang
                    targetIdInput.value = '';

                    // 2. Jika input terlalu pendek (misal kurang dari 2 karakter), sembunyikan saran
                    if (query.length < 2) {
                        suggestionsBox.classList.add('hidden');
                        return;
                    }

                    // 3. Tampilkan loading
                    loadingIcon.classList.remove('hidden');

                    // 4. Debounce: Tunggu 300ms setelah user berhenti mengetik sebelum memanggil API
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        // Lakukan request AJAX ke API '/api/search/users'
                        fetch(`<?= BASE_URL ?>/api/search/users?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(users => {
                                suggestionsBox.innerHTML = '';
                                loadingIcon.classList.add('hidden'); // Sembunyikan loading

                                if (users.length > 0) {
                                    suggestionsBox.classList.remove('hidden');
                                    users.forEach(user => {
                                        // Buat elemen tampilan user di dropdown
                                        const div = document.createElement('div');
                                        div.className = 'px-4 py-3 hover:bg-indigo-50 cursor-pointer flex items-center border-b border-gray-50 last:border-0 transition group';

                                        // Tentukan inisial dan warna Role (untuk tampilan)
                                        const initial = user.NAMA.charAt(0).toUpperCase();
                                        const roleColor = user.ROLE_NAME === 'dosen' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600';

                                        div.innerHTML = `
                                            <div class="w-8 h-8 ${roleColor} rounded-full flex items-center justify-center font-bold text-xs mr-3">
                                                ${initial}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-700">${user.NAMA}</p>
                                                <p class="text-xs text-gray-500">${user.ROLE_NAME} &bull; ${user.NIM || user.NIP || '-'}</p>
                                            </div>
                                        `;

                                        // Handler saat saran diklik: isi input dan simpan ID
                                        div.addEventListener('click', () => {
                                            searchInput.value = user.NAMA;
                                            targetIdInput.value = user.USER_ID;
                                            suggestionsBox.classList.add('hidden');
                                        });

                                        suggestionsBox.appendChild(div);
                                    });
                                } else {
                                    // Jika tidak ada hasil
                                    suggestionsBox.innerHTML = `
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                            User not found.
                                        </div>
                                    `;
                                    suggestionsBox.classList.remove('hidden');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                loadingIcon.classList.add('hidden');
                            });
                    }, 300); // Delay 300ms
                });

                // Sembunyikan saran jika user klik di luar kotak pencarian
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.classList.add('hidden');
                    }
                });
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