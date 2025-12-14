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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL);
        exit;
    }

    // CAPTCHA CHECK
    if (
        !isset($_POST['captcha']) ||
        !isset($_SESSION['captcha_string']) ||
        trim($_POST['captcha']) !== $_SESSION['captcha_string']
    ) {
        $_SESSION['error_message'] = 'Verifikasi CAPTCHA gagal.';
        $_SESSION['open_modal'] = 'login';
        unset($_SESSION['captcha_string']);
        header('Location: ' . BASE_URL);
        exit;
    }

    unset($_SESSION['captcha_string']);

    try {
        $identifier = $_POST['identifier'] ?? '';
        $password   = $_POST['password'] ?? '';

        // 1️⃣ AMBIL USER
        $user = $this->userModel->loginUser($identifier, $password);

        if (!$user) {
            $_SESSION['error_message'] = 'Login gagal. Periksa kredensial.';
            $_SESSION['open_modal'] = 'login';
            header('Location: ' . BASE_URL);
            exit;
        }

        // 2️⃣ CEK STATUS USER (INI YANG KEMARIN SALAH TEMPAT)
        if (isset($user['STATUS']) && strtolower($user['STATUS']) === 'suspended') {
            $_SESSION['error_message'] = 'Akun Anda telah disuspend oleh admin.';
            $_SESSION['open_modal'] = 'login';
            header('Location: ' . BASE_URL);
            exit;
        }

        // 3️⃣ SET SESSION (BARU BOLEH)
        $_SESSION['user_id']   = $user['USER_ID'];
        $_SESSION['nama']      = $user['NAMA'];
        $_SESSION['email']     = $user['EMAIL'];
        $_SESSION['role_name'] = $user['ROLE_NAME'];

        header('Location: ' . BASE_URL . '/home');
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Terjadi kesalahan sistem.';
        header('Location: ' . BASE_URL);
        exit;
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
    /**
     * 1. FORM LUPA PASSWORD (MODAL)
     */
    public function forgotPassword()
    {
        // Jika akses GET biasa (bukan submit form), redirect ke home buka modal forgot
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['open_modal'] = 'forgot_password';
            header('Location: ' . BASE_URL);
            exit;
        }

        // --- PROSES POST ---
        $email = $_POST['email'];

        // Cek User
        $user = $this->userModel->getUserByEmail($email);

        if ($user) {
            // Generate OTP
            $code = rand(1000, 9999);

            // Simpan Session OTP
            $_SESSION['otp_code'] = $code;
            $_SESSION['otp_email'] = $email;
            $_SESSION['otp_expiry'] = time() + (15 * 60); // 15 Menit

            // Kirim Email
            MailHelper::sendResetCode($email, $user['NAMA'], $code);

            // SUKSES: Buka modal verifikasi
            $_SESSION['open_modal'] = 'verify_code';
        } else {
            // GAGAL: Tetap di modal forgot
            $_SESSION['error_message'] = "Email tidak ditemukan.";
            $_SESSION['open_modal'] = 'forgot_password';
        }

        header('Location: ' . BASE_URL);
        exit;
    }

    /**
     * 2. FORM VERIFIKASI KODE (MODAL)
     */
    public function verifyCode()
    {
        // Cek akses valid
        if (!isset($_SESSION['otp_email'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputCode = implode('', $_POST['code']);

            // Validasi Waktu
            if (time() > $_SESSION['otp_expiry']) {
                unset($_SESSION['otp_code']);
                $_SESSION['error_message'] = "Kode kadaluarsa. Silakan ulang.";
                $_SESSION['open_modal'] = 'forgot_password'; // Balik ke awal
                header('Location: ' . BASE_URL);
                exit;
            }

            // Validasi Kode
            if ($inputCode == $_SESSION['otp_code']) {
                $_SESSION['otp_verified'] = true;
                // SUKSES: Buka modal reset password
                $_SESSION['open_modal'] = 'reset_password';
            } else {
                $_SESSION['error_message'] = "Kode salah.";
                $_SESSION['open_modal'] = 'verify_code'; // Tetap di sini
            }
        } else {
            // Jika akses GET, buka modal verify
            $_SESSION['open_modal'] = 'verify_code';
        }

        header('Location: ' . BASE_URL);
        exit;
    }

    /**
     * 3. FORM GANTI PASSWORD (MODAL)
     */
    public function resetPassword()
    {
        if (!isset($_SESSION['otp_verified'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pass1 = $_POST['new_password'];
            $pass2 = $_POST['confirm_password'];

            if ($pass1 === $pass2) {
                $email = $_SESSION['otp_email'];

                if ($this->userModel->updatePasswordByEmail($email, $pass1)) {
                    // BERSIHKAN SESSION
                    unset($_SESSION['otp_code']);
                    unset($_SESSION['otp_email']);
                    unset($_SESSION['otp_expiry']);
                    unset($_SESSION['otp_verified']);

                    $_SESSION['success_message'] = "Password berhasil diubah. Silakan login.";
                    $_SESSION['open_modal'] = 'login'; // Buka modal login
                } else {
                    $_SESSION['error_message'] = "Gagal update database.";
                    $_SESSION['open_modal'] = 'reset_password';
                }
            } else {
                $_SESSION['error_message'] = "Password tidak cocok.";
                $_SESSION['open_modal'] = 'reset_password';
            }
        } else {
            $_SESSION['open_modal'] = 'reset_password';
        }

        header('Location: ' . BASE_URL);
        exit;
    }
}
