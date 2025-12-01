<?php
require_once 'src/models/User.php';

class SettingsController
{
    private $userModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
    }

    /**
     * Menampilkan halaman Settings dengan data user asli
     */
    public function index()
    {
        // 1. Cek Login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // 2. Ambil data user lengkap dari Database
        // (Kita perlu menambahkan method getUserById di User.php)
        $user = $this->userModel->getUserById($user_id);

        if (!$user) {
            $_SESSION['error_message'] = "User data not found.";
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        // 3. Siapkan data untuk View
        // Mapping kolom DB ke variabel view agar sesuai dengan views/settings/index.php
        $userData = [
            'name' => htmlspecialchars($user['NAMA']),
            // Gunakan UI Avatars jika tidak ada foto (atau implementasi upload foto nanti)
            'profile_picture' => 'https://ui-avatars.com/api/?name=' . urlencode($user['NAMA']) . '&size=100&background=4f46e5&color=fff',
            'status' => ucfirst($user['ROLE_NAME'] ?? 'User'), // Menggunakan Role sebagai Status sementara
            'email' => htmlspecialchars($user['EMAIL']),
            'nim_nip' => htmlspecialchars($user['NIM'] ?? $user['NIP'] ?? '-')
        ];

        // 4. Load View
        require 'views/settings/index.php';
    }

    /**
     * Menangani Update Data via AJAX
     */
    public function update()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        // Ambil data JSON
        $input = json_decode(file_get_contents('php://input'), true);

        $field = $input['field'] ?? '';
        $value = trim($input['value'] ?? '');

        // Validasi dasar
        if (empty($field) || empty($value)) {
            echo json_encode(['success' => false, 'message' => 'Input tidak boleh kosong']);
            exit;
        }

        try {
            // Mapping field dari UI ke Kolom Database
            $dbColumn = '';
            if ($field === 'name') {
                $dbColumn = 'nama';
            } else {
                // Untuk saat ini kita hanya izinkan ganti nama dulu
                echo json_encode(['success' => false, 'message' => 'Field tidak valid untuk diedit']);
                exit;
            }

            // Panggil Model untuk update
            $updated = $this->userModel->updateUserField($_SESSION['user_id'], $dbColumn, $value);

            if ($updated) {
                // Update session jika nama berubah
                if ($dbColumn === 'nama') {
                    $_SESSION['nama'] = $value;
                }

                echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui database']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }
}
