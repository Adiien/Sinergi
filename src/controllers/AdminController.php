<?php
// Pastikan kita memiliki akses ke model User, Post, dan ReportModel
require_once 'src/models/User.php';
require_once 'src/models/Post.php';
// [PERBAIKAN 1]: Pastikan nama file ini benar (saya asumsikan 'ReportModel.php' sesuai instruksi sebelumnya)
require_once 'src/models/Report.php'; 

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
}