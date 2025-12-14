<?php
require_once 'src/models/User.php';
require_once 'src/models/Post.php';
require_once 'src/helpers/AuthGuard.php';

class ProfileController
{
    private $userModel;
    private $postModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
        $this->postModel = new Post($this->conn);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $current_login_id = $_SESSION['user_id'];

        // 1. Tentukan ID siapa yang mau dilihat
        $target_user_id = isset($_GET['id']) ? $_GET['id'] : $current_login_id;
        $is_own_profile = ($target_user_id == $current_login_id);

        // 2. Ambil Data User Target
        $targetUser = $this->userModel->getUserById($target_user_id);
        if (!$targetUser) {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 3. Cek Status Follow (Khusus jika melihat profil orang lain)
        $is_following = false;
        if (!$is_own_profile) {
            // Query manual cek status follow
            $query = "SELECT 1 FROM follows WHERE follower_id = :me AND following_id = :target";
            $stmt = oci_parse($this->conn, $query);
            oci_bind_by_name($stmt, ':me', $current_login_id);
            oci_bind_by_name($stmt, ':target', $target_user_id);
            oci_execute($stmt);
            if (oci_fetch($stmt)) {
                $is_following = true;
            }
            oci_free_statement($stmt);
        }

        // 4. Ambil Statistik & Postingan
        $followStats = $this->userModel->getFollowStats($target_user_id);

        // Postingan Content (Milik target_user, dilihat oleh current_login_id)
        $contentPosts = $this->postModel->getPostsByAuthor($target_user_id, $current_login_id);

        // Postingan Activity (Interaksi target_user, dilihat oleh current_login_id)
        $activityPosts = $this->postModel->getActivityPosts($target_user_id, $current_login_id);

        // --- Logika Penempelan Komentar (Copy-Paste Logika Sebelumnya) ---
        $all_posts_temp = array_merge($contentPosts, $activityPosts);
        $post_ids = [];
        foreach ($all_posts_temp as $p) $post_ids[] = $p['POST_ID'];
        $post_ids = array_unique($post_ids);

        if (!empty($post_ids)) {
            $all_comments_raw = $this->postModel->getCommentsForPosts(array_values($post_ids), $current_login_id);
            $comments_by_post_id = [];
            foreach ($all_comments_raw as $comment) $comments_by_post_id[$comment['POST_ID']][] = $comment;

            foreach ($contentPosts as &$post) $post['comments_list'] = $comments_by_post_id[$post['POST_ID']] ?? [];
            unset($post);
            foreach ($activityPosts as &$post) $post['comments_list'] = $comments_by_post_id[$post['POST_ID']] ?? [];
            unset($post);
        }

        // 5. Kirim Data ke View
        $data = [
            'user_id' => $target_user_id,
            'is_own_profile' => $is_own_profile, // Penentu Tampilan
            'is_following' => $is_following,     // Status Follow
            'nama' => $targetUser['NAMA'],
            'email' => $targetUser['EMAIL'],
            'role' => $targetUser['ROLE_NAME'] ?? 'Member',
            'followers' => $followStats['followers'],
            'following' => $followStats['following'],
            'content_posts' => $contentPosts,
            'activity_posts' => $activityPosts
        ];

        require 'views/profile/index.php';
    }
}
