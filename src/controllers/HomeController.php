<?php
// Impor model baru Anda
require_once 'src/models/Post.php';
require_once 'src/models/User.php'; // (Jika diperlukan untuk data user lain)

class HomeController
{

    private $postModel;
    private $userModel;
    private $conn;

    public function __construct()
    {
        // Buat koneksi
        $this->conn = koneksi_oracle();
        // Buat instance dari model-model
        $this->postModel = new Post($this->conn);
        $this->userModel = new User($this->conn);
    }

    public function index()
    {
        // Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $posts = [];
        $myPostCount = 0;
        try {
            $current_user_id = $_SESSION['user_id'];

            $myPostCount = $this->postModel->getPostCountByUserId($current_user_id);
            $followStats = $this->userModel->getFollowStats($current_user_id);
            $followerCount = $followStats['followers'];
            $followingCount = $followStats['following'];

            // 2. [BARU] Ambil saran teman (3 orang)
            $suggestedUsers = $this->userModel->getSuggestedUsers($current_user_id, 3);

            // [UPDATE] Kirim $current_user_id ke model
            $posts = $this->postModel->getFeedPosts($current_user_id);

            // 2. Ambil ID dari postingan yang didapat
            $post_ids = array_column($posts, 'POST_ID');

            if (!empty($post_ids)) {
                // 3. Ambil semua komentar untuk post_ids tsb (1 kueri)
                $all_comments_raw = $this->postModel->getCommentsForPosts($post_ids);

                // 4. Petakan komentar ke ID postingan agar mudah dicari
                $comments_by_post_id = [];
                foreach ($all_comments_raw as $comment) {
                    // $comment['POST_ID'] adalah kuncinya
                    $comments_by_post_id[$comment['POST_ID']][] = $comment;
                }

                // 5. Lampirkan daftar komentar ke setiap post
                //    Kita gunakan & (reference) agar array $posts asli berubah
                foreach ($posts as $key => &$post) {
                    $current_post_id = $post['POST_ID'];

                    if (isset($comments_by_post_id[$current_post_id])) {
                        // Jika ada komentar, lampirkan
                        $posts[$key]['comments_list'] = $comments_by_post_id[$current_post_id];
                    } else {
                        // Jika tidak ada, lampirkan array kosong
                        $posts[$key]['comments_list'] = [];
                    }
                }
                unset($post); // Hapus referensi setelah loop selesai
            }
        } catch (Exception $e) {
            // Tangani error jika gagal mengambil post
            $_SESSION['error_message'] = 'Gagal memuat feed: ' . $e->getMessage();
        }

        // 6. Kirim data $posts (yang kini berisi 'comments_list') ke view
        require 'views/home/index.php';
    }
}
