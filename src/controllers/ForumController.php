<?php
require_once 'src/models/Forum.php';

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

        $myForums = $this->forumModel->getAllForums();
        $joinedForums = $this->forumModel->getUserJoinedForums($_SESSION['user_id']);

        require 'views/forum/index.php';
    }

    /**
     * [UPDATE] Dengan Debugging Error
     */
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // 1. Ambil Data
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $visibility = $_POST['visibility'] ?? 'public';
                $creator_id = $_SESSION['user_id'];
                $cover_image = null;

                // 2. Validasi
                if (empty($name)) {
                    $_SESSION['error_message'] = 'Gagal: Nama forum wajib diisi!';
                    header('Location: ' . BASE_URL . '/forum');
                    exit;
                }

                // 3. Eksekusi ke Model
                // Pastikan urutan parameter sesuai dengan di Forum.php
                $result = $this->forumModel->createForum($name, $description, $visibility, $creator_id, $cover_image);

                if ($result) {
                    $_SESSION['success_message'] = 'Berhasil! Forum baru telah dibuat.';
                } else {
                    $_SESSION['error_message'] = 'Gagal menyimpan ke database (Unknown Error).';
                }

            } catch (Exception $e) {
                // 4. TANGKAP ERROR DATABASE DISINI
                // Ini akan memberi tahu kita kenapa query gagal (misal: kolom visibility belum ada)
                $_SESSION['error_message'] = 'Database Error: ' . $e->getMessage();
            }
            
            // Redirect kembali
            header('Location: ' . BASE_URL . '/forum');
            exit;
        }
    }
}