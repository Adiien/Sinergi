<?php
// Pastikan kita memiliki akses ke model User, Post, dan ReportModel
require_once 'src/models/User.php';
require_once 'src/models/Post.php';
require_once 'src/models/Report.php';
require_once 'src/models/Forum.php';

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
        // 1. Cek Login
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin') {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        $db = $this->conn;
        
        // 2. Siapkan Variabel Default
        $users = [];
        $pendingReports = [];
        $stats = [ // Default stats 0
            'total_users'  => 0,
            'active_users' => 0,
            'total_forums' => 0,
            'total_posts'  => 0,
        ];

        // 3. Ambil Parameter Tab
        $tab = $_GET['tab'] ?? 'users';

        try {
            // Init Model yang diperlukan
            // (Pastikan semua model ini sudah di-include atau ada di __construct)
            $userModel  = new User($db);
            $reportModel = new ReportModel($db); // Sesuaikan nama class model report Anda
            $forumModel = new ForumModel($db);
            $postModel  = new Post($db);
            $limit = 10;
            $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($page - 1) * $limit;

            // 4. Logika Pengambilan Data Berdasarkan Tab
            if ($tab === 'users') {
                $role = $_GET['role'] ?? '';
                $nim  = $_GET['nim'] ?? '';
                $nama = $_GET['nama'] ?? '';
                $totalUsers = $this->userModel->countUsersFiltered($role, $nim, $nama);
                $totalPages = ceil($totalUsers / $limit);

                $users = $this->userModel->getUsersFilteredPaginated(
                $limit, $offset, $role, $nim, $nama
                ); 
            }

            elseif ($tab === 'reports') {
                $pendingReports = $reportModel->getPendingReports();
            }
            // KUNCI PERBAIKANNYA DI SINI:
            // Jika tab adalah 'stats', atau untuk jaga-jaga kita ambil juga di semua tab (opsional)
            elseif  ($tab === 'stats') {
                 $stats = [
                    'total_users'  => $userModel->countAll(),
                    'active_users' => $userModel->countActive(),
                    'total_forums' => $forumModel->countAll(),
                    'total_posts'  => $postModel->countAll(),
                ];
            }

            // Opsional: Jika Anda ingin widget angka SELALU muncul,
            // pindahkan logika $stats keluar dari blok if/else.

        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Gagal memuat data: ' . $e->getMessage();
        }

        // 5. Load View Utama
        // Variabel $stats yang sudah diisi di atas akan terbawa ke sini
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
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    $db = koneksi_oracle();

    require_once 'src/models/User.php';
    require_once 'src/models/Post.php';
    require_once 'src/models/forum.php';

    $userModel  = new User($db);
    $forumModel = new ForumModel($db);
    $postModel  = new Post($db);

    // KPI
    $stats = [
        'total_users'  => (int) $userModel->countAll(),
        'active_users' => (int) $userModel->countActive(),
        'total_forums' => (int) $forumModel->countAll(),
        'total_posts'  => (int) $postModel->countAll(),
    ];

    // Aktivitas
    $activeUsers  = $userModel->getMostActiveUsers(5);
    $activeForums = $forumModel->getMostActiveForums(5);

    require 'views/admin/stats.php';
}


    public function review()
{
    // proteksi admin
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
        header('Location: ' . BASE_URL . '/home');
        exit;
    }

    $reportId = $_GET['id'] ?? null;
    if (!$reportId || !is_numeric($reportId)) {
        die('Report ID tidak valid');
    }

    $reportModel = new ReportModel($this->conn);
    $postModel   = new Post($this->conn);

    // ambil laporan
    $report = $reportModel->getReportById($reportId);
    if (!$report) {
        die('Laporan tidak ditemukan');
    }

    // HANDLE ACTION
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        try {

            if ($action === 'dismiss') {
                $reportModel->updateStatus($reportId, 'dismissed');

                $_SESSION['success_message'] = 'Laporan berhasil diabaikan.';
                header('Location: ' . BASE_URL . '/admin?tab=reports');
                exit;
            }

            if ($action === 'delete_content') {

                if ($report['TARGET_TYPE'] === 'post') {
                    // pastikan method delete ADA di Post model
                    $postModel->delete($report['TARGET_ID']);
                    $reportModel->resolveAllByTarget('post', $report['TARGET_ID']);
                }
                $_SESSION['success_message'] = 'Konten dihapus dan laporan diselesaikan.';
                header('Location: ' . BASE_URL . '/admin?tab=reports');
                exit;
            }

        } catch (Exception $e) {
            $error = 'Gagal memproses laporan: ' . $e->getMessage();
        }
    }

    require __DIR__ . '/../../views/admin/review.php';
}


    // Notip sidebar
    public function apiCheckNotifications()
    {
        // Pastikan yang akses adalah admin
        if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['count' => 0]);
            exit;
        }

        try {
            $count = $this->reportModel->countPending();
            header('Content-Type: application/json');
            echo json_encode(['count' => $count]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['count' => 0]);
            exit;
        }
    }

}
