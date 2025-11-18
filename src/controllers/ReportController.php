<?php
require_once 'src/models/Report.php';

class ReportController
{
    private $reportModel;
    private $conn;

    public function __construct() {
        $this->conn = koneksi_oracle(); 
        $this->reportModel = new ReportModel($this->conn);
    }

    /**
     * Menerima request AJAX untuk membuat laporan baru
     */
    public function create() {
        // Set header ke JSON karena ini adalah endpoint AJAX
        header('Content-Type: application/json');

        // Pastikan user login
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in.']);
            exit;
        }

        // Ambil data JSON dari body request
        // (Kita pakai JSON.parse di JS, jadi datanya ada di php://input)
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['target_type']) || !isset($data['target_id']) || empty($data['reason'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap (tipe, id, atau alasan kosong).']);
            exit;
        }

        try {
            $user_id = $_SESSION['user_id'];
            $target_type = $data['target_type']; // 'post' atau 'comment'
            $target_id = (int)$data['target_id']; // ID dari post/comment
            $reason = strip_tags($data['reason']); // Keamanan dasar

            $this->reportModel->createReport($user_id, $target_type, $target_id, $reason);
            
            echo json_encode(['success' => true, 'message' => 'Laporan berhasil dikirim.']);
            exit;

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}