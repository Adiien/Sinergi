<?php
class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Registrasi user baru
     * Kueri ini sudah benar: "pass_user" (lowercase) dengan tanda kutip
     */
    public function registerUser($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $nama = $data['nama'];
        $email = $data['email'];
        $role_name = $data['role_name'];
        $program_studi = !empty($data['program_studi']) ? $data['program_studi'] : null;

        $tahun_masuk = null;
        if (!empty($data['admission_year']) && is_numeric($data['admission_year'])) {
            $tahun_masuk = (int)$data['admission_year'];
        }

        $nim = null;
        $nip = null;
        if ($role_name == 'mahasiswa' || $role_name == 'alumni') {
            $nim = $data['nim-nip-input'];
        } elseif ($role_name == 'dosen') {
            $nip = $data['nim-nip-input'];
        }

        $query = 'INSERT INTO users (
                      user_id, nama, email, pass_user, role_name, 
                      nim, nip, program_studi, tahun_masuk
                  ) VALUES (
                      users_seq.NEXTVAL, :nama, :email, :pass_user, :role_name,
                      :nim, :nip, :program_studi, :tahun_masuk
                  )';

        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ':nama', $nama);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':pass_user', $hashedPassword);
        oci_bind_by_name($stmt, ':role_name', $role_name);
        oci_bind_by_name($stmt, ':nim', $nim);
        oci_bind_by_name($stmt, ':nip', $nip);
        oci_bind_by_name($stmt, ':program_studi', $program_studi);
        oci_bind_by_name($stmt, ':tahun_masuk', $tahun_masuk);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database: " . $e['message'] . " (Query: " . $e['sqltext'] . ")");
        }

        oci_free_statement($stmt);
        return true;
    }

    public function loginUser($identifier, $password = null)
    {
        $query = 'SELECT * FROM users 
              WHERE email = :identifier 
                 OR nim = :identifier 
                 OR nip = :identifier';

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':identifier', $identifier);
        $exec = oci_execute($stmt);

        if (!$exec) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database: " . $e['message'] . " (Query: " . $e['sqltext'] . ")");
        }

        $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
        oci_free_statement($stmt);

        if ($row) {
            $dbPassword = isset($row['PASS_USER']) ? $row['PASS_USER'] : (isset($row['pass_user']) ? $row['pass_user'] : null);
            if ($dbPassword && isset($password) && password_verify($password, $dbPassword)) {
                return $row;
            }
        }

        return false;
    }
    /**
     * Mengambil semua pengguna untuk dasbor admin
     */
    public function getAllUsers()
    {
        // Ambil kolom yang relevan, urutkan berdasarkan nama
        $query = 'SELECT user_id, nama, email, role_name, nim, nip, program_studi 
                  FROM users 
                  ORDER BY nama ASC';

        $stmt = oci_parse($this->conn, $query);
        $exec = oci_execute($stmt);

        if (!$exec) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (getAllUsers): " . $e['message']);
        }

        $users = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $users[] = $row;
        }

        oci_free_statement($stmt);
        return $users;
    }
    /**
     * [BARU] Menghapus pengguna berdasarkan ID
     */
    public function deleteUserById($user_id)
    {
        $query = 'DELETE FROM users WHERE user_id = :user_id';

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (Delete User): " . $e['message']);
        }

        // oci_num_rows() akan mengembalikan jumlah baris yang terpengaruh
        $rows_affected = oci_num_rows($stmt);

        oci_free_statement($stmt);

        // Mengembalikan true jika 1 baris (pengguna) terhapus
        return $rows_affected > 0;
    }

    /**
     * [UPDATE] Toggle Follow/Unfollow (Sesuai tabel FOLLOWS Anda)
     */
    public function toggleFollow($follower_id, $following_id)
    {
        // 1. Cek apakah sudah follow
        $query_check = "SELECT COUNT(*) AS CNT FROM FOLLOWS 
                        WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt_check = oci_parse($this->conn, $query_check);
        oci_bind_by_name($stmt_check, ':follower_id', $follower_id);
        oci_bind_by_name($stmt_check, ':following_id', $following_id);
        oci_execute($stmt_check);
        $row = oci_fetch_array($stmt_check, OCI_ASSOC);
        $is_following = ($row['CNT'] > 0);
        oci_free_statement($stmt_check);

        if ($is_following) {
            // UNFOLLOW
            $query = "DELETE FROM FOLLOWS WHERE follower_id = :follower_id AND following_id = :following_id";
            $status = 'unfollowed';
        } else {
            // FOLLOW
            $query = "INSERT INTO FOLLOWS (follower_id, following_id) 
                      VALUES (:follower_id, :following_id)";
            $status = 'followed';
        }

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':follower_id', $follower_id);
        oci_bind_by_name($stmt, ':following_id', $following_id);

        // Eksekusi query
        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT); // Jangan auto commit dulu

        if (!$result) {
            $e = oci_error($stmt);
            return false;
        }

        // Commit Eksplisit agar data PASTI tersimpan
        oci_commit($this->conn);

        oci_free_statement($stmt);

        return $status;
    }

    /**
     * [UPDATE] Mengambil saran teman (Hanya yang BELUM di-follow)
     */
    public function getSuggestedUsers($exclude_user_id, $limit = 3)
    {
        // Menggunakan 'following_id' dalam subquery
        $query = "SELECT user_id, nama, role_name 
                  FROM users 
                  WHERE user_id != :user_id 
                  AND user_id NOT IN (SELECT following_id FROM FOLLOWS WHERE follower_id = :user_id)
                  ORDER BY DBMS_RANDOM.VALUE 
                  FETCH FIRST :limit_rows ROWS ONLY";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $exclude_user_id);
        oci_bind_by_name($stmt, ':limit_rows', $limit);

        oci_execute($stmt);

        $users = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
            $users[] = $row;
        }
        oci_free_statement($stmt);
        return $users;
    }

    /**
     * [UPDATE PERBAIKAN] Mengambil jumlah Followers dan Following
     * Mengganti nama bind :uid menjadi :user_id_val untuk menghindari konflik reserved word Oracle
     */
    public function getFollowStats($user_id)
    {
        // 1. Hitung Followers (Orang yang follow saya -> saya sebagai 'following_id')
        $q1 = "SELECT COUNT(*) AS CNT FROM FOLLOWS WHERE following_id = :user_id_val";
        $s1 = oci_parse($this->conn, $q1);
        oci_bind_by_name($s1, ':user_id_val', $user_id);
        oci_execute($s1);
        $r1 = oci_fetch_array($s1, OCI_ASSOC);
        $followers = $r1 ? $r1['CNT'] : 0;
        oci_free_statement($s1);

        // 2. Hitung Following (Orang yang saya follow -> saya sebagai 'follower_id')
        $q2 = "SELECT COUNT(*) AS CNT FROM FOLLOWS WHERE follower_id = :user_id_val";
        $s2 = oci_parse($this->conn, $q2);
        oci_bind_by_name($s2, ':user_id_val', $user_id);
        oci_execute($s2);
        $r2 = oci_fetch_array($s2, OCI_ASSOC);
        $following = $r2 ? $r2['CNT'] : 0;
        oci_free_statement($s2);

        return ['followers' => $followers, 'following' => $following];
    }
    /**
     * [UPDATE] Mencari pengguna + Status Follow
     */
    public function searchUsers($keyword, $current_user_id) // Tambah parameter $current_user_id
    {
        $keyword = strtolower($keyword);

        // Tambahkan subquery (SELECT COUNT...) AS IS_FOLLOWING
        $query = "SELECT u.user_id, u.nama, u.role_name, u.nim, u.nip,
                         (SELECT COUNT(*) FROM FOLLOWS f WHERE f.follower_id = :current_user_id AND f.following_id = u.user_id) AS IS_FOLLOWING
                  FROM users u 
                  WHERE LOWER(u.nama) LIKE '%' || :keyword || '%' 
                     OR u.nim LIKE '%' || :keyword || '%' 
                     OR u.nip LIKE '%' || :keyword || '%'
                  ORDER BY u.nama ASC";

        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ':keyword', $keyword);
        oci_bind_by_name($stmt, ':current_user_id', $current_user_id); // Bind ID user login

        oci_execute($stmt);

        $users = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $users[] = $row;
        }

        oci_free_statement($stmt);
        return $users;
    }
    /**
     * [TAMBAHAN] Ambil data user lengkap berdasarkan ID
     */
    public function getUserById($user_id)
    {
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt);

        $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
        oci_free_statement($stmt);

        return $row;
    }

    /**
     * [TAMBAHAN] Update satu kolom user
     */
    public function updateUserField($user_id, $column, $value)
    {
        // Whitelist kolom agar aman dari SQL Injection
        $allowed_columns = ['nama', 'email', 'pass_user'];
        if (!in_array($column, $allowed_columns)) {
            return false;
        }

        $query = "UPDATE users SET $column = :val WHERE user_id = :user_id";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':val', $value);
        oci_bind_by_name($stmt, ':user_id', $user_id);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stmt);

        return $result;
    }

    public function getAllUsersExcept($user_id)
    {
        $query = 'SELECT user_id, nama, email, role_name, nim, nip, program_studi
                  FROM users
                  WHERE user_id != :user_id
                  ORDER BY nama ASC';

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);

        $exec = oci_execute($stmt);

        if (!$exec) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (getAllUsersExcept): " . $e['message']);
        }

        $users = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $users[] = $row;
        }

        oci_free_statement($stmt);
        return $users;
    }

    public function getContactsWithLastMessage($me)
{
    $sql = "
        WITH conv AS (
            SELECT
                CASE 
                    WHEN m.sender_id = :me THEN m.receiver_id
                    ELSE m.sender_id
                END AS contact_id,
                m.content,
                m.msg_type,
                m.created_at,
                ROW_NUMBER() OVER (
                    PARTITION BY CASE 
                        WHEN m.sender_id = :me THEN m.receiver_id
                        ELSE m.sender_id
                    END
                    ORDER BY m.created_at DESC
                ) AS rn
            FROM messages m
            WHERE m.sender_id = :me OR m.receiver_id = :me
        ),
        unread AS (
            SELECT
                m.sender_id AS contact_id,
                COUNT(*)    AS unread_count
            FROM messages m
            WHERE 
                m.receiver_id = :me
                AND m.is_read = 'N'
            GROUP BY m.sender_id
        )
        SELECT
            u.user_id,
            u.nama,
            u.email,
            u.role_name,

            c.content    AS last_content,
            c.msg_type   AS last_msg_type,
            c.created_at AS last_message_raw,
            TO_CHAR(
                c.created_at,
                'MON DD, HH:MI AM',
                'NLS_DATE_LANGUAGE=ENGLISH'
            ) AS last_message_at,

            NVL(unread.unread_count, 0) AS unread_count

        FROM users u
        LEFT JOIN conv   c   ON c.contact_id = u.user_id AND c.rn = 1
        LEFT JOIN unread unread ON unread.contact_id = u.user_id
        WHERE u.user_id != :me
        ORDER BY 
          last_message_raw DESC NULLS LAST,
          u.nama ASC
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':me', $me);
    oci_execute($stmt);

    $result = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $result[] = $row;
    }

    return $result;
}



}
