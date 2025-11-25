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
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $content = $_POST['content'];
                $image_path = null;

                // --- LOGIKA UPLOAD GAMBAR ---
                if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'public/uploads/posts/';

                    // Buat folder jika belum ada
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileTmpPath = $_FILES['post_image']['tmp_name'];
                    $fileName = $_FILES['post_image']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    // Validasi Ekstensi
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($fileExtension, $allowedExtensions)) {
                        // Generate nama unik agar tidak bentrok
                        $newFileName = uniqid('post_') . '.' . $fileExtension;
                        $dest_path = $uploadDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $image_path = $newFileName; // Simpan nama file saja
                        } else {
                            throw new Exception('Gagal mengupload gambar.');
                        }
                    } else {
                        throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    }
                }
                // --- END LOGIKA UPLOAD ---

                if (empty($content) && empty($image_path)) {
                    $_SESSION['error_message'] = 'Postingan (teks atau gambar) tidak boleh kosong.';
                } else {
                    // Update pemanggilan model untuk mengirim image_path
                    if ($this->postModel->createPost($user_id, $content, $image_path)) {
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
     * [UPDATE] Memproses penghapusan postingan DAN file gambar
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
        $is_admin = (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin');

        try {
            // [LANGKAH 1] Ambil data post dulu untuk cek gambar & validasi manual
            $post = $this->postModel->getPostById($post_id_to_delete);

            if (!$post) {
                $_SESSION['error_message'] = 'Postingan tidak ditemukan.';
                header('Location: ' . BASE_URL . '/home');
                exit;
            }

            // [LANGKAH 2] Cek Hak Akses (Manual Check)
            // Kita lakukan di sini sebelum menghapus file fisik
            if (!$is_admin && $post['USER_ID'] != $current_user_id) {
                $_SESSION['error_message'] = 'Anda tidak memiliki hak akses untuk menghapus postingan ini.';
                header('Location: ' . BASE_URL . '/home');
                exit;
            }

            // [LANGKAH 3] Hapus File Fisik (Jika ada gambarnya)
            if (!empty($post['IMAGE_PATH'])) {
                // Path harus sesuai dengan tempat Anda menyimpan di fungsi create()
                // create() menyimpan di: 'public/uploads/posts/'
                $filePath = 'public/uploads/posts/' . $post['IMAGE_PATH'];

                // Cek apakah file benar-benar ada di server
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file
                }
            }

            // [LANGKAH 4] Hapus Record dari Database
            // Kita tetap memanggil fungsi ini untuk keamanan transaksi DB
            if ($this->postModel->deletePostById($post_id_to_delete, $current_user_id, $is_admin)) {
                $_SESSION['success_message'] = 'Postingan dan file berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Gagal menghapus data dari database.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
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
        // Hapus buffer output sebelumnya untuk mencegah sampah HTML
        if (ob_get_level()) ob_end_clean();

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'No ID provided']);
            exit;
        }

        try {
            $post_id = (int)$_GET['id'];
            $user_id = $_SESSION['user_id'];

            $result = $this->postModel->toggleLike($post_id, $user_id);

            echo json_encode([
                'success' => true,
                'isLiked' => $result['isLiked'],
                'newLikeCount' => $result['newLikeCount']
            ]);
        } catch (Exception $e) {
            // Kirim error JSON, bukan HTML
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
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

    /**
     * Endpoint API untuk AJAX Polling
     */
    public function getUpdates()
    {
        // Set header agar browser tahu ini data JSON
        header('Content-Type: application/json');

        // Ambil data JSON yang dikirim oleh JavaScript
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['post_ids']) || empty($input['post_ids'])) {
            echo json_encode([]);
            exit;
        }

        try {
            // Minta data terbaru ke Model
            $updates = $this->postModel->getPostStats($input['post_ids']);
            echo json_encode($updates);
        } catch (Exception $e) {
            // Jika error, kirim array kosong agar JS tidak crash
            echo json_encode([]);
        }
        exit;
    }
}
