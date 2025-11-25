<?php
require_once 'src/models/User.php';

class UserController
{
    private $userModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->userModel = new User($this->conn);
    }

    public function follow()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        // ID user yang ingin di-follow
        $target_id = $_GET['id'] ?? null;
        
        // Mencegah Self-Follow (Sesuai constraint database Anda)
        if ($target_id == $_SESSION['user_id']) {
             echo json_encode(['success' => false, 'message' => 'Cannot follow yourself']);
             exit;
        }

        if (!$target_id) {
            echo json_encode(['success' => false, 'message' => 'No ID provided']);
            exit;
        }

        try {
            $status = $this->userModel->toggleFollow($_SESSION['user_id'], $target_id);
            
            if ($status) {
                echo json_encode(['success' => true, 'status' => $status]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
public function ajaxSearch()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

        if (empty($keyword)) {
            echo json_encode([]);
            exit;
        }

        try {
            // [PERBAIKAN] Kirim ID user yang sedang login ke Model
            $current_user_id = $_SESSION['user_id'];
            $results = $this->userModel->searchUsers($keyword, $current_user_id);
            
            // Filter: Hapus diri sendiri dari hasil
            $filteredResults = array_filter($results, function($user) use ($current_user_id) {
                return $user['USER_ID'] != $current_user_id;
            });

            echo json_encode(array_values($filteredResults));
        } catch (Exception $e) {
            echo json_encode([]);
        }
        exit;
    }
}