<?php
require_once 'src/models/Forum.php';
// Pastikan model Post juga di-load jika belum ada di autoloader
require_once 'src/models/Post.php';
require_once 'src/helpers/AuthGuard.php';

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
        // [BARU] Ambil parameter view, default ke 'feed'
        $view_mode = $_GET['view'] ?? 'feed';

        if (!$forum_id) {
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        if (!class_exists('Post')) {
            require_once 'src/models/Post.php';
        }
        $postModel = new Post($this->conn);

        // 1. Ambil Info Forum
        $forum = $this->forumModel->getForumById($forum_id);

        if (!$forum) {
            $_SESSION['error_message'] = "Forum tidak ditemukan.";
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        $isMember = $this->forumModel->isMember($forum_id, $_SESSION['user_id']);

        // Inisialisasi variabel agar view tidak error
        $posts = [];
        $members = [];

        // [LOGIKA SWITCH VIEW]
        if ($view_mode == 'members') {
            // Ambil Data Member
            $members = $this->forumModel->getForumMembers($forum_id);
        } else {
            // Default: Ambil Data Feed (Postingan)
            $posts = $postModel->getPostsByForum($forum_id, $_SESSION['user_id']);

            // Attach comments
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
    public function join()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $forum_id = $_POST['forum_id'] ?? null;

            if ($forum_id) {
                if ($this->forumModel->addMember($forum_id, $_SESSION['user_id'])) {
                    $_SESSION['success_message'] = "Berhasil bergabung dengan forum!";
                } else {
                    $_SESSION['error_message'] = "Gagal bergabung dengan forum.";
                }
                // Redirect kembali ke halaman forum tersebut
                header('Location: ' . BASE_URL . '/forum/show?id=' . $forum_id);
                exit;
            }
        }

        // Jika bukan POST atau tidak ada ID, kembalikan ke list forum
        header('Location: ' . BASE_URL . '/forum');
        exit;
    }
    // Tampilkan Halaman Settings
    public function settings()
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

        // Ambil data forum
        $forum = $this->forumModel->getForumById($forum_id);

        if (!$forum) {
            $_SESSION['error_message'] = "Forum tidak ditemukan.";
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        // [KEAMANAN] Cek apakah user adalah Creator
        if ($forum['CREATED_BY'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Anda tidak memiliki izin untuk mengedit forum ini.";
            header('Location: ' . BASE_URL . '/forum/show?id=' . $forum_id);
            exit;
        }

        require 'views/forum/settings.php';
    }

    // Proses Update
    public function update()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        $forum_id = $_POST['forum_id'];

        // Cek kepemilikan
        $forum = $this->forumModel->getForumById($forum_id);
        if (!$forum || $forum['CREATED_BY'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Akses ditolak.";
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }

        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $visibility = $_POST['visibility'];
        $cover_image = null;

        // [PERBAIKAN] Cek & Buat Folder jika belum ada
        $uploadDir = 'public/uploads/forums/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] != 4) {
            // Cek Error Upload
            if ($_FILES['cover_image']['error'] != 0) {
                $_SESSION['error_message'] = "Gagal upload gambar. Error Code: " . $_FILES['cover_image']['error'];
                // Code 1 = File terlalu besar (melebihi upload_max_filesize di php.ini)
                if ($_FILES['cover_image']['error'] == 1) {
                    $_SESSION['error_message'] = "Ukuran file terlalu besar (Max: " . ini_get('upload_max_filesize') . ")";
                }
                header('Location: ' . BASE_URL . '/forum/settings?id=' . $forum_id);
                exit;
            }

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['cover_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newName = 'forum_' . uniqid() . '.' . $ext;
                $target = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                    $cover_image = $newName;

                    // Hapus gambar lama
                    if (!empty($forum['COVER_IMAGE']) && file_exists($uploadDir . $forum['COVER_IMAGE'])) {
                        unlink($uploadDir . $forum['COVER_IMAGE']);
                    }
                } else {
                    $_SESSION['error_message'] = "Gagal memindahkan file. Cek permission folder public/uploads/forums";
                    header('Location: ' . BASE_URL . '/forum/settings?id=' . $forum_id);
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Format file tidak didukung.";
                header('Location: ' . BASE_URL . '/forum/settings?id=' . $forum_id);
                exit;
            }
        }

        try {
            $this->forumModel->updateForum($forum_id, $name, $description, $visibility, $cover_image);
            $_SESSION['success_message'] = "Pengaturan forum berhasil disimpan.";
            header('Location: ' . BASE_URL . '/forum/show?id=' . $forum_id);
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Gagal update: " . $e->getMessage();
            header('Location: ' . BASE_URL . '/forum/settings?id=' . $forum_id);
        }
        exit;
    }
}
