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
        $query = "SELECT 
                    r.report_id, r.target_type, r.target_id, r.reason, r.status,
                    TO_CHAR(r.created_at, 'YYYY-MM-DD HH24:MI') AS created_at,
                    u.nama AS reporter_name,
                    p.content AS post_content -- (Hanya jika target_type = 'post')
                  FROM 
                    reports r
                  JOIN 
                    users u ON r.user_id = u.user_id
                  LEFT JOIN
                    posts p ON r.target_id = p.post_id AND r.target_type = 'post'
                  WHERE 
                    r.status = 'pending'
                  ORDER BY 
                    r.created_at ASC";
        
        $stmt = oci_parse($this->conn, $query);
        oci_execute($stmt);
        $reports = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            // Ambil data CLOB jika itu adalah objek
            if (is_object($row['POST_CONTENT'])) {
                $row['POST_CONTENT'] = $row['POST_CONTENT']->load();
            }
            $reports[] = $row;
        }
        oci_free_statement($stmt);
        return $reports;
    }
    
    // Anda bisa tambahkan fungsi lain di sini nanti
    // Misalnya: updateReportStatus($report_id, $new_status)
}