<?php
// File: views/partials/post_card.php

$post_id = $post['POST_ID'];
$nama = htmlspecialchars($post['NAMA'] ?? 'Nama User');
$content = nl2br(htmlspecialchars($post['CONTENT'] ?? '...'));
$imagesString = $post['IMAGE_PATHS'] ?? null;
$allFiles = $imagesString ? explode(',', $imagesString) : [];
$visibility = $post['VISIBILITY'] ?? 'public';
$likeCount = $post['LIKE_COUNT'] ?? 0;
$commentCount = $post['COMMENT_COUNT'] ?? 0;
$handle = '@' . strtolower(str_replace(' ', '', $nama));
$initial = strtoupper(substr($nama, 0, 1));
$timestamp = isset($post['CREATED_AT']) ? date('d M Y, H:i', strtotime($post['CREATED_AT'])) : '';
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$imageList = [];
$docList = [];

foreach ($allFiles as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($ext, $imageExtensions)) {
        $imageList[] = $file;
    } else {
        $docList[] = $file;
    }
}

$isFollowing = ($post['IS_FOLLOWING'] ?? 0) > 0;
$isOwnPost = isset($_SESSION['user_id']) && ($post['USER_ID'] == $_SESSION['user_id']);
?>

<div class="bg-white rounded-xl shadow-lg overflow-hidden post-card mb-6">
    <div class="p-5 flex justify-between items-center">

        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center text-white font-bold text-xl">
                <span><?php echo $initial; ?></span>
            </div>

            <div>
                <h3 class="font-bold text-gray-900 flex items-center flex-wrap">
                    <?php echo $nama; ?>

                    <?php
                    $role = strtolower($post['ROLE_NAME'] ?? 'mahasiswa');
                    $badgeClass = 'bg-green-100 text-green-800';
                    if ($role == 'admin') {
                        $badgeClass = 'bg-indigo-100 text-indigo-800';
                    } elseif ($role == 'dosen') {
                        $badgeClass = 'bg-blue-100 text-blue-800';
                    }
                    ?>
                    <span class="ml-2 px-2 inline-flex text-[10px] leading-5 font-semibold rounded-full <?php echo $badgeClass; ?>">
                        <?php echo ucfirst($role); ?>
                    </span>

                    <?php if (!empty($post['FORUM_ID']) && !empty($post['FORUM_NAME'])): ?>
                        <span class="text-gray-400 mx-2 font-normal text-sm">di</span>
                        <a href="<?= BASE_URL ?>/forum/show?id=<?= $post['FORUM_ID'] ?>"
                            class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm font-bold truncate max-w-[150px]">
                            <?= htmlspecialchars($post['FORUM_NAME']) ?>
                        </a>
                    <?php endif; ?>
                </h3>

                <p class="text-sm text-gray-500">
                    <?php echo $handle; ?>
                    <?php if ($timestamp): ?>
                        <span class="text-xs text-gray-400">&middot; <?php echo $timestamp; ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-3">

            <?php
            // [FITUR BARU] Tombol Follow di Post Card
            // Hanya tampil jika: Belum Follow DAN Bukan Postingan Sendiri
            if (!$isFollowing && !$isOwnPost):
            ?>
                <button
                    data-user-id="<?php echo $post['USER_ID']; ?>"
                    class="follow-button border border-blue-500 text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-full text-xs font-bold transition-colors">
                    Follow
                </button>
            <?php endif; ?>

            <div class="relative" data-menu-container>
                <button type="button"
                    class="post-menu-button text-gray-400 hover:text-gray-600 p-1 rounded-full"
                    data-target-menu-id="<?php echo $post_id; ?>">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </button>

                <div id="post-menu-<?php echo $post_id; ?>"
                    class="post-menu-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-gray-100">

                    <?php
                    // Menu Delete: Hanya untuk Pemilik Post atau Admin
                    if (isset($_SESSION['user_id']) && ($isOwnPost || (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin'))):
                    ?>
                        <a href="<?= BASE_URL ?>/post/delete?id=<?php echo $post_id; ?>"
                            class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini? Tindakan ini tidak dapat dibatalkan.');">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </a>
                    <?php endif; ?>

                    <button type="button"
                        class="report-button flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        data-target-type="post"
                        data-target-id="<?php echo $post_id; ?>">
                        <img src="<?= BASE_URL ?>/public/assets/image/Report.png" alt="Report Icon" class="w-5 h-5 mr-2">
                        Report
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="px-5 pb-5">
        <p class="text-gray-800 text-base break-words"><?php echo $content; ?></p>
    </div>

    <?php if (!empty($imageList)): ?>
        <div class="px-5 pb-4">

            <div class="relative group w-full bg-gray-100 border border-gray-200 rounded-lg overflow-hidden">

                <div id="carousel-<?= $post_id ?>"
                    class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar w-full h-[400px]">

                    <?php foreach ($imageList as $index => $img): ?>
                        <div class="flex-shrink-0 w-full h-full snap-center flex items-center justify-center bg-gray-100 relative">

                            <img src="<?= BASE_URL ?>/public/uploads/posts/<?= htmlspecialchars($img) ?>"
                                class="max-w-full max-h-full object-contain">

                            <?php if (count($imageList) > 1): ?>
                                <div class="absolute top-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full">
                                    <?= $index + 1 ?> / <?= count($imageList) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                </div>
                <?php if (count($imageList) > 1): ?>
                    <button onclick="slideCarousel('<?= $post_id ?>', 'left')"
                        class="absolute top-1/2 left-2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button onclick="slideCarousel('<?= $post_id ?>', 'right')"
                        class="absolute top-1/2 right-2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($docList)): ?>
        <div class="px-5 pb-4 space-y-2">
            <?php foreach ($docList as $doc): ?>
                <?php
                $ext = strtoupper(pathinfo($doc, PATHINFO_EXTENSION));
                // Bersihkan nama file yang random (file_unik_namaasli.pdf) -> namaasli.pdf
                $displayName = preg_replace('/^file_[a-z0-9]+_/', '', $doc);
                ?>
                <a href="<?= BASE_URL ?>/public/uploads/posts/<?= htmlspecialchars($doc) ?>" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition group bg-gray-50">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg mr-3 group-hover:bg-blue-200 group-hover:text-blue-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate group-hover:text-blue-700"><?= htmlspecialchars($displayName) ?></p>
                        <p class="text-xs text-gray-500 uppercase"><?= $ext ?> File</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="px-5 pb-4 text-sm text-gray-500 border-b">
        <span class="like-count" data-post-id="<?php echo $post_id; ?>"><?php echo $likeCount; ?></span> Likes -
        <span class="comment-count" data-post-id="<?php echo $post_id; ?>"><?php echo $commentCount; ?></span> Comments
    </div>

    <div class="p-3 flex flex-col space-y-3">
        <div class="flex space-x-2">
            <?php
            $isLikedByMe = ($post['IS_LIKED'] ?? 0) > 0;
            $likeBtnClass = $isLikedByMe ? 'text-blue-500 font-bold' : 'text-gray-600';
            ?>
            <button type="button" data-post-id="<?php echo $post_id; ?>" class="like-button flex-1 flex justify-center items-center space-x-1 <?php echo $likeBtnClass; ?> hover:bg-gray-100 px-3 py-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.25a.75.75 0 01-.75-.75V10.5c0-.414.336-.75.75-.75h1.383z" />
                </svg>
                <span>Like</span>
            </button>
            <button type="button" class="comment-toggle-button flex-1 flex justify-center items-center space-x-1 text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443h2.282c1.584 0 2.863-1.39 2.863-3.227V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                </svg>
                <span>Comment</span>
            </button>
        </div>

        <div id="comments-section-<?php echo $post_id; ?>" class="hidden comments-section pt-3">
            <form action="<?= BASE_URL ?>/post/comment" method="POST" class="flex space-x-2 mb-4">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="text" name="content" placeholder="Tulis komentar..." class="w-full bg-gray-100 border-none rounded-lg p-2 text-sm focus:ring-indigo-500" autocomplete="off">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Kirim</button>
            </form>

            <div id="comments-list-<?php echo $post_id; ?>" class="space-y-4 max-h-80 overflow-y-auto pr-2 custom-scroll">
                <?php
                // [PENTING] Menggunakan struktur TREE yang baru
                $comments_tree = $post['comments_list'] ?? [];
                ?>

                <?php if (!empty($comments_tree)): ?>
                    <?php foreach ($comments_tree as $comment): ?>
                        <?php
                        $cid = $comment['COMMENT_ID'];
                        $c_nama = htmlspecialchars($comment['NAMA'] ?? 'User');
                        $c_content = nl2br(htmlspecialchars($comment['CONTENT'] ?? ''));
                        $c_initial = strtoupper(substr($c_nama, 0, 1));
                        $c_likes = $comment['LIKE_COUNT'] ?? 0;
                        $c_is_liked = ($comment['IS_LIKED'] ?? 0) > 0;
                        $replies = $comment['REPLIES'] ?? [];
                        ?>

                        <div class="flex items-start space-x-2 group">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xs flex-shrink-0">
                                <?= $c_initial ?>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-100 rounded-2xl rounded-tl-none p-3 inline-block min-w-[150px]">
                                    <h4 class="text-xs font-bold text-gray-900"><?= $c_nama ?></h4>
                                    <p class="text-sm text-gray-800 leading-snug break-all"><?= $c_content ?></p>
                                </div>
                                <div class="flex items-center space-x-4 mt-1 ml-2">
                                    <button class="comment-like-btn text-xs font-semibold <?= $c_is_liked ? 'text-pink-500' : 'text-gray-400 hover:text-pink-500' ?>" data-comment-id="<?= $cid ?>">
                                        Like <span class="comment-like-count"><?= $c_likes > 0 ? $c_likes : '' ?></span>
                                    </button>
                                    <button class="reply-button text-xs font-semibold text-gray-400 hover:text-indigo-600"
                                        data-parent-id="<?= $cid ?>"
                                        data-post-id="<?= $post_id ?>">
                                        Reply
                                    </button>
                                </div>

                                <div id="reply-form-container-<?= $cid ?>" class="mt-2 ml-2"></div>

                                <?php if (!empty($replies)): ?>
                                    <div class="ml-2 pl-2 border-l-2 border-gray-100 mt-2 space-y-2">
                                        <?php foreach ($replies as $reply): ?>
                                            <div class="flex items-start space-x-2">
                                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-[10px] flex-shrink-0">
                                                    <?= strtoupper(substr($reply['NAMA'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="bg-gray-50 rounded-xl p-2 px-3 inline-block">
                                                        <h4 class="text-xs font-bold text-gray-900"><?= htmlspecialchars($reply['NAMA']) ?></h4>
                                                        <p class="text-xs text-gray-700 break-all"><?= nl2br(htmlspecialchars($reply['CONTENT'])) ?></p>
                                                    </div>
                                                    <div class="flex items-center space-x-2 mt-0.5 ml-1">
                                                        <button class="comment-like-btn text-[10px] font-semibold <?= ($reply['IS_LIKED'] ?? 0) ? 'text-pink-500' : 'text-gray-400' ?>" data-comment-id="<?= $reply['COMMENT_ID'] ?>">
                                                            Like <span class="comment-like-count"><?= ($reply['LIKE_COUNT'] ?? 0) > 0 ? $reply['LIKE_COUNT'] : '' ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500 text-center py-2 empty-msg">Belum ada komentar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>