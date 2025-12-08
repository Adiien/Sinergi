<?php
require_once 'src/models/User.php';
require_once 'src/helpers/MailHelper.php'; // Ensure this file exists at src/helpers/MailHelper.php
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

            // ... (Kode validasi input Anda sebelumnya TETAP SAMA) ...
            $nama = htmlspecialchars(trim($_POST['nama'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            // ... dst ...

            // [BARU] Generate Token 32 byte hex
            $token = bin2hex(random_bytes(32));

            $data = [
                'nama' => $nama,
                'email' => $email,
                'password' => $_POST['password'],
                'role_name' => $_POST['role_name'],
                'nim-nip-input' => $_POST['nim-nip-input'] ?? '',
                'program_studi' => $_POST['program_studi'] ?? '',
                'admission_year' => $_POST['admission_year'] ?? null
            ];

            try {
                // Panggil Model dengan Token
                if ($this->userModel->registerUser($data, $token)) {

                    // Kirim Email
                    $sent = MailHelper::sendVerificationEmail($email, $nama, $token);

                    if ($sent) {
                        $_SESSION['success_message'] = 'Registrasi berhasil! Cek email Anda untuk verifikasi.';
                    } else {
                        $_SESSION['success_message'] = 'Registrasi berhasil, tapi gagal kirim email. Hubungi Admin.';
                    }

                    $_SESSION['open_modal'] = 'login';
                } else {
                    $_SESSION['error_message'] = 'Registrasi gagal.';
                    $_SESSION['open_modal'] = 'register';
                }
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'ORA-00001') !== false) {
                    $_SESSION['error_message'] = 'Email atau NIM/NIP sudah terdaftar!';
                } else {
                    $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
                }
                $_SESSION['open_modal'] = 'register';
            }

            header('Location: ' . BASE_URL);
            exit;
        }
    }

    /**
     * [BARU] Menangani Klik Link dari Email
     */
    public function verify()
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            $_SESSION['error_message'] = "Token tidak ditemukan.";
            header('Location: ' . BASE_URL);
            exit;
        }

        try {
            $status = $this->userModel->verifyUserToken($token);

            if ($status === 'active') {
                $_SESSION['success_message'] = "Email terverifikasi! Akun Anda sudah aktif. Silakan login.";
            } elseif ($status === 'pending_approval') {
                $_SESSION['success_message'] = "Email terverifikasi! Akun Alumni Anda kini menunggu persetujuan Admin.";
            } else {
                $_SESSION['error_message'] = "Token verifikasi salah atau sudah kadaluarsa.";
            }

            $_SESSION['open_modal'] = 'login';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Terjadi kesalahan sistem.";
        }

        header('Location: ' . BASE_URL);
        exit;
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
                trim($_POST['captcha']) !== $_SESSION['captcha_string']
            ) {


                $_SESSION['error_message'] = 'Verifikasi CAPTCHA gagal. Silakan coba lagi.';
                $_SESSION['open_modal'] = 'login';

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
                    $_SESSION['open_modal'] = 'login';
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
