<?php
// Impor model baru Anda
require_once 'src/models/Post.php';
require_once 'src/models/User.php'; // (Jika diperlukan untuk data user lain)
    
function buildCommentTree($comments_raw) {
    $comments_by_id = [];
    $comment_tree = [];

    // 1. Indeks komentar berdasarkan COMMENT_ID
    foreach ($comments_raw as $comment) {
        $comment['REPLIES'] = []; // Inisialisasi array balasan
        // Pastikan key cocok dengan hasil query (huruf besar)
        $comments_by_id[$comment['COMMENT_ID']] = $comment;
    }

    // 2. Bangun pohon
    foreach ($comments_by_id as $id => &$comment) {
        // Jika memiliki parent ID (bukan komentar utama)
        if ($comment['PARENT_COMMENT_ID'] !== null) {
            $parentId = $comment['PARENT_COMMENT_ID'];
            
            // Masukkan komentar saat ini ke dalam array REPLIES milik parent
            if (isset($comments_by_id[$parentId])) {
                $comments_by_id[$parentId]['REPLIES'][] = &$comment;
            } else {
                // FALLBACK: Jika parent tidak ditemukan, perlakukan sebagai komentar utama
                $comment_tree[] = &$comment;
            }
        } else {
            // Jika tidak memiliki parent ID, itu adalah komentar utama (root)
            $comment_tree[] = &$comment;
        }
    }
    unset($comment); // Hapus reference untuk menghindari bug

    return $comment_tree;
}

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
                $raw_comments_by_post_id = [];
                foreach ($all_comments_raw as $comment) {
                    $raw_comments_by_post_id[$comment['POST_ID']][] = $comment;
                }

// 5. [UPDATE] Konversi komentar datar menjadi struktur bersarang (tree)
//    Dan lampirkan hasil 'tree' ke setiap post
                foreach ($posts as $key => &$post) {
                    $current_post_id = $post['POST_ID'];

                    if (isset($raw_comments_by_post_id[$current_post_id])) {
                        // Gunakan fungsi yang baru ditambahkan
                        $comment_tree = buildCommentTree($raw_comments_by_post_id[$current_post_id]); 
                        $posts[$key]['comments_list'] = $comment_tree;
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
