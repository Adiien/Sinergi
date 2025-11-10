<?php
// Sinergi/src/controllers/AuthController.php
// (Versi Final Lengkap dengan Register dan Login + CAPTCHA)

class AuthController {

    /**
     * 1. Method index() (Anda sudah punya ini)
     * Tugas: Hanya menampilkan halaman form login/register.
     */
    public function index() {
        // Baris $data ini bagus untuk judul halaman
        $data['pageTitle'] = 'Auth Sinergi Page';
        
        // Memuat file view (Pintu Depan)
        require_once __DIR__ . '/../../views/auth/index.php';
    }

    /**
     * 2. Method register() (PENTING: tambahkan ini)
     * Tugas: Menerima data dari form register, memvalidasi, dan menyimpan ke DB.
     */
    public function register() {
        
        // Cek apakah ini metode POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['register_error'] = 'Metode request tidak valid.';
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        // Koneksi ke Database (fungsi dari database.php)
        $conn = koneksi_oracle();
        if (!$conn) {
            $_SESSION['register_error'] = 'Koneksi ke database gagal.';
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        // Ambil dan Validasi Data
        $nama = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password_reg'] ?? '';
        $re_password = $_POST['confirm_password'] ?? '';
        $role_name = trim($_POST['selected-role'] ?? '');

        // Validasi dasar (bisa ditambahkan validasi lain)
        if (empty($nama) || empty($email) || empty($password) || empty($role_name)) {
            $_SESSION['register_error'] = 'Semua field wajib diisi.';
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        if ($password !== $re_password) {
            $_SESSION['register_error'] = 'Password dan Konfirmasi Password tidak cocok.';
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        // (Tambahkan validasi lain seperti cek duplikat email, dll. di sini)
        // ...

        // Ambil data spesifik role (NIM, NIP, dll)
        $nim = null;
        $nip = null;
        $program_studi = null;
        $tahun_masuk = null;

        if ($role_name == 'mahasiswa') {
            $nim = trim($_POST['nim'] ?? null);
            $program_studi = trim($_POST['program_studi'] ?? null); 
            $tahun_masuk = !empty($_POST['tahun_masuk']) ? (int)$_POST['tahun_masuk'] : null;
            if (empty($nim) || empty($program_studi) || empty($tahun_masuk)) {
                 $_SESSION['register_error'] = 'NIM, Prodi, dan Tahun Masuk wajib diisi untuk mahasiswa.';
                 oci_close($conn);
                 header('Location: ' . BASE_URL . '/auth/index');
                 exit;
            }
        } // ... (tambahkan elseif untuk 'dosen' dan 'alumni' jika perlu)

        // Hash Password
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Query INSERT
        $query_insert = "INSERT INTO users (
                    USER_ID, NAMA, EMAIL, PASSWORD, ROLE_NAME,
                    NIM, NIP, PROGRAM_STUDI, TAHUN_MASUK
                ) VALUES (
                    users_seq.NEXTVAL, :nama, :email, :password_hash, :role,
                    :nim, :nip, :program_studi, :tahun_masuk
                )";

        $stmt_insert = oci_parse($conn, $query_insert);
        
        oci_bind_by_name($stmt_insert, ':nama', $nama);
        oci_bind_by_name($stmt_insert, ':email', $email);
        oci_bind_by_name($stmt_insert, ':password_hash', $hash_password);
        oci_bind_by_name($stmt_insert, ':role', $role_name);
        oci_bind_by_name($stmt_insert, ':nim', $nim);
        oci_bind_by_name($stmt_insert, ':nip', $nip);
        oci_bind_by_name($stmt_insert, ':program_studi', $program_studi);
        oci_bind_by_name($stmt_insert, ':tahun_masuk', $tahun_masuk);

        $result = oci_execute($stmt_insert);

        if ($result) {
            $_SESSION['register_success'] = 'Registrasi berhasil! Silakan login.';
        } else {
            $e = oci_error($stmt_insert);
            $_SESSION['register_error'] = 'Registrasi Gagal: ' . htmlspecialchars($e['message']);
        }
        
        oci_free_statement($stmt_insert);
        oci_close($conn);
        header('Location: ' . BASE_URL . '/auth/index');
        exit;
    }


    /**
     * 3. Method login() (PENTING: "Satpam" CAPTCHA ada di sini)
     * Tugas: Menerima data dari form login, cek CAPTCHA, cek password, dan buat session.
     */
    public function login() {
        
        // 1. Cek apakah ini metode POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['login_error'] = 'Metode request tidak valid.';
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        // 2. Koneksi ke Database (fungsi dari database.php)
        $conn = koneksi_oracle(); // Di-load oleh router
        if (!$conn) {
            $_SESSION['login_error'] = 'Koneksi ke database gagal.';
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }

        // 3. Ambil data dari form
        $identifier = $_POST['identifier'] ?? null;
        $password = $_POST['password'] ?? null;
        $captcha_input = trim($_POST['captcha_input'] ?? '');

        // 4. Validasi input dasar
        if (!$identifier || !$password || !$captcha_input) {
            $_SESSION['login_error'] = 'Semua field (termasuk CAPTCHA) wajib diisi!';
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }
        
        // 5. SATPAM MENGECEK TIKET (Validasi CAPTCHA)
        $stored_code = $_SESSION['captcha_code'] ?? null; // Ambil tiket dari "Kantong Ajaib"

        if ($stored_code === null || strcasecmp($captcha_input, $stored_code) != 0) {
            // Jika CAPTCHA salah
            $_SESSION['login_error'] = 'Kode CAPTCHA yang Anda masukkan salah.';
            unset($_SESSION['captcha_code']); // Hapus kode yang salah
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index'); // Kembalikan ke Pintu Depan
            exit;
        }
        
        // Jika CAPTCHA benar, hapus kodenya (agar tidak bisa dipakai lagi)
        unset($_SESSION['captcha_code']);

        // 6. SATPAM MENGECEK ID (PASSWORD)
        $sql = "SELECT user_id, nama, email, password, role_name, nim, nip, program_studi 
                FROM users 
                WHERE email = :identifier OR nim = :identifier OR nip = :identifier";

        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ":identifier", $identifier);
        
        if (!oci_execute($stid)) {
            $_SESSION['login_error'] = 'Terjadi kesalahan sistem saat login.';
            oci_free_statement($stid);
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }
        
        $user = oci_fetch_assoc($stid);

        // Verifikasi password
        if ($user && password_verify($password, $user['PASSWORD'])) {
            // ✅ Login berhasil
            session_regenerate_id(true); // Amankan session
            
            $_SESSION['user_id'] = $user['USER_ID'];
            $_SESSION['nama'] = $user['NAMA'];
            $_SESSION['role'] = $user['ROLE_NAME'];

            oci_free_statement($stid);
            oci_close($conn);
            
            // Redirect ke halaman home (via URL route)
            header('Location: ' . BASE_URL . '/home');
            exit;
            
        } else {
            // ❌ Login gagal (ID atau Password salah)
            $_SESSION['login_error'] = 'Email/NIM/NIP atau Password Anda salah.';
            oci_free_statement($stid);
            oci_close($conn);
            header('Location: ' . BASE_URL . '/auth/index');
            exit;
        }
    }

} // <-- Akhir dari class AuthController
?>