<?php
require_once 'src/models/User.php';

class AuthController {

    private $userModel;
    private $conn;

    public function __construct() {
        // Buat koneksi Oracle
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
    }

    /**
     * Menampilkan halaman login/registrasi (metode default)
     */
    public function index() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }
        require_once 'views/auth/index.php';
    }

    /**
     * Memproses registrasi
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->userModel->registerUser($_POST)) {
                    $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
                } else {
                    $_SESSION['error_message'] = 'Registrasi gagal. Coba lagi.';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    /**
     * Memproses login
     */
// ...
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $identifier = $_POST['identifier'];
                $password = $_POST['password'];

                $user = $this->userModel->loginUser($identifier, $password);

                if ($user) {
                    // ===== INI SUDAH BENAR (Gunakan UPPERCASE) =====
                    // Karena 'user_id', 'nama', 'email' adalah kolom standar
                    $_SESSION['nama'] = $user['NAMA'];
                    $_SESSION['email'] = $user['EMAIL'];
                    $_SESSION['role_name'] = $user['ROLE_NAME'];
                    // ==============================================

                    header('Location: ' . BASE_URL . '/home');
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Login Gagal. Periksa kembali email/NIM/NIP dan password Anda.';
                    header('Location: ' . BASE_URL);
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . BASE_URL);
                exit;
            }
        }
    }
    // ...

    /**
     * Memproses logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}
?>