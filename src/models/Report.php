<?php
class ReportModel
{
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Menyimpan laporan baru ke database
     */
    public function createReport($user_id, $target_type, $target_id, $reason) {
        $query = 'INSERT INTO reports (user_id, target_type, target_id, reason)
                  VALUES (:user_id, :target_type, :target_id, :reason)';
        
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_bind_by_name($stmt, ':target_type', $target_type);
        oci_bind_by_name($stmt, ':target_id', $target_id);
        oci_bind_by_name($stmt, ':reason', $reason);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (Create Report): " . $e['message']);
        }
        oci_free_statement($stmt);
        return true;
    }

    /**
     * Mengambil semua laporan yang masih 'pending' untuk admin
     * Menggabungkan data pelapor dan konten postingan (jika ada)
     */
    public function getPendingReports() {
        // PERBAIKAN: Menambahkan pengambilan email pelapor & terlapor
        $query = "SELECT 
                    r.report_id, 
                    r.target_type, 
                    r.target_id, 
                    r.reason, 
                    r.status,
                    TO_CHAR(r.created_at, 'YYYY-MM-DD HH24:MI') AS created_at,
                    u_pelapor.nama AS reporter_name,
                    u_pelapor.email AS reporter_email,  -- (1) Ambil Email Pelapor
                    u_target.email AS target_email,     -- (2) Ambil Email Terlapor
                    p.content AS post_content
                  FROM 
                    reports r
                  JOIN 
                    users u_pelapor ON r.user_id = u_pelapor.user_id
                  LEFT JOIN
                    posts p ON r.target_id = p.post_id AND r.target_type = 'post'
                  LEFT JOIN
                    users u_target ON p.user_id = u_target.user_id -- (3) Join ke pemilik post
                  WHERE 
                    r.status = 'pending'
                  ORDER BY 
                    r.created_at ASC";
        
        $stmt = oci_parse($this->conn, $query);
        oci_execute($stmt);
        $reports = [];
        
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            // Handle CLOB
            if (isset($row['POST_CONTENT']) && is_object($row['POST_CONTENT'])) {
                $row['POST_CONTENT'] = $row['POST_CONTENT']->load();
            }
            
            // Jaga-jaga kalau target email kosong (misal targetnya komentar, bukan post)
            if (empty($row['TARGET_EMAIL'])) {
                $row['TARGET_EMAIL'] = '-';
            }
            
            $reports[] = $row;
        }
        oci_free_statement($stmt);
        return $reports;
    }
    public function getReportById($report_id) {
        $query = "SELECT 
                    r.report_id, r.target_type, r.target_id, r.reason, r.status,
                    TO_CHAR(r.created_at, 'YYYY-MM-DD HH24:MI') AS created_at,
                    u_pelapor.nama AS reporter_name,
                    u_pelapor.email AS reporter_email,
                    -- Join ke user terlapor (pemilik konten) via Post
                    u_terlapor.nama AS target_user_name,
                    u_terlapor.email AS target_user_email,
                    p.content AS post_content,
                    p.image_path AS post_image
                  FROM 
                    reports r
                  JOIN 
                    users u_pelapor ON r.user_id = u_pelapor.user_id
                  LEFT JOIN
                    posts p ON r.target_id = p.post_id AND r.target_type = 'post'
                  LEFT JOIN
                    users u_terlapor ON p.user_id = u_terlapor.user_id
                  WHERE 
                    r.report_id = :rid";
        
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':rid', $report_id);
        oci_execute($stmt);
        
        $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
        oci_free_statement($stmt);

        if ($row) {
            // Handle CLOB jika konten panjang
            if (isset($row['POST_CONTENT']) && is_object($row['POST_CONTENT'])) {
                $row['POST_CONTENT'] = $row['POST_CONTENT']->load();
            }
        }
        return $row;
    }

    /**
     * [BARU] Update status laporan (misal: 'resolved', 'dismissed')
     */
    public function updateStatus($report_id, $status) {
        $query = "UPDATE reports SET status = :status WHERE report_id = :rid";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':status', $status);
        oci_bind_by_name($stmt, ':rid', $report_id);
        
        $res = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stmt);
        return $res;
    }

    public function countPending() {
        $query = "SELECT COUNT(*) FROM reports WHERE status = 'pending'";
        $stmt = oci_parse($this->conn, $query);
        oci_execute($stmt);
        
        $row = oci_fetch_array($stmt, OCI_NUM);
        return isset($row[0]) ? (int)$row[0] : 0;
    }

    public function resolveAllByTarget($targetType, $targetId)
{
    $sql = "UPDATE reports 
            SET status = 'reviewed' 
            WHERE target_type = :type 
              AND target_id = :id 
              AND status = 'pending'";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':type', $targetType);
    oci_bind_by_name($stmt, ':id', $targetId);

    $res = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if (!$res) {
        $e = oci_error($stmt);
        oci_free_statement($stmt);
        throw new Exception('Gagal update laporan terkait: ' . $e['message']);
    }

    oci_free_statement($stmt);
    return true;
}

    
    // Anda bisa tambahkan fungsi lain di sini nanti
    // Misalnya: updateReportStatus($report_id, $new_status)
}