<?php
class Post
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    /**
     * Membuat postingan baru (Support Gambar & Visibility)
     */
    // [UBAH] Tambahkan parameter $visibility dengan default 'public'
    public function createPost($user_id, $content, $image_path = null, $visibility = 'public') 
    {
        // [UBAH] Tambahkan kolom visibility di INSERT dan :visibility di VALUES
        $query = 'INSERT INTO posts (post_id, user_id, content, image_path, visibility, created_at)
                  VALUES (posts_seq.NEXTVAL, :user_id, EMPTY_CLOB(), :image_path, :visibility, SYSTIMESTAMP)
                  RETURNING content INTO :content_clob';

        $stmt = oci_parse($this->conn, $query);
        $clob = oci_new_descriptor($this->conn, OCI_D_LOB);

        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_bind_by_name($stmt, ':image_path', $image_path);
        
        // [UBAH] Bind parameter visibility
        oci_bind_by_name($stmt, ':visibility', $visibility);
        
        oci_bind_by_name($stmt, ':content_clob', $clob, -1, OCI_B_CLOB);

        // Eksekusi
        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        
        if (!$result) {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }

        $clob->save($content);
        oci_commit($this->conn); // Commit perubahan

        return true;
    }
    /**
     * [BARU] Menghitung total postingan milik seorang user
     */
    public function getPostCountByUserId($user_id)
    {
        $query = 'SELECT COUNT(*) AS POST_COUNT FROM posts WHERE user_id = :user_id';

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (getPostCount): " . $e['message']);
        }

        $row = oci_fetch_array($stmt, OCI_ASSOC);
        oci_free_statement($stmt);

        return $row ? (int)$row['POST_COUNT'] : 0;
    }

    /**
     * [BARU] Menghapus postingan berdasarkan ID
     * Memerlukan ID postingan, ID pengguna yang mencoba menghapus,
     * dan status admin pengguna tersebut.
     */
    public function deletePostById($post_id, $user_id, $is_admin = false)
    {
        // Admin bisa menghapus postingan siapa saja
        if ($is_admin) {
            $query = 'DELETE FROM posts WHERE post_id = :post_id';
        } else {
            // Pengguna biasa hanya bisa menghapus postingan mereka sendiri
            $query = 'DELETE FROM posts WHERE post_id = :post_id AND user_id = :user_id';
        }

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':post_id', $post_id);

        // Hanya bind user_id jika bukan admin
        if (!$is_admin) {
            oci_bind_by_name($stmt, ':user_id', $user_id);
        }

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (Delete Post): " . $e['message']);
        }

        // oci_num_rows() akan mengembalikan jumlah baris yang terpengaruh
        $rows_affected = oci_num_rows($stmt);

        oci_free_statement($stmt);

        // Mengembalikan true jika 1 baris (postingan) terhapus
        return $rows_affected > 0;
    }

    /**
     * Menambah atau menghapus (toggle) like
     * (Versi ini DIPERBARUI untuk mengembalikan data JSON)
     */
    public function toggleLike($post_id, $user_id)
    {
        // 1. Cek dulu apakah user sudah like post ini
        $query_check = 'SELECT COUNT(*) AS LIKE_COUNT FROM post_likes 
                        WHERE post_id = :post_id AND user_id = :user_id';

        $stmt_check = oci_parse($this->conn, $query_check);
        oci_bind_by_name($stmt_check, ':post_id', $post_id);
        oci_bind_by_name($stmt_check, ':user_id', $user_id);
        oci_execute($stmt_check);

        $row = oci_fetch_array($stmt_check, OCI_ASSOC);
        $is_liked = ($row && $row['LIKE_COUNT'] > 0);
        oci_free_statement($stmt_check);

        if ($is_liked) {
            // 2. Jika sudah like, hapus like (unlike)
            $query_toggle = 'DELETE FROM post_likes 
                             WHERE post_id = :post_id AND user_id = :user_id';
        } else {
            // 3. Jika belum, tambahkan like
            $query_toggle = 'INSERT INTO post_likes (like_id, post_id, user_id) 
                             VALUES (post_likes_seq.NEXTVAL, :post_id, :user_id)';
        }

        $stmt_toggle = oci_parse($this->conn, $query_toggle);
        oci_bind_by_name($stmt_toggle, ':post_id', $post_id);
        oci_bind_by_name($stmt_toggle, ':user_id', $user_id);
        $result = oci_execute($stmt_toggle, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt_toggle);
            oci_free_statement($stmt_toggle);
            throw new Exception("Error Database (Toggle Like): " . $e['message']);
        }
        oci_free_statement($stmt_toggle);

        // --- [BAGIAN BARU] ---
        // 4. Ambil jumlah like yang baru setelah di-toggle
        $query_count = 'SELECT COUNT(*) AS NEW_LIKE_COUNT FROM post_likes WHERE post_id = :post_id';
        $stmt_count = oci_parse($this->conn, $query_count);
        oci_bind_by_name($stmt_count, ':post_id', $post_id);
        oci_execute($stmt_count);

        $row_count = oci_fetch_array($stmt_count, OCI_ASSOC);
        $new_like_count = $row_count ? (int)$row_count['NEW_LIKE_COUNT'] : 0;
        oci_free_statement($stmt_count);

        // 5. Kembalikan data sebagai array
        //    Status 'isLiked' yang baru adalah kebalikan dari status 'is_liked' yang lama
        return [
            'isLiked' => !$is_liked,
            'newLikeCount' => $new_like_count
        ];
        // --- [AKHIR BAGIAN BARU] ---
    }
    /**
     * Menambahkan komentar baru
     */
    public function addComment($post_id, $user_id, $content, $parent_comment_id = null) // <--- PERUBAHAN
    {
        // Mirip dengan createPost, kita pakai CLOB
        // [UBAH] Tambahkan parent_comment_id ke INSERT dan :parent_id ke VALUES
        $query = 'INSERT INTO comments (comment_id, post_id, user_id, content, parent_comment_id, created_at)
                  VALUES (comments_seq.NEXTVAL, :post_id, :user_id, EMPTY_CLOB(), :parent_id, SYSTIMESTAMP)
                  RETURNING content INTO :content_clob'; // <--- PERUBAHAN

        $stmt = oci_parse($this->conn, $query);

        $clob = oci_new_descriptor($this->conn, OCI_D_LOB);

        oci_bind_by_name($stmt, ':post_id', $post_id);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        
        // [BARU] Bind parameter parent_comment_id
        oci_bind_by_name($stmt, ':parent_id', $parent_comment_id); // <--- BARU
        
        oci_bind_by_name($stmt, ':content_clob', $clob, -1, OCI_B_CLOB);

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            oci_rollback($this->conn);
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            $clob->free();
            throw new Exception("Error Database (Insert Comment): " . $e['message']);
        }

        if (!$clob->save($content)) {
            oci_rollback($this->conn);
            oci_free_statement($stmt);
            $clob->free();
            throw new Exception("Error Database (Save Comment CLOB): Gagal menyimpan data komentar.");
        }

        oci_commit($this->conn);
        $clob->free();
        oci_free_statement($stmt);
        return true;
    }

    /**
     * Mengambil semua komentar untuk sekumpulan ID postingan
     * (Versi ini mengambil SEMUA komentar, tidak dibatasi 3)
     */
    public function getCommentsForPosts(array $post_ids)
    {
        if (empty($post_ids)) {
            return [];
        }

        // Membuat placeholder binding: (:id_0, :id_1, :id_2, ...)
        $placeholders = [];
        foreach ($post_ids as $key => $id) {
            $placeholders[] = ":id_$key";
        }
        $in_clause = implode(',', $placeholders);

        // Kueri mengambil komentar, nama user, DAN parent_comment_id
        $query = 'SELECT 
                    c.comment_id, 
                    c.post_id, 
                    c.content, 
                    c.parent_comment_id, -- <--- BARU: Ambil ID komentar induk
                    TO_CHAR(c.created_at, \'YYYY-MM-DD"T"HH24:MI:SS\') AS created_at,
                    u.user_id,
                    u.nama
                  FROM 
                    comments c
                  JOIN 
                    users u ON c.user_id = u.user_id
                  WHERE 
                    c.post_id IN (' . $in_clause . ')
                  ORDER BY 
                    c.post_id ASC, 
                    c.parent_comment_id NULLS FIRST, -- Agar komentar utama di atas
                    c.created_at ASC'; // <--- PERUBAHAN ORDER BY

        $stmt = oci_parse($this->conn, $query);

        // Bind semua ID di array $post_ids
        foreach ($post_ids as $key => $id) {
            // Kita perlu meneruskan nilai aktual ke oci_bind_by_name
            // jadi kita simpan dulu di variabel
            // <-- MASALAHNYA DI SINI
            oci_bind_by_name($stmt, ":id_$key", $post_ids[$key]);
        }

        $exec = oci_execute($stmt);

        if (!$exec) {
            $e = oci_error($stmt);
            oci_free_statement($stmt);
            throw new Exception("Error Database (getCommentsForPosts): " . $e['message']);
        }

        $comments = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            // Baca data CLOB
            if (is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $comments[] = $row;
        }

        oci_free_statement($stmt);
        return $comments;
    }

/**
     * Mengambil semua postingan untuk feed
     * [UPDATE] Sekarang mengambil status IS_LIKED
     */
    public function getFeedPosts($current_user_id)
    {
        $query = 'SELECT 
                    p.post_id, 
                    p.content,
                    p.image_path, 
                    p.visibility,
                    -- [FIXED] Ganti alias menjadi CREATED_AT_FMT (sesuai yang diakses di PHP)
                    TO_CHAR(p.created_at, \'YYYY-MM-DD"T"HH24:MI:SS\') AS CREATED_AT_FMT,
                    u.user_id, 
                    u.nama, 
                    u.email,
                    u.role_name,
                    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS like_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count,
                    
                    (SELECT COUNT(*) FROM FOLLOWS f WHERE f.follower_id = :current_user_id AND f.following_id = p.user_id) AS IS_FOLLOWING,
                    (SELECT COUNT(*) FROM post_likes pl2 WHERE pl2.post_id = p.post_id AND pl2.user_id = :current_user_id) AS IS_LIKED
                  FROM 
                    posts p
                  JOIN 
                    users u ON p.user_id = u.user_id
                  WHERE 
                    -- 1. Postingan Public (Semua bisa lihat)
                    p.visibility = \'public\'
                    
                    -- 2. ATAU Postingan milik saya sendiri (Private pun saya bisa lihat)
                    OR p.user_id = :current_user_id
                    
                    -- 3. ATAU Postingan Private orang lain, tapi SAYA SUDAH FOLLOW DIA
                    OR (
                        p.visibility = \'private\' 
                        AND EXISTS (
                            SELECT 1 FROM follows f 
                            WHERE f.follower_id = :current_user_id 
                            AND f.following_id = p.user_id
                        )
                    )
                  ORDER BY 
                    p.created_at DESC';

        $stmt = oci_parse($this->conn, $query);

        oci_bind_by_name($stmt, ":current_user_id", $current_user_id);

        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if (isset($row['CONTENT']) && is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            
            // [AMAN] Sekarang key 'CREATED_AT_FMT' sudah ada di array $row
            $row['CREATED_AT'] = $row['CREATED_AT_FMT'];
            
            $posts[] = $row;
        }
        oci_free_statement($stmt);
        return $posts;
    }

    /**
     * Mengambil statistik terbaru (jumlah like & komen) untuk polling AJAX
     */
    public function getPostStats($post_ids)
    {
        if (empty($post_ids)) {
            return [];
        }

        // Buat placeholder dinamis (:id_0, :id_1, dst)
        $placeholders = [];
        foreach ($post_ids as $key => $id) {
            $placeholders[] = ":id_$key";
        }
        $in_clause = implode(',', $placeholders);

        // Query efisien: Ambil semua count sekaligus
        $query = "SELECT 
                    p.post_id,
                    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS like_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
                  FROM posts p
                  WHERE p.post_id IN ($in_clause)";

        $stmt = oci_parse($this->conn, $query);

        // Binding nilai ID
        foreach ($post_ids as $key => $id) {
            oci_bind_by_name($stmt, ":id_$key", $post_ids[$key]);
        }

        oci_execute($stmt);

        $stats = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
            $stats[] = $row;
        }
        
        oci_free_statement($stmt);
        return $stats;
    }
    /**
     * [BARU] Mengambil postingan milik user tertentu (Untuk Tab Content)
     */
    /**
     * [BARU] Mengambil postingan milik user tertentu (Untuk Tab Content)
     * [FIXED] Perbaikan alias tanggal di SQL dan penambahan konversi key
     */
    public function getPostsByAuthor($author_id, $viewer_id)
    {
        $query = 'SELECT 
                    p.post_id, 
                    p.content,
                    p.image_path, 
                    p.visibility, 
                    -- [FIXED] Ganti alias menjadi CREATED_AT_FMT
                    TO_CHAR(p.created_at, \'YYYY-MM-DD"T"HH24:MI:SS\') AS CREATED_AT_FMT,
                    u.user_id, 
                    u.nama, 
                    u.email,
                    u.role_name,
                    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS like_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count,
                    
                    (SELECT COUNT(*) FROM FOLLOWS f WHERE f.follower_id = :viewer_id AND f.following_id = p.user_id) AS IS_FOLLOWING,
                    (SELECT COUNT(*) FROM post_likes pl2 WHERE pl2.post_id = p.post_id AND pl2.user_id = :viewer_id) AS IS_LIKED
                  FROM 
                    posts p
                  JOIN 
                    users u ON p.user_id = u.user_id
                  WHERE 
                    p.user_id = :author_id 
                    
                    AND (
                        -- 1. Tampilkan jika Public
                        p.visibility = \'public\' 
                        
                        -- 2. ATAU yang melihat adalah pemilik post itu sendiri
                        OR p.user_id = :viewer_id
                        
                        -- 3. ATAU Private dan viewer sudah follow author
                        OR (
                            p.visibility = \'private\' 
                            AND EXISTS (
                                SELECT 1 FROM follows f 
                                WHERE f.follower_id = :viewer_id 
                                AND f.following_id = p.user_id
                            )
                        )
                    )
                  ORDER BY 
                    p.created_at DESC';

        $stmt = oci_parse($this->conn, $query);
        
        oci_bind_by_name($stmt, ':author_id', $author_id);
        oci_bind_by_name($stmt, ':viewer_id', $viewer_id);
        
        $exec = oci_execute($stmt);
        if (!$exec) return [];

        $posts = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if (is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            
            // [BARU DITAMBAHKAN] Konversi key agar View tidak error
            $row['CREATED_AT'] = $row['CREATED_AT_FMT'];
            
            $posts[] = $row;
        }
        oci_free_statement($stmt);
        return $posts;
    }

/**
     * [PERBAIKAN ORA-01791] Mengambil postingan activity (Like/Comment)
     */
    public function getActivityPosts($activity_user_id, $viewer_id)
    {
        // Query DISTINCT mensyaratkan kolom ORDER BY (created_at) harus ada di SELECT
        $query = 'SELECT DISTINCT
                    p.post_id, 
                    p.content,
                    p.image_path, 
                    p.created_at, -- [WAJIB ADA] agar bisa di-order by
                    TO_CHAR(p.created_at, \'YYYY-MM-DD"T"HH24:MI:SS\') AS CREATED_AT_FMT,
                    u.user_id, 
                    u.nama, 
                    u.email, 
                    u.role_name,
                    (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS like_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count,
                    (SELECT COUNT(*) FROM FOLLOWS f WHERE f.follower_id = :viewer_id AND f.following_id = p.user_id) AS IS_FOLLOWING,
                    (SELECT COUNT(*) FROM post_likes pl2 WHERE pl2.post_id = p.post_id AND pl2.user_id = :viewer_id) AS IS_LIKED
                  FROM 
                    posts p
                  JOIN 
                    users u ON p.user_id = u.user_id
                  WHERE 
                    p.post_id IN (
                        SELECT post_id FROM post_likes WHERE user_id = :act_user_1
                        UNION
                        SELECT post_id FROM comments WHERE user_id = :act_user_2
                    )
                  ORDER BY 
                    p.created_at DESC';

        $stmt = oci_parse($this->conn, $query);
        
        oci_bind_by_name($stmt, ':act_user_1', $activity_user_id);
        oci_bind_by_name($stmt, ':act_user_2', $activity_user_id);
        oci_bind_by_name($stmt, ':viewer_id', $viewer_id);
        
        $exec = oci_execute($stmt);
        if (!$exec) {
            // Debugging: Jika error, tampilkan pesan
            $e = oci_error($stmt);
            // error_log("Oracle Error: " . $e['message']); 
            return [];
        }

        $posts = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if (isset($row['CONTENT']) && is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            
            // [PENTING] Kembalikan nama key 'CREATED_AT' agar View tidak error
            $row['CREATED_AT'] = $row['CREATED_AT_FMT'];
            
            $posts[] = $row;
        }
        oci_free_statement($stmt);
        return $posts;
    }
    /**
     * [BARU] Mengambil satu postingan berdasarkan ID
     * Digunakan untuk mengambil image_path sebelum menghapus post
     */
    public function getPostById($post_id)
    {
        $query = "SELECT post_id, user_id, image_path FROM posts WHERE post_id = :post_id";
        
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':post_id', $post_id);
        oci_execute($stmt);
        
        $row = oci_fetch_array($stmt, OCI_ASSOC);
        oci_free_statement($stmt);
        
        return $row; // Mengembalikan array data post atau false jika tidak ditemukan
    }
}
