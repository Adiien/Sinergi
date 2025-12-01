<?php
require_once 'src/models/Forum.php';
// Pastikan model Post juga di-load jika belum ada di autoloader
require_once 'src/models/Post.php';

class ForumController
{
    private $forumModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->forumModel = new ForumModel($this->conn);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $myForums = $this->forumModel->getForumsByCreator($_SESSION['user_id']);
        $joinedForums = $this->forumModel->getUserJoinedForums($_SESSION['user_id']);

        require 'views/forum/index.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $visibility = $_POST['visibility'] ?? 'public';
                $creator_id = $_SESSION['user_id'];
                $cover_image = null;

                if (empty($name)) {
                    $_SESSION['error_message'] = 'Gagal: Nama forum wajib diisi!';
                    header('Location: ' . BASE_URL . '/forum');
                    exit;
                }

                $newForumId = $this->forumModel->createForum($name, $description, $visibility, $creator_id, $cover_image);

                if ($newForumId) {
                    $_SESSION['success_message'] = 'Forum berhasil dibuat!';
                    // Redirect langsung ke forum yang baru dibuat
                    header('Location: ' . BASE_URL . '/forum/show?id=' . $newForumId);
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Gagal menyimpan ke database.';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Database Error: ' . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/forum');
            exit;
        }
    }

    // --- PERBAIKAN: Fungsi show() harus ada DI DALAM class ---
    public function show()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $forum_id = $_GET['id'] ?? null;
        if (!$forum_id) {
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        // Load Post Model
        // (Pastikan path file benar. Jika class Post ada di src/models/Post.php)
        if (!class_exists('Post')) {
            require_once 'src/models/Post.php';
        }
        $postModel = new Post($this->conn);

        // Get Data
        $forum = $this->forumModel->getForumById($forum_id);

        // Cek jika forum tidak ditemukan
        if (!$forum) {
            $_SESSION['error_message'] = "Forum tidak ditemukan.";
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        $isMember = $this->forumModel->isMember($forum_id, $_SESSION['user_id']);

        // Get Posts for this forum
        // Pastikan Anda sudah menambahkan kolom 'forum_id' di tabel 'posts' database Anda
        $posts = $postModel->getPostsByForum($forum_id, $_SESSION['user_id']);

        // Attach comments (Logic copy dari HomeController)
        $post_ids = array_column($posts, 'POST_ID');
        if (!empty($post_ids)) {
            $all_comments = $postModel->getCommentsForPosts($post_ids, $_SESSION['user_id']);
            $comments_by_post = [];
            foreach ($all_comments as $c) {
                $comments_by_post[$c['POST_ID']][] = $c;
            }
            foreach ($posts as &$p) {
                $p['comments_list'] = $comments_by_post[$p['POST_ID']] ?? [];
            }
        }

        require 'views/forum/show.php';
    }
    public function ajaxSearch()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        // [BARU] Ambil limit dari parameter, default 5
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

        if (empty($keyword)) {
            echo json_encode([]);
            exit;
        }

        try {
            // Panggil model dengan limit dinamis
            $results = $this->forumModel->searchForums($keyword, $limit);
            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode([]);
        }
        exit;
    }
}
