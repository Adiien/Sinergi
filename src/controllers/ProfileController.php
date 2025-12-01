<?php
require_once 'src/models/User.php';
require_once 'src/models/Post.php';

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
        // Cek Login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // 1. Ambil data statistik (Followers/Following)
        $followStats = $this->userModel->getFollowStats($user_id);
        
        // 2. Ambil Data CONTENT (Postingan Sendiri)
        $contentPosts = $this->postModel->getPostsByAuthor($user_id, $user_id);

        // 3. Ambil Data ACTIVITY (Postingan yang di-Interaksi)
        $activityPosts = $this->postModel->getActivityPosts($user_id, $user_id);

        // --- [LOGIKA PENEMPELAN KOMENTAR UNTUK SEMUA POST] ---
        
        // Gabungkan kedua array post untuk mengambil semua ID-nya sekaligus (efisiensi query)
        $all_posts_temp = array_merge($contentPosts, $activityPosts);
        
        $post_ids = [];
        foreach ($all_posts_temp as $p) {
            $post_ids[] = $p['POST_ID'];
        }
        // Hapus duplikat ID
        $post_ids = array_unique($post_ids);

        if (!empty($post_ids)) {
            // Ambil semua komentar dari database untuk ID-ID tersebut
            $all_comments_raw = $this->postModel->getCommentsForPosts(array_values($post_ids), $_SESSION['user_id']);

            // Kelompokkan komentar berdasarkan POST_ID agar mudah dicocokkan
            $comments_by_post_id = [];
            foreach ($all_comments_raw as $comment) {
                $comments_by_post_id[$comment['POST_ID']][] = $comment;
            }

            // 1. Tempelkan komentar ke array $contentPosts (Tab Content)
            foreach ($contentPosts as &$post) {
                $pid = $post['POST_ID'];
                // Jika ada komentar untuk post ini, masukkan. Jika tidak, array kosong.
                $post['comments_list'] = $comments_by_post_id[$pid] ?? [];
            }
            unset($post); // Putus referensi

            // 2. Tempelkan komentar ke array $activityPosts (Tab Activity)
            foreach ($activityPosts as &$post) {
                $pid = $post['POST_ID'];
                $post['comments_list'] = $comments_by_post_id[$pid] ?? [];
            }
            unset($post); // Putus referensi
        }
        // --- [AKHIR LOGIKA] ---

        // Siapkan data untuk View
        $data = [
            'user_id' => $user_id,
            'nama' => $_SESSION['nama'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role_name'] ?? 'Member',
            'followers' => $followStats['followers'],
            'following' => $followStats['following'],
            'content_posts' => $contentPosts, // Sekarang sudah punya comments_list
            'activity_posts' => $activityPosts // Sekarang sudah punya comments_list
        ];

        require 'views/profile/index.php';
    }
}