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
    public function registerUser($data, $token) // Tambah parameter $token
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $nama = $data['nama'];
        $email = $data['email'];
        $role_name = $data['role_name'];
        // ... (variabel lain tetap sama) ...
        $program_studi = !empty($data['program_studi']) ? $data['program_studi'] : null;
        $tahun_masuk = !empty($data['admission_year']) ? (int)$data['admission_year'] : null;
        $nim = ($role_name == 'mahasiswa' || $role_name == 'alumni') ? $data['nim-nip-input'] : null;
        $nip = ($role_name == 'dosen') ? $data['nim-nip-input'] : null;

        // [UPDATE] Set status default 'pending_email'
        $default_status = 'pending_email';

        $query = 'INSERT INTO users (
                      user_id, nama, email, pass_user, role_name, 
                      nim, nip, program_studi, tahun_masuk,
                      verification_token, status
                  ) VALUES (
                      users_seq.NEXTVAL, :nama, :email, :pass_user, :role_name,
                      :nim, :nip, :program_studi, :tahun_masuk,
                      :token, :status
                  )';

        $stmt = oci_parse($this->conn, $query);

        // ... (bind variabel lama tetap sama) ...
        oci_bind_by_name($stmt, ':nama', $nama);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':pass_user', $hashedPassword);
        oci_bind_by_name($stmt, ':role_name', $role_name);
        oci_bind_by_name($stmt, ':nim', $nim);
        oci_bind_by_name($stmt, ':nip', $nip);
        oci_bind_by_name($stmt, ':program_studi', $program_studi);
        oci_bind_by_name($stmt, ':tahun_masuk', $tahun_masuk);

        // [UPDATE] Bind Token & Status
        oci_bind_by_name($stmt, ':token', $token);
        oci_bind_by_name($stmt, ':status', $default_status);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database: " . $e['message']);
        }

        oci_free_statement($stmt);
        return true;
    }

    /**
     * [PERBAIKAN] Verifikasi Token & Update Status
     * Mengganti :uid menjadi :id_user agar tidak error ORA-01745
     */
    public function verifyUserToken($token)
    {
        // 1. Cari user berdasarkan token
        $query = "SELECT user_id, role_name FROM users WHERE verification_token = :token";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':token', $token);
        oci_execute($stmt);

        $user = oci_fetch_array($stmt, OCI_ASSOC);
        oci_free_statement($stmt);

        if ($user) {
            // 2. Tentukan status baru
            $newStatus = ($user['ROLE_NAME'] == 'alumni') ? 'pending_approval' : 'active';

            // 3. Update status dan hapus token
            // [PERBAIKAN DI SINI] Menggunakan :id_user bukan :uid
            $update = "UPDATE users SET status = :status, verification_token = NULL WHERE user_id = :id_user";

            $stmtUp = oci_parse($this->conn, $update);

            oci_bind_by_name($stmtUp, ':status', $newStatus);
            oci_bind_by_name($stmtUp, ':id_user', $user['USER_ID']); // Bind ke nama variabel baru

            $res = oci_execute($stmtUp, OCI_COMMIT_ON_SUCCESS);
            oci_free_statement($stmtUp);

            return $res ? $newStatus : false;
        }

        return false; // Token tidak ditemukan
    }

    /**
     * [BARU] Update Token Verifikasi (Untuk fitur Resend)
     */
    public function updateVerificationToken($user_id, $newToken)
    {
        // [PERBAIKAN] Ganti :uid menjadi :id_user
        $query = "UPDATE users SET verification_token = :token WHERE user_id = :id_user";

        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ':token', $newToken);
        oci_bind_by_name($stmt, ':id_user', $user_id); // Ganti binding juga

        // Eksekusi (Mode Debugging bisa dihapus jika sudah berhasil)
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            // Anda bisa mengembalikan error log jika perlu, atau throw exception
            // die("Oracle Error: " . $e['message']); 
            return false;
        }

        oci_free_statement($stmt);
        return $result;
    }

    /**
     * [UPDATE] Login dengan Cek Status
     */
    public function loginUser($identifier, $password = null)
    {
        // ... (Query SELECT sama seperti file lama Anda) ...
        $query = 'SELECT * FROM users WHERE email = :identifier OR nim = :identifier OR nip = :identifier';
        // ... (Execute query) ...
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':identifier', $identifier);
        oci_execute($stmt);
        $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
        oci_free_statement($stmt);

        if ($row) {
            // Cek Password
            $dbPassword = $row['PASS_USER'] ?? null;
            if ($dbPassword && isset($password) && password_verify($password, $dbPassword)) {

                // [LOGIKA BARU] Cek Status
                $status = $row['STATUS'] ?? 'active'; // Default active untuk user lama

                if ($status == 'pending_email') {
                    throw new Exception("Email Anda belum diverifikasi. Silakan cek inbox Anda.");
                }
                if ($status == 'pending_approval') {
                    throw new Exception("Akun Alumni Anda sedang menunggu persetujuan Admin.");
                }
                if ($status == 'banned' || $status == 'inactive') {
                    throw new Exception("Akun Anda dinonaktifkan.");
                }

                return $row; // Login Sukses
            }
        }
        return false;
    }
    /**
     * Mengambil semua pengguna untuk dasbor admin
     */
    public function getAllUsers()
    {
        // [PERBAIKAN] Tambahkan kolom 'status' di sini agar tombol Resend bisa muncul
        $query = 'SELECT user_id, nama, email, role_name, nim, nip, program_studi, status
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
     * [PERBAIKAN FINAL - ANTI ORA-01745]
     * Menggunakan nama bind variable ':del_id' karena ':uid' adalah reserved word Oracle.
     */
    public function deleteUserById($user_id)
    {
        // ------------------------------------------------------------------
        // TAHAP 1: Hapus Data di Tabel yang TIDAK MEMILIKI CASCADE
        // ------------------------------------------------------------------

        // 1. Hapus Pesan (Sender & Receiver)
        // Gunakan :del_id_1 dan :del_id_2 untuk query dengan OR
        $sqlMsg = "DELETE FROM messages WHERE sender_id = :del_id_1 OR receiver_id = :del_id_2";
        $stmtM = oci_parse($this->conn, $sqlMsg);
        oci_bind_by_name($stmtM, ':del_id_1', $user_id);
        oci_bind_by_name($stmtM, ':del_id_2', $user_id);
        if (!oci_execute($stmtM, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stmtM);
            throw new Exception("Gagal menghapus pesan user: " . $e['message']);
        }
        oci_free_statement($stmtM);

        // 2. Hapus Laporan (Reports)
        // [FIX] Ganti :uid menjadi :del_id
        $sqlRep = "DELETE FROM reports WHERE user_id = :del_id";
        $stmtR = oci_parse($this->conn, $sqlRep);
        oci_bind_by_name($stmtR, ':del_id', $user_id);
        if (!oci_execute($stmtR, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stmtR);
            throw new Exception("Gagal menghapus laporan user: " . $e['message']);
        }
        oci_free_statement($stmtR);

        // 3. Hapus Notifikasi
        $sqlNotif = "DELETE FROM notifications WHERE user_id = :del_id_1 OR actor_id = :del_id_2";
        $stmtN = oci_parse($this->conn, $sqlNotif);
        oci_bind_by_name($stmtN, ':del_id_1', $user_id);
        oci_bind_by_name($stmtN, ':del_id_2', $user_id);
        if (!oci_execute($stmtN, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stmtN);
            throw new Exception("Gagal menghapus notifikasi user: " . $e['message']);
        }
        oci_free_statement($stmtN);

        // ------------------------------------------------------------------
        // TAHAP 2: Hapus Data Lain (Follows & Interaksi)
        // ------------------------------------------------------------------

        // 4. Hapus Follows
        $sqlFollow = "DELETE FROM follows WHERE follower_id = :del_id_1 OR following_id = :del_id_2";
        $stmtF = oci_parse($this->conn, $sqlFollow);
        oci_bind_by_name($stmtF, ':del_id_1', $user_id);
        oci_bind_by_name($stmtF, ':del_id_2', $user_id);
        oci_execute($stmtF, OCI_NO_AUTO_COMMIT);
        oci_free_statement($stmtF);

        // 5. Hapus Likes & Votes
        $tables = ['post_likes', 'comment_likes', 'poll_votes'];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE user_id = :del_id";
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt, ':del_id', $user_id);
            oci_execute($stmt, OCI_NO_AUTO_COMMIT);
            oci_free_statement($stmt);
        }

        // ------------------------------------------------------------------
        // TAHAP 3: Hapus Forum & Member (Pembersihan Ekstra)
        // ------------------------------------------------------------------

        // Hapus user dari semua forum
        $sqlExit = "DELETE FROM forum_members WHERE user_id = :del_id";
        $stmtExit = oci_parse($this->conn, $sqlExit);
        oci_bind_by_name($stmtExit, ':del_id', $user_id);
        oci_execute($stmtExit, OCI_NO_AUTO_COMMIT);
        oci_free_statement($stmtExit);

        // Hapus forum yang dibuat user (Beserta isinya agar tidak error foreign key)

        // a. Ambil ID Forum buatan user
        $sqlGetF = "SELECT forum_id FROM forums WHERE created_by = :del_id";
        $stmtGetF = oci_parse($this->conn, $sqlGetF);
        oci_bind_by_name($stmtGetF, ':del_id', $user_id);
        oci_execute($stmtGetF);

        $myForumIds = [];
        while ($row = oci_fetch_array($stmtGetF, OCI_ASSOC)) {
            $myForumIds[] = $row['FORUM_ID'];
        }
        oci_free_statement($stmtGetF);

        // b. Jika ada forum, hapus isinya dulu
        if (!empty($myForumIds)) {
            $idList = implode(',', $myForumIds);

            // Hapus Child dari Postingan di forum ini
            // (Likes, Comments, Images, Polls) - disederhanakan dengan subquery
            $sqls = [
                "DELETE FROM post_likes WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList))",
                "DELETE FROM comment_likes WHERE comment_id IN (SELECT comment_id FROM comments WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList)))",
                "DELETE FROM comments WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList))",
                "DELETE FROM post_images WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList))",
                "DELETE FROM poll_votes WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList))",
                "DELETE FROM poll_options WHERE post_id IN (SELECT post_id FROM posts WHERE forum_id IN ($idList))",
                // Hapus Postingan
                "DELETE FROM posts WHERE forum_id IN ($idList)",
                // Hapus Member lain
                "DELETE FROM forum_members WHERE forum_id IN ($idList)"
            ];

            foreach ($sqls as $sql) {
                $s = oci_parse($this->conn, $sql);
                oci_execute($s, OCI_NO_AUTO_COMMIT);
            }

            // c. Hapus Forumnya
            $sqlDelForum = "DELETE FROM forums WHERE forum_id IN ($idList)";
            $stmtDF = oci_parse($this->conn, $sqlDelForum);
            oci_execute($stmtDF, OCI_NO_AUTO_COMMIT);
        }

        // ------------------------------------------------------------------
        // TAHAP 4: Hapus User Utama
        // ------------------------------------------------------------------
        $query = 'DELETE FROM users WHERE user_id = :del_id';
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':del_id', $user_id);

        // Eksekusi dan COMMIT
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (Delete User): " . $e['message']);
        }

        $rows_affected = oci_num_rows($stmt);
        oci_free_statement($stmt);

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
    // 1. Cek Email (Hanya untuk memastikan user terdaftar)
    public function getUserByEmail($email)
    {
        $query = "SELECT nama FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':email', $email);
        oci_execute($stmt);
        return oci_fetch_array($stmt, OCI_ASSOC);
    }

    // 2. Update Password Baru (Tanpa cek token di DB)
    public function updatePasswordByEmail($email, $newPassword)
    {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE users SET pass_user = :pass WHERE email = :email";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':pass', $hashed);
        oci_bind_by_name($stmt, ':email', $email);

        return oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    }

    public function countAll()
    {
        $q = "SELECT COUNT(*) AS TOTAL FROM users";
        $s = oci_parse($this->conn, $q);
        oci_execute($s);
        return oci_fetch_assoc($s)['TOTAL'];
    }

    public function countActive()
    {
        $q = "SELECT COUNT(*) AS TOTAL FROM users WHERE status = 'active'";
        $s = oci_parse($this->conn, $q);
        oci_execute($s);
        return oci_fetch_assoc($s)['TOTAL'];
    }

}
