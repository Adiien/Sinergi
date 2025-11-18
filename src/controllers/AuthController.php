<?php
require_once 'src/models/User.php';

class AuthController
{

    private $userModel;
    private $conn;

    public function __construct()
    {
        // Buat koneksi Oracle
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
    }

    /**
     * Menampilkan halaman login/registrasi (metode default)
     */
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }
        require_once 'views/auth/index.php';
    }

    /**
     * Memproses registrasi
     */
    public function register()
    {
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
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if (
                !isset($_POST['captcha']) ||
                !isset($_SESSION['captcha_string']) ||
                strtolower($_POST['captcha']) != strtolower($_SESSION['captcha_string'])
            ) {


                $_SESSION['error_message'] = 'Verifikasi CAPTCHA gagal. Silakan coba lagi.';

                // Kosongkan CAPTCHA lama
                if (isset($_SESSION['captcha_string'])) {
                    unset($_SESSION['captcha_string']);
                }

                header('Location: ' . BASE_URL);
                exit;
            }
            
            unset($_SESSION['captcha_string']);

            try {
                $identifier = $_POST['identifier'];
                $password = $_POST['password'];

                $user = $this->userModel->loginUser($identifier, $password);

                if ($user) {

                    $_SESSION['user_id'] = $user['USER_ID'];
                    $_SESSION['nama'] = $user['NAMA'];
                    $_SESSION['email'] = $user['EMAIL'];
                    $_SESSION['role_name'] = $user['ROLE_NAME'];


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
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}
