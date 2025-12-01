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

            // 1. AMBIL DATA & BERSIHKAN
            $nama = htmlspecialchars(trim($_POST['nama'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $role_name = $_POST['role_name'] ?? 'mahasiswa';
            $nim_nip = trim($_POST['nim-nip-input'] ?? '');
            $program_studi = $_POST['program_studi'] ?? '';
            $admission_year = $_POST['admission_year'] ?? null;

            // --- [BARU] VALIDASI DOMAIN EMAIL ---
            if ($role_name == 'mahasiswa') {
                if (!preg_match("/@stu\.pnj\.ac\.id$/", $email)) {
                    $_SESSION['error_message'] = 'Mahasiswa wajib menggunakan email @stu.pnj.ac.id';
                    $_SESSION['open_modal'] = 'register';
                    header('Location: ' . BASE_URL);
                    exit;
                }
            } elseif ($role_name == 'dosen') {
                if (!preg_match("/@tik\.pnj\.ac\.id$/", $email)) {
                    $_SESSION['error_message'] = 'Dosen wajib menggunakan email @tik.pnj.ac.id';
                    $_SESSION['open_modal'] = 'register';
                    header('Location: ' . BASE_URL);
                    exit;
                }
            } elseif ($role_name == 'alumni') {
                // Validasi gmail.com (atau bisa dihapus jika Alumni bebas)
                if (!preg_match("/@gmail\.com$/", $email)) {
                    $_SESSION['error_message'] = 'Alumni wajib menggunakan email @gmail.com';
                    $_SESSION['open_modal'] = 'register';
                    header('Location: ' . BASE_URL);
                    exit;
                }
            }
            // ------------------------------------

            // 2. VALIDASI FIELD KOSONG
            if (empty($nama) || empty($email) || empty($password) || empty($nim_nip)) {
                $_SESSION['error_message'] = 'Semua kolom wajib diisi!';
                $_SESSION['open_modal'] = 'register';
                header('Location: ' . BASE_URL);
                exit;
            }
            // Validasi khusus Role
            if ($role_name == 'mahasiswa' && empty($program_studi)) {
                $_SESSION['error_message'] = 'Mahasiswa wajib memilih Program Studi.';
                $_SESSION['open_modal'] = 'register';
                header('Location: ' . BASE_URL);
                exit;
            }

            // 3. SIAPKAN DATA UNTUK MODEL
            $data = [
                'nama' => $nama,
                'email' => $email,
                'password' => $password,
                'role_name' => $role_name,
                'nim-nip-input' => $nim_nip,
                'program_studi' => $program_studi,
                'admission_year' => $admission_year
            ];

            // 4. EKSEKUSI KE MODEL
            try {
                if ($this->userModel->registerUser($data)) {
                    $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
                    $_SESSION['open_modal'] = 'login'; // Buka modal login
                } else {
                    $_SESSION['error_message'] = 'Registrasi gagal. Silakan coba lagi.';
                    $_SESSION['open_modal'] = 'register';
                }
            } catch (Exception $e) {
                // Cek pesan error Oracle untuk duplikat data (ORA-00001)
                if (strpos($e->getMessage(), 'ORA-00001') !== false) {
                    $_SESSION['error_message'] = 'Email atau NIM/NIP sudah terdaftar!';
                } else {
                    $_SESSION['error_message'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
                }
                $_SESSION['open_modal'] = 'register';
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
