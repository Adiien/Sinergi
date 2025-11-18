<?php
// File: coba4/views/partials/post_card.php

$post_id = $post['POST_ID'];
$nama = htmlspecialchars($post['NAMA'] ?? 'Nama User');
$content = nl2br(htmlspecialchars($post['CONTENT'] ?? '...'));
$likeCount = $post['LIKE_COUNT'] ?? 0;
$commentCount = $post['COMMENT_COUNT'] ?? 0;
$handle = '@' . strtolower(str_replace(' ', '', $nama));
$initial = strtoupper(substr($nama, 0, 1));
$timestamp = isset($post['CREATED_AT']) ? date('d M Y, H:i', strtotime($post['CREATED_AT'])) : '';
?>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-5 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center text-white font-bold text-xl">
                <span><?php echo $initial; ?></span>
            </div>
            <div>
                <h3 class="font-bold text-gray-900"><?php echo $nama; ?>
                    <?php
                    // Logika Badge Gaya Admin Panel
                    $role = strtolower($post['ROLE_NAME'] ?? 'mahasiswa');

                    // Tentukan warna berdasarkan role
                    $badgeClass = 'bg-green-100 text-green-800'; // Default (Mahasiswa)
                    if ($role == 'admin') {
                        $badgeClass = 'bg-indigo-100 text-indigo-800';
                    } elseif ($role == 'dosen') {
                        $badgeClass = 'bg-blue-100 text-blue-800'; // Sedikit beda untuk Dosen
                    }
                    ?>

                    <span class="ml-1 px-2 inline-flex text-[10px] leading-5 font-semibold rounded-full <?php echo $badgeClass; ?>">
                        <?php echo ucfirst($role); ?>
                    </span>
                </h3>
                <p class="text-sm text-gray-500">
                    <?php echo $handle; ?>
                    <?php if ($timestamp): ?>
                        <span class="text-xs text-gray-400">&middot; <?php echo $timestamp; ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="relative" data-menu-container>
            <button type="button"
                class="post-menu-button text-gray-400 hover:text-gray-600 p-1 rounded-full"
                data-target-menu-id="<?php echo $post_id; ?>">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </button>

            <div id="post-menu-<?php echo $post_id; ?>"
                class="post-menu-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">

                <?php
                // Tampilkan tombol Hapus HANYA jika:
                // 1. User login DAN
                // 2. USER_ID postingan == USER_ID sesi ATAU role sesi adalah 'admin'
                if (
                    isset($_SESSION['user_id']) &&
                    (
                        $post['USER_ID'] == $_SESSION['user_id'] ||
                        (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin')
                    )
                ):
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
                    <img src="<?= BASE_URL ?>/public/assets/images/Report.png" alt="Report Icon" class="w-5 h-5 mr-2">
                    Report
                </button>

            </div>
        </div>
    </div>

    <div class="px-5 pb-5">
        <p class="text-gray-800 text-base">
            <?php echo $content; ?>
        </p>
    </div>

    <div class="px-5 pb-4 text-sm text-gray-500 border-b">
        <span class="like-count" data-post-id="<?php echo $post_id; ?>"><?php echo $likeCount; ?></span> Likes
        - <span><?php echo $commentCount; ?> Comments</span>
    </div>

    <div class="p-3 flex flex-col space-y-3">

        <div class="flex space-x-2 ">

            <button type="button"
                data-post-id="<?php echo $post_id; ?>"
                class="like-button flex-1 flex justify-center items-center space-x-1 text-gray-600 hover:text-blue-500 font-medium px-3 py-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.25a.75.75 0 01-.75-.75V10.5c0-.414.336-.75.75-.75h1.383z" />
                </svg>
                <span>Like</span>
            </button>

            <button type="button"
                class="comment-toggle-button flex-1 flex justify-center items-center space-x-1 text-gray-600 hover:text-indigo-600 font-medium px-3 py-2 rounded-lg hover:bg-gray-100"
                data-post-id="<?php echo $post_id; ?>">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443h2.282c1.584 0 2.863-1.39 2.863-3.227V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                </svg>
                <span>Comment</span>
            </button>
        </div>

        <div id="comments-section-<?php echo $post_id; ?>" class="pt-3 hidden">

            <form action="<?= BASE_URL ?>/post/comment" method="POST" class="flex space-x-2">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="text" name="content" placeholder="Tulis komentar..."
                    class="w-full bg-gray-100 border-none rounded-lg p-2 text-sm focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    Kirim
                </button>
            </form>

            <div class="mt-4 space-y-3 h-50 overflow-y-auto pr-2">
                <?php
                // Kita hanya perlu satu variabel ini, yang dijamin ada oleh HomeController
                $comments_list = $post['comments_list'] ?? [];
                ?>

                <?php if (!empty($comments_list)): ?>
                    <?php foreach ($comments_list as $comment): ?>
                        <?php
                        $commenter_name = htmlspecialchars($comment['NAMA'] ?? 'User');
                        $comment_content = nl2br(htmlspecialchars($comment['CONTENT'] ?? '...'));
                        $comment_initial = strtoupper(substr($commenter_name, 0, 1));
                        ?>

                        <div class="flex items-start space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold text-sm flex-shrink-0">
                                <span><?php echo $comment_initial; ?></span>
                            </div>
                            <div class="flex-1 bg-gray-100 rounded-xl p-3">
                                <h4 class="text-sm font-bold text-gray-900"><?php echo $commenter_name; ?></h4>
                                <p class="text-sm text-gray-700">
                                    <?php echo $comment_content; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500 text-center py-2">Belum ada komentar.</p>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>