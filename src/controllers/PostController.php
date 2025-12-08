<?php
require_once 'src/models/Post.php';
require_once 'src/models/Notification.php';

class PostController
{

    private $postModel;
    private $conn;
    private $notifModel;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->postModel = new Post($this->conn);
        $this->notifModel = new NotificationModel($this->conn);
    }

    /**
     * Memproses pembuatan post baru
     * [UPDATE] Menambahkan logika Visibility
     */
    public function create()
    {
        // Cek apakah request ini AJAX
        $isAjax = isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        if (session_status() == PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $content = $_POST['content'];
                $visibility = $_POST['visibility'] ?? 'public';
                $forum_id = !empty($_POST['forum_id']) ? $_POST['forum_id'] : null;
                // Ambil nilai checkbox 'Turn off commenting'
                // Jika dicentang nilainya 1, jika tidak 0
                $is_comment_disabled = isset($_POST['is_comment_disabled']) ? 1 : 0;

                $poll_options = isset($_POST['poll_options']) ? $_POST['poll_options'] : [];
                // Filter opsi yang kosong
                $poll_options = array_filter($poll_options, function ($value) {
                    return !empty(trim($value));
                });

                // --- Logika Upload File (Gambar & Dokumen) ---
                // (Asumsi logika upload file Anda sudah ada di sini seperti file asli)
                $uploaded_files = [];
                $uploadDir = 'public/uploads/posts/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                // 1. Gambar
                if (isset($_FILES['post_images']) && !empty($_FILES['post_images']['name'][0])) {
                    $files = $_FILES['post_images'];
                    $count = count($files['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $newName = uniqid('img_') . '.' . $ext;
                                if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $newName)) {
                                    $uploaded_files[] = $newName;
                                }
                            }
                        }
                    }
                }

                // Validasi input kosong
                if (empty($content) && empty($uploaded_files)) {
                    throw new Exception('Postingan tidak boleh kosong.');
                }

                // [SIMPAN] Panggil model dengan parameter is_comment_disabled
                if ($this->postModel->createPost($user_id, $content, $uploaded_files, $visibility, $forum_id, $is_comment_disabled, $poll_options)) {

                    // Respon sukses untuk AJAX
                    if ($isAjax) {
                        echo json_encode(['success' => true, 'message' => 'Postingan berhasil dibuat!']);
                        exit;
                    }
                    $_SESSION['success_message'] = 'Postingan berhasil dibuat!';
                } else {
                    throw new Exception('Gagal menyimpan postingan.');
                }
            } catch (Exception $e) {
                // Respon error untuk AJAX
                if ($isAjax) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }
                $_SESSION['error_message'] = $e->getMessage();
            }

            // Redirect fallback jika JS mati
            header('Location: ' . BASE_URL . '/home');
            exit;
        }
    }
    /**
     * Memproses penghapusan postingan DAN file gambar
     */
    public function delete()
    {
        // ... (Validasi Login & ID tetap sama seperti sebelumnya) ...
        if (!isset($_SESSION['user_id'])) { /* ... */
        }
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { /* ... */
        }

        $post_id_to_delete = (int)$_GET['id'];
        $current_user_id = (int)$_SESSION['user_id'];
        $is_admin = (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin');

        try {
            // [LANGKAH 1] Ambil data post untuk cek kepemilikan
            $post = $this->postModel->getPostById($post_id_to_delete);

            if (!$post) {
                $_SESSION['error_message'] = 'Postingan tidak ditemukan.';
                header('Location: ' . BASE_URL . '/home');
                exit;
            }

            // [LANGKAH 2] Cek Hak Akses
            if (!$is_admin && $post['USER_ID'] != $current_user_id) {
                $_SESSION['error_message'] = 'Anda tidak memiliki hak akses untuk menghapus postingan ini.';
                header('Location: ' . BASE_URL . '/home');
                exit;
            }

            // [LANGKAH 3 - PERBAIKAN] Hapus File Fisik (Support Multiple Images)

            // A. Ambil semua gambar dari tabel post_images
            $images = $this->postModel->getImagePathsByPostId($post_id_to_delete);

            // B. Loop dan hapus file satu per satu
            foreach ($images as $img) {
                $filePath = 'public/uploads/posts/' . $img;
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file dari folder
                }
            }

            // C. Cek juga kolom image_path di tabel posts (untuk jaga-jaga data lama)
            if (!empty($post['IMAGE_PATH'])) {
                $filePathOld = 'public/uploads/posts/' . $post['IMAGE_PATH'];
                if (file_exists($filePathOld)) {
                    unlink($filePathOld);
                }
            }

            // [LANGKAH 4] Hapus Record dari Database
            // (Data di tabel post_images akan otomatis terhapus karena ON DELETE CASCADE di database)
            if ($this->postModel->deletePostById($post_id_to_delete, $current_user_id, $is_admin)) {
                $_SESSION['success_message'] = 'Postingan dan semua foto berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Gagal menghapus data dari database.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    /**
     * Memproses like/unlike (Versi AJAX)
     */
    public function like()
    {
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

            if ($result['isLiked']) {
                // Ambil data post untuk tahu siapa pemiliknya
                $post = $this->postModel->getPostById($post_id);
                if ($post) {
                    $owner_id = $post['USER_ID'];
                    // Kirim notifikasi (pemilik, pelaku, tipe, id_post)
                    $this->notifModel->create($owner_id, $user_id, 'like', $post_id);
                }
            }

            echo json_encode([
                'success' => true,
                'isLiked' => $result['isLiked'],
                'newLikeCount' => $result['newLikeCount']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Memproses komentar (Mendukung AJAX dan Reply)
     */
    public function comment()
    {
        // Cek flag AJAX
        $isAjax = isset($_GET['ajax']);

        // 1. Cek Login
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
                exit;
            }
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $post_id = $_POST['post_id'] ?? null;
                $content = $_POST['content'] ?? '';

                // Ambil parent_id jika ada (untuk reply)
                $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

                if (empty($content)) {
                    $msg = 'Komentar tidak boleh kosong.';
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => $msg]);
                        exit;
                    }
                    $_SESSION['error_message'] = $msg;
                } elseif (empty($post_id) || !is_numeric($post_id)) {
                    $msg = 'Postingan tidak valid.';
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => $msg]);
                        exit;
                    }
                    $_SESSION['error_message'] = $msg;
                } else {

                    $currentPost = $this->postModel->getPostById($post_id);

                    // Cek jika post ditemukan DAN komentar didisable (is_comment_disabled == 1)
                    if ($currentPost && isset($currentPost['IS_COMMENT_DISABLED']) && $currentPost['IS_COMMENT_DISABLED'] == 1) {
                        $msg = 'Komentar untuk postingan ini telah dinonaktifkan.';
                        if ($isAjax) {
                            echo json_encode(['success' => false, 'message' => $msg]);
                            exit;
                        }
                        $_SESSION['error_message'] = $msg;
                        header('Location: ' . BASE_URL . '/home');
                        exit;
                    }
                    // Simpan ke Database
                    if ($this->postModel->addComment($post_id, $user_id, $content, $parent_id)) {

                        // [LOGIKA BARU NOTIFIKASI] 
                        // Ambil data post untuk tahu siapa pemiliknya
                        $post = $this->postModel->getPostById($post_id);
                        if ($post) {
                            $owner_id = $post['USER_ID'];
                            // Buat notifikasi: (Penerima, Pelaku, Tipe, ID Post)
                            $this->notifModel->create($owner_id, $user_id, 'comment', $post_id);
                        }

                        // JIKA AJAX: Kembalikan JSON data komentar baru agar bisa dirender JS
                        if ($isAjax) {
                            echo json_encode([
                                'success' => true,
                                'data' => [
                                    'nama' => $_SESSION['nama'],
                                    'initial' => strtoupper(substr($_SESSION['nama'], 0, 1)),
                                    'content' => nl2br(htmlspecialchars($content)),
                                    'parent_id' => $parent_id
                                ]
                            ]);
                            exit;
                        }

                        // JIKA BUKAN AJAX: Set session flash message
                        $_SESSION['success_message'] = 'Komentar ditambahkan!';
                    } else {
                        $msg = 'Gagal menambahkan komentar.';
                        if ($isAjax) {
                            echo json_encode(['success' => false, 'message' => $msg]);
                            exit;
                        }
                        $_SESSION['error_message'] = $msg;
                    }
                }
            } catch (Exception $e) {
                $msg = 'Terjadi kesalahan: ' . $e->getMessage();
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                $_SESSION['error_message'] = $msg;
            }
        }

        // Fallback jika akses langsung tanpa AJAX
        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    /**
     * Memproses Like pada Komentar
     */
    public function likeComment()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        $comment_id = $_GET['id'] ?? null;
        if (!$comment_id) {
            echo json_encode(['success' => false, 'message' => 'No ID']);
            exit;
        }

        try {
            $result = $this->postModel->toggleCommentLike($comment_id, $_SESSION['user_id']);
            echo json_encode(['success' => true, 'isLiked' => $result['isLiked'], 'count' => $result['count']]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Endpoint API untuk AJAX Polling (Realtime Updates)
     */
    public function getUpdates()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['post_ids']) || empty($input['post_ids'])) {
            echo json_encode([]);
            exit;
        }

        try {
            $updates = $this->postModel->getPostStats($input['post_ids']);
            echo json_encode($updates);
        } catch (Exception $e) {
            echo json_encode([]);
        }
        exit;
    }
    /**
     * [UPDATE] Menangani request disable/enable komentar (Support AJAX)
     */
    public function toggleComments()
    {
        // 1. Cek Login
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            if (isset($_GET['ajax'])) {
                echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
                exit;
            }
            header('Location: ' . BASE_URL);
            exit;
        }

        $post_id = $_GET['id'] ?? null;
        $user_id = $_SESSION['user_id'];
        $isAjax = isset($_GET['ajax']); // Cek apakah ini request AJAX

        if ($post_id) {
            try {
                // Panggil fungsi di Model
                $this->postModel->toggleCommentStatus($post_id, $user_id);

                if ($isAjax) {
                    // Ambil status terbaru untuk dikirim balik ke JS
                    $post = $this->postModel->getPostById($post_id);
                    $isDisabled = ($post['IS_COMMENT_DISABLED'] == 1);

                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'isDisabled' => $isDisabled,
                        'message' => 'Status komentar berhasil diubah.'
                    ]);
                    exit;
                }

                $_SESSION['success_message'] = 'Pengaturan komentar berhasil diubah.';
            } catch (Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }
                $_SESSION['error_message'] = $e->getMessage();
            }
        }

        // Fallback untuk request biasa (bukan AJAX)
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . BASE_URL . '/home');
        }
        exit;
    }
    /**
     * Memproses Voting pada Polling
     */
    public function vote()
    {
        // Set header JSON agar JS tidak error "Unexpected token"
        header('Content-Type: application/json');

        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
            exit;
        }

        // Ambil data JSON dari fetch() JS
        $input = json_decode(file_get_contents('php://input'), true);
        $post_id = $input['post_id'] ?? null;
        $option_id = $input['option_id'] ?? null;

        if ($post_id && $option_id) {
            try {
                // Panggil Model untuk simpan vote
                $result = $this->postModel->submitVote($post_id, $option_id, $_SESSION['user_id']);

                if ($result) {
                    // Jika sukses, ambil data terbaru untuk update tampilan progress bar
                    $newData = $this->postModel->getPollData($post_id, $_SESSION['user_id']);
                    echo json_encode(['success' => true, 'data' => $newData]);
                } else {
                    // Biasanya return false jika user sudah pernah vote
                    echo json_encode(['success' => false, 'message' => 'Anda sudah memberikan suara pada polling ini.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
        }
        exit;
    }
    /**
     * [BARU] Hapus Komentar via AJAX
     */
    public function deleteComment()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        $comment_id = $_POST['comment_id'] ?? null;
        if (!$comment_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        // Cek Admin (Opsional)
        $is_admin = (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin');

        if ($this->postModel->deleteComment($comment_id, $_SESSION['user_id'], $is_admin)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus atau akses ditolak']);
        }
        exit;
    }

    /**
     * [BARU] Update Komentar via AJAX
     */
    public function updateComment()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        $comment_id = $_POST['comment_id'] ?? null;
        $content = $_POST['content'] ?? '';

        if (!$comment_id || empty(trim($content))) {
            echo json_encode(['success' => false, 'message' => 'Content cannot be empty']);
            exit;
        }

        if ($this->postModel->updateComment($comment_id, $_SESSION['user_id'], $content)) {
            echo json_encode(['success' => true, 'new_content' => nl2br(htmlspecialchars($content))]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal update atau akses ditolak']);
        }
        exit;
    }
}
