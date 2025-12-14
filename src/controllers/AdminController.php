<?php
// Pastikan kita memiliki akses ke model User, Post, dan ReportModel
require_once 'src/models/User.php';
require_once 'src/models/Post.php';
// [PERBAIKAN 1]: Pastikan nama file ini benar (saya asumsikan 'ReportModel.php' sesuai instruksi sebelumnya)
require_once 'src/models/Report.php';

require_once __DIR__ . '/../helpers/MailHelper.php';
class AdminController
{
    private $userModel;
    private $postModel;
    private $reportModel; // [PERBAIKAN 2]: Properti sudah ada, bagus
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
        $this->postModel = new Post($this->conn);

        // [PERBAIKAN 3]: Inisialisasi ReportModel
        // (Pastikan nama class-nya 'ReportModel', sesuaikan jika nama file Anda beda)
        $this->reportModel = new ReportModel($this->conn);
    }

    /**
     * Metode utama untuk dasbor admin
     */
    public function index()
    {
        // 1. Pengecekan Keamanan (Sangat Penting)
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin') {
            // ... (kode keamanan Anda sudah benar)
            $_SESSION['error_message'] = 'Anda tidak memiliki hak akses.';
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 2. Jika lolos, ambil data untuk dasbor
        $users = [];
        $pendingReports = []; // [PERBAIKAN 4]: Inisialisasi variabel

        try {
            // Ambil data user
            $users = $this->userModel->getAllUsers();

            // [PERBAIKAN 5]: Ambil data laporan yang pending
            $pendingReports = $this->reportModel->getPendingReports();
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Gagal memuat data admin: ' . $e->getMessage();
        }

        // 3. Muat view admin dan kirimkan datanya
        //    Variabel $users dan $pendingReports otomatis tersedia di view
        require 'views/admin/index.php';
    }

    /**
     * Memproses penghapusan pengguna
     */
    public function delete()
    {
        // (Kode fungsi delete Anda sudah benar, biarkan saja)

        // 1. Keamanan: Pastikan admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin') {
            $_SESSION['error_message'] = 'Anda tidak memiliki hak akses.';
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 2. Validasi: Pastikan ID ada
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = 'ID pengguna tidak valid.';
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }

        $user_id_to_delete = (int)$_GET['id'];
        $admin_user_id = (int)$_SESSION['user_id'];

        // 3. Keamanan: Admin tidak boleh menghapus diri sendiri
        if ($user_id_to_delete === $admin_user_id) {
            $_SESSION['error_message'] = 'Anda tidak dapat menghapus akun Anda sendiri.';
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }

        // 4. Proses Hapus
        try {
            if ($this->userModel->deleteUserById($user_id_to_delete)) {
                $_SESSION['success_message'] = 'Pengguna berhasil dihapus.';
            } else {
                $_SESSION['error_message'] = 'Gagal menghapus pengguna. Mungkin pengguna tidak ditemukan.';
            }
        } catch (Exception $e) {
            // Tangkap error database (misal, foreign key constraint)
            $_SESSION['error_message'] = 'Error Database: ' . $e->getMessage();
        }

        // 5. Kembali ke halaman admin
        header('Location: ' . BASE_URL . '/admin');
        exit;
    }
    /**
     * [BARU] Kirim Ulang Email Verifikasi
     */
    public function resendVerification()
    {
        // 1. Cek Admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin') {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 2. Validasi ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = 'ID pengguna tidak valid.';
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }

        $user_id = (int)$_GET['id'];

        try {
            // 3. Ambil data user
            $user = $this->userModel->getUserById($user_id);

            if (!$user) {
                throw new Exception("Pengguna tidak ditemukan.");
            }

            // Cek apakah statusnya memang pending_email
            if ($user['STATUS'] !== 'pending_email') {
                throw new Exception("Akun ini sudah aktif atau tidak butuh verifikasi email.");
            }

            // 4. Generate Token Baru
            $newToken = bin2hex(random_bytes(32));

            // 5. Update Token di DB
            if ($this->userModel->updateVerificationToken($user_id, $newToken)) {

                // 6. Kirim Email
                $sent = MailHelper::sendVerificationEmail($user['EMAIL'], $user['NAMA'], $newToken);

                if ($sent) {
                    $_SESSION['success_message'] = "Email verifikasi berhasil dikirim ulang ke " . htmlspecialchars($user['EMAIL']);
                } else {
                    $_SESSION['error_message'] = "Gagal mengirim email (SMTP Error). Cek konfigurasi MailHelper.";
                }
            } else {
                throw new Exception("Gagal mengupdate token database.");
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        // Kembali ke dashboard admin
        header('Location: ' . BASE_URL . '/admin');
        exit;
    }

    public function updateRole()
{
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }

    $userId = (int)($_POST['user_id'] ?? 0);
    $role   = $_POST['role'] ?? '';

    $allowed = ['admin', 'mahasiswa', 'dosen', 'alumni'];
    if ($userId <= 0 || !in_array($role, $allowed)) {
        $_SESSION['error_message'] = 'Data tidak valid';
        header("Location: " . BASE_URL . "/admin");
        exit;
    }

    // Cegah admin mengubah role dirinya sendiri
    if ($userId == $_SESSION['user_id']) {
        $_SESSION['error_message'] = 'Tidak boleh mengubah role sendiri';
        header("Location: " . BASE_URL . "/admin");
        exit;
    }

    $db = koneksi_oracle();
    $sql = "UPDATE users SET role_name = :role WHERE user_id = :id";
    $stmt = oci_parse($db, $sql);

    oci_bind_by_name($stmt, ":role", $role);
    oci_bind_by_name($stmt, ":id", $userId);
    oci_execute($stmt);

    $_SESSION['success_message'] = 'Role user berhasil diubah';
    header("Location: " . BASE_URL . "/admin");
    exit;
}

    public function toggleStatus()
{
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }

    $userId = (int)($_GET['id'] ?? 0);
    $status = $_GET['status'] ?? '';

    if ($userId <= 0 || !in_array($status, ['active', 'suspended'])) {
        $_SESSION['error_message'] = 'Status tidak valid';
        header("Location: " . BASE_URL . "/admin");
        exit;
    }

    // Cegah admin suspend dirinya sendiri
    if ($userId == $_SESSION['user_id']) {
        $_SESSION['error_message'] = 'Tidak boleh suspend akun sendiri';
        header("Location: " . BASE_URL . "/admin");
        exit;
    }

    $db = koneksi_oracle();
    $sql = "UPDATE users SET status = :status WHERE user_id = :id";
    $stmt = oci_parse($db, $sql);

    oci_bind_by_name($stmt, ":status", $status);
    oci_bind_by_name($stmt, ":id", $userId);
    oci_execute($stmt);

    $_SESSION['success_message'] = "Status user diubah menjadi {$status}";
    header("Location: " . BASE_URL . "/admin");
    exit;
}

public function statistik()
{
    AuthGuard::protect();

    $db = koneksi_oracle();

    $userModel  = new User($db);
    $forumModel = new Forum($db);
    $postModel  = new Post($db);

    $stats = [
        'total_users'  => $userModel->countAll(),
        'active_users' => $userModel->countActive(),
        'total_forums' => $forumModel->countAll(),
        'total_posts'  => $postModel->countAll(),
    ];

    require_once 'views/admin/stats.php';
}




}
