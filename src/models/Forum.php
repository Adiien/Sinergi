<?php
class ForumModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ambil semua forum (untuk Discover/My Forums)
    public function getAllForums()
    {
        $query = "SELECT f.*, 
                    (SELECT COUNT(*) FROM forum_members fm WHERE fm.forum_id = f.forum_id) AS member_count
                    FROM forums f
                    WHERE f.visibility = 'public'
                    ORDER BY f.created_at DESC";

        $stmt = oci_parse($this->conn, $query);
        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $forums[] = $row;
    }

    return $forums;
    }

    // Ambil forum yang diikuti user (untuk Sidebar)
    public function getUserJoinedForums($user_id)
    {
        $query = "SELECT f.forum_id, f.name, f.cover_image 
                  FROM forums f
                  JOIN forum_members fm ON f.forum_id = fm.forum_id
                  WHERE fm.user_id = :user_id
                  ORDER BY fm.joined_at DESC FETCH FIRST 5 ROWS ONLY";
        
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt);
        
        $forums = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $forums[] = $row;
        }
        return $forums;
    }
    
/**
     * [UPDATE] Fungsi Create Forum Lengkap
     */
    public function createForum($name, $desc, $visibility, $creator_id, $image = null) {
        // Asumsi tabel 'forums' punya kolom: VISIBILITY (VARCHAR)
        $query = "INSERT INTO forums (forum_id, name, description, visibility, created_by, cover_image, created_at) 
                  VALUES (forums_seq.NEXTVAL, :name, :description_text, :visibility, :creator_id, :img, SYSTIMESTAMP)";
        
        $stmt = oci_parse($this->conn, $query);
        
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':description_text', $desc);
        oci_bind_by_name($stmt, ':visibility', $visibility);
        oci_bind_by_name($stmt, ':creator_id', $creator_id);
        oci_bind_by_name($stmt, ':img', $image);
        
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            throw new Exception("Error Create Forum: " . $e['message']);
        }
        
        // Opsional: Langsung masukkan creator sebagai member (Admin)
        // Anda bisa menambahkan logika insert ke tabel forum_members di sini jika perlu.
        
        oci_free_statement($stmt);
        return true;
    }

    public function getForumsByCreator($user_id)
    {
        $query = "SELECT f.*, 
                    (SELECT COUNT(*) FROM forum_members fm WHERE fm.forum_id = f.forum_id) AS member_count
                    FROM forums f
                    WHERE f.created_by = :user_id
                    ORDER BY f.created_at DESC";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $forums[] = $row;
        }

        return $forums;
    }

}