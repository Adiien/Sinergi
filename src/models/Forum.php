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
                  (SELECT COUNT(*) FROM forum_members fm WHERE fm.forum_id = f.forum_id) as member_count
                  FROM forums f ORDER BY created_at DESC";

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
    public function createForum($name, $desc, $visibility, $creator_id, $image = null)
    {
        // 1. Insert ke tabel FORUMS & Ambil ID Forum Baru
        // [PERBAIKAN] Mengganti :desc menjadi :description untuk menghindari ORA-01745
        $query = "INSERT INTO forums (forum_id, name, description, visibility, created_by, cover_image, created_at) 
                  VALUES (forums_seq.NEXTVAL, :name, :description, :visibility, :creator_id, :img, SYSTIMESTAMP)
                  RETURNING forum_id INTO :new_id";

        $stmt = oci_parse($this->conn, $query);

        // Variabel penampung ID baru
        $new_forum_id = 0;

        oci_bind_by_name($stmt, ':name', $name);

        // [PERBAIKAN] Ubah binding dari :desc ke :description
        oci_bind_by_name($stmt, ':description', $desc);

        oci_bind_by_name($stmt, ':visibility', $visibility);
        oci_bind_by_name($stmt, ':creator_id', $creator_id);
        oci_bind_by_name($stmt, ':img', $image);
        // Bind output ID
        oci_bind_by_name($stmt, ':new_id', $new_forum_id, -1, SQLT_INT);

        // Gunakan NO_AUTO_COMMIT agar kita bisa insert member di transaksi yang sama
        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stmt);
            throw new Exception("Gagal membuat forum: " . $e['message']);
        }

        // 2. Insert Pembuat ke tabel FORUM_MEMBERS (Agar terhitung sebagai member)
        if ($new_forum_id > 0) {
            // Gunakan nama bind yang aman (misal :m_fid, :m_uid) untuk menghindari konflik
            $queryMember = "INSERT INTO forum_members (member_id, forum_id, user_id, joined_at)
                            VALUES (forum_members_seq.NEXTVAL, :m_fid, :m_uid, SYSTIMESTAMP)";

            $stmtMember = oci_parse($this->conn, $queryMember);
            oci_bind_by_name($stmtMember, ':m_fid', $new_forum_id);
            oci_bind_by_name($stmtMember, ':m_uid', $creator_id);

            if (!oci_execute($stmtMember, OCI_NO_AUTO_COMMIT)) {
                oci_rollback($this->conn); // Batalkan pembuatan forum jika gagal insert member
                throw new Exception("Gagal menambahkan admin sebagai member.");
            }
            oci_free_statement($stmtMember);
        }

        // Commit semua perubahan (Forum + Member)
        oci_commit($this->conn);
        oci_free_statement($stmt);

        // Return ID forum baru
        return $new_forum_id;
    }
    // Add inside ForumModel class
    public function getForumById($forum_id)
    {
        $query = "SELECT f.*, 
              (SELECT COUNT(*) FROM forum_members fm WHERE fm.forum_id = f.forum_id) as member_count,
              u.nama as creator_name
              FROM forums f
              JOIN users u ON f.created_by = u.user_id
              WHERE f.forum_id = :forum_id";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':forum_id', $forum_id);
        oci_execute($stmt);

        return oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
    }

    public function isMember($forum_id, $user_id)
    {
        // Perbaikan: Ganti :uid menjadi :p_user_id dan :fid menjadi :p_forum_id
        $query = "SELECT COUNT(*) as CNT FROM forum_members WHERE forum_id = :p_forum_id AND user_id = :p_user_id";
        $stmt = oci_parse($this->conn, $query);

        // Update binding sesuai nama baru
        oci_bind_by_name($stmt, ':p_forum_id', $forum_id);
        oci_bind_by_name($stmt, ':p_user_id', $user_id);

        // Eksekusi
        if (!oci_execute($stmt)) {
            // (Opsional) Debugging jika masih error
            // $e = oci_error($stmt);
            // error_log("Error isMember: " . $e['message']);
            return false;
        }

        $row = oci_fetch_array($stmt, OCI_ASSOC);

        // Cek agar tidak error "access array offset on bool" jika row kosong
        if ($row) {
            return ($row['CNT'] > 0);
        }

        return false;
    }
    // Fungsi untuk Join Forum
    public function addMember($forum_id, $user_id)
    {
        // Cek dulu apakah sudah member
        if ($this->isMember($forum_id, $user_id)) {
            return true;
        }

        // PERBAIKAN: Mengganti :uid menjadi :p_user_id untuk menghindari ORA-01745
        $query = "INSERT INTO forum_members (member_id, forum_id, user_id, joined_at)
                  VALUES (forum_members_seq.NEXTVAL, :p_forum_id, :p_user_id, SYSTIMESTAMP)";

        $stmt = oci_parse($this->conn, $query);

        // Update binding sesuai nama baru
        oci_bind_by_name($stmt, ':p_forum_id', $forum_id);
        oci_bind_by_name($stmt, ':p_user_id', $user_id);

        // Eksekusi (Kembalikan ke normal tanpa var_dump)
        if (oci_execute($stmt)) {
            return true;
        } else {
            // Opsional: Log error jika perlu
            // $e = oci_error($stmt);
            // error_log($e['message']);
            return false;
        }
    }
    /**
     * [UPDATE] Mencari forum dengan opsi Limit dan kolom Visibility
     */
    public function searchForums($keyword, $limit = 5)
    {
        $keyword = strtolower($keyword);

        // Tambahkan 'visibility' ke select
        $query = "SELECT forum_id, name, description, cover_image, visibility, 
                  (SELECT COUNT(*) FROM forum_members fm WHERE fm.forum_id = f.forum_id) as member_count
                  FROM forums f 
                  WHERE LOWER(name) LIKE '%' || :keyword || '%' 
                  ORDER BY member_count DESC";

        // Jika limit > 0, batasi baris. Jika 0 atau null, ambil semua.
        if ($limit > 0) {
            $query .= " FETCH FIRST " . (int)$limit . " ROWS ONLY";
        }

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':keyword', $keyword);
        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $forums[] = $row;
        }
        oci_free_statement($stmt);
        return $forums;
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
    public function getAllPublicForums()
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
    public function getForumMembers($forum_id)
    {
        // Join tabel forum_members dengan users untuk ambil nama & role
        $query = "SELECT u.user_id, u.nama, u.role_name, 
              TO_CHAR(fm.joined_at, 'DD Mon YYYY') as joined_date
              FROM forum_members fm
              JOIN users u ON fm.user_id = u.user_id
              WHERE fm.forum_id = :forum_id
              ORDER BY fm.joined_at ASC"; // Member terlama di atas (biasanya admin/creator)

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':forum_id', $forum_id);
        oci_execute($stmt);

        $members = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $members[] = $row;
        }
        return $members;
    }
    /**
     * [BARU] Update Data Forum
     */
    public function updateForum($forum_id, $name, $description, $visibility, $cover_image = null)
    {
        // Jika ada gambar baru, update kolom cover_image. Jika tidak, biarkan (NVL/Coalesce logic di query atau logic if di PHP)

        $sql = "UPDATE forums SET 
                name = :name, 
                description = :description, 
                visibility = :visibility";

        // Tambahkan update gambar jika tidak null
        if ($cover_image !== null) {
            $sql .= ", cover_image = :cover_image";
        }

        $sql .= " WHERE forum_id = :forum_id";

        $stmt = oci_parse($this->conn, $sql);

        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':description', $description);
        oci_bind_by_name($stmt, ':visibility', $visibility);
        oci_bind_by_name($stmt, ':forum_id', $forum_id);

        if ($cover_image !== null) {
            oci_bind_by_name($stmt, ':cover_image', $cover_image);
        }

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            throw new Exception("Gagal update forum: " . $e['message']);
        }

        oci_free_statement($stmt);
        return true;
    }
}
