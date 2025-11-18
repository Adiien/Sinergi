<?php
require_once 'src/models/Post.php';

class PostController
{

    private $postModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->postModel = new Post($this->conn);
    }

    /**
     * Memproses pembuatan post baru
     */
    public function create()
    {
        // Pastikan user login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $content = $_POST['content'];

                if (empty($content)) {
                    $_SESSION['error_message'] = 'Postingan tidak boleh kosong.';
                } else {
                    if ($this->postModel->createPost($user_id, $content)) {
                        $_SESSION['success_message'] = 'Postingan berhasil dibuat!';
                    } else {
                        $_SESSION['error_message'] = 'Gagal membuat postingan.';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }
        }

        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    /**
     * [BARU] Memproses penghapusan postingan
     */
    public function delete()
    {
        // 1. Keamanan: Pastikan user login
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Anda harus login untuk menghapus postingan.';
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 2. Validasi: Pastikan ID postingan ada
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = 'ID postingan tidak valid.';
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        $post_id_to_delete = (int)$_GET['id'];
        $current_user_id = (int)$_SESSION['user_id'];
        
        // Cek apakah user adalah admin
        $is_admin = (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin');

        // 3. Proses Hapus
        try {
            // Panggil model
            if ($this->postModel->deletePostById($post_id_to_delete, $current_user_id, $is_admin)) {
                $_SESSION['success_message'] = 'Postingan berhasil dihapus.';
            } else {
                // Ini terjadi jika query-nya jalan tapi 0 baris terhapus
                // (misal, pengguna mencoba hapus post orang lain & dia bukan admin)
                $_SESSION['error_message'] = 'Gagal menghapus postingan. Anda mungkin tidak memiliki hak akses.';
            }
        } catch (Exception $e) {
            // Tangkap error database (misal, foreign key constraint jika ada)
            $_SESSION['error_message'] = 'Error Database: ' . $e->getMessage();
        }

        // 4. Kembali ke halaman home
        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    /**
     * Memproses like/unlike (Versi AJAX)
     */
    public function like()
    {
        // Set header ke JSON
        header('Content-Type: application/json');

        // Pastikan user login
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in.']);
            exit;
        }

        // Pastikan post_id ada
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid post ID.']);
            exit;
        }

        try {
            $post_id = (int)$_GET['id'];
            $user_id = $_SESSION['user_id'];

            // Panggil model, yang sekarang mengembalikan array
            $result = $this->postModel->toggleLike($post_id, $user_id);

            // Kirim balasan sukses
            echo json_encode([
                'success' => true,
                'isLiked' => $result['isLiked'],
                'newLikeCount' => $result['newLikeCount']
            ]);
            exit;
        } catch (Exception $e) {
            // Kirim balasan error
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Memproses penambahan komentar
     */
    public function comment()
    {
        // Pastikan user login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $post_id = $_POST['post_id'];
                $content = $_POST['content'];

                if (empty($content)) {
                    $_SESSION['error_message'] = 'Komentar tidak boleh kosong.';
                } elseif (empty($post_id) || !is_numeric($post_id)) {
                    $_SESSION['error_message'] = 'Postingan tidak valid.';
                } else {
                    if ($this->postModel->addComment($post_id, $user_id, $content)) {
                        $_SESSION['success_message'] = 'Komentar ditambahkan!';
                    } else {
                        $_SESSION['error_message'] = 'Gagal menambahkan komentar.';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }
        }

        header('Location: ' . BASE_URL . '/home');
        exit;
    }
}
