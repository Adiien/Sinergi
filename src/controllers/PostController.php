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
        // ... (kode validasi login tetap sama) ...

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $user_id = $_SESSION['user_id'];
                $content = $_POST['content'];
                $visibility = $_POST['visibility'] ?? 'public';
                $forum_id = !empty($_POST['forum_id']) ? $_POST['forum_id'] : null;

                $uploaded_files = []; // Array untuk menampung semua file (gambar + dokumen)
                $uploadDir = 'public/uploads/posts/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                // 1. PROSES GAMBAR (post_images)
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

                // 2. [BARU] PROSES DOKUMEN (post_files)
                if (isset($_FILES['post_files']) && !empty($_FILES['post_files']['name'][0])) {
                    $docFiles = $_FILES['post_files'];
                    $docCount = count($docFiles['name']);
                    // Daftar ekstensi file yang diperbolehkan
                    $allowed_docs = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'txt'];

                    for ($i = 0; $i < $docCount; $i++) {
                        if ($docFiles['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($docFiles['name'][$i], PATHINFO_EXTENSION));

                            if (in_array($ext, $allowed_docs)) {
                                // Simpan nama asli agar lebih user friendly saat didownload, tapi prefix unik
                                $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($docFiles['name'][$i], PATHINFO_FILENAME));
                                $newName = uniqid('file_') . '_' . $cleanName . '.' . $ext;

                                if (move_uploaded_file($docFiles['tmp_name'][$i], $uploadDir . $newName)) {
                                    $uploaded_files[] = $newName;
                                }
                            }
                        }
                    }
                }

                if (empty($content) && empty($uploaded_files)) {
                    $_SESSION['error_message'] = 'Postingan kosong. Tulis sesuatu atau lampirkan file.';
                } else {
                    // Simpan ke DB (Model sudah support array file path)
                    if ($this->postModel->createPost($user_id, $content, $uploaded_files, $visibility, $forum_id)) {
                        $_SESSION['success_message'] = 'Postingan berhasil dibuat!';
                    } else {
                        $_SESSION['error_message'] = 'Gagal membuat postingan.';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
            }

            // Redirect...
            if ($forum_id) {
                header('Location: ' . BASE_URL . '/forum/show?id=' . $forum_id);
            } else {
                header('Location: ' . BASE_URL . '/home');
            }
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
}
