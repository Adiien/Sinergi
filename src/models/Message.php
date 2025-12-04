<?php
require_once __DIR__ . '/../../database.php';

class Message
{
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
    }

    public function getAllUsersExcept($userId)
    {
        $sql = "
            SELECT USER_ID, NAMA, EMAIL, ROLE_NAME
            FROM USERS
            WHERE USER_ID != :id
            ORDER BY NAMA ASC
        ";

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':id', $userId);

        oci_execute($stmt);

        $result = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $result[] = $row;
        }

        return $result;
    }

    public function getUserById($userId)
    {
        $sql = "
            SELECT USER_ID, NAMA, EMAIL, ROLE_NAME, EMAIL
            FROM USERS
            WHERE USER_ID = :id
        ";

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':id', $userId);

        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        return $row ?: null;
    }

    public function getConversation($me, $other)
{
    $sql = "
        SELECT *
        FROM messages
        WHERE 
            (sender_id = :me AND receiver_id = :other)
            OR
            (sender_id = :other AND receiver_id = :me)
        ORDER BY created_at ASC
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':me', $me);
    oci_bind_by_name($stmt, ':other', $other);

    oci_execute($stmt);

    $result = [];
    while ($row = oci_fetch_assoc($stmt)) {

        // KONVERSI CLOB KE STRING
        if (isset($row['CONTENT']) && $row['CONTENT'] instanceof OCILob) {
            $row['CONTENT'] = $row['CONTENT']->load();
        }

        $result[] = $row;
    }

    return $result;
}


    public function sendMessage($sender, $receiver, $content = '', $filePath = null, $msgType = 'text')
{
    // CONTENT di DB NOT NULL, jadi jangan pernah kirim NULL
    if ($content === null) {
        $content = '';
    }

    $query = "INSERT INTO messages (
                message_id,
                sender_id,
                receiver_id,
                content,
                file_path,
                msg_type,
                created_at,
                is_read,
                deleted_by_sender,
                deleted_by_receiver
              ) VALUES (
                messages_seq.NEXTVAL,
                :sender,
                :receiver,
                :content,
                :file_path,
                :msg_type,
                SYSTIMESTAMP,
                'N',
                'N',
                'N'
              )";

    $stmt = oci_parse($this->conn, $query);

    oci_bind_by_name($stmt, ':sender', $sender);
    oci_bind_by_name($stmt, ':receiver', $receiver);
    oci_bind_by_name($stmt, ':content', $content);
    oci_bind_by_name($stmt, ':file_path', $filePath);
    oci_bind_by_name($stmt, ':msg_type', $msgType);

    $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if (!$result) {
        $e = oci_error($stmt);
        oci_free_statement($stmt);
        throw new Exception("DB Error (sendMessage): " . $e['message']);
    }

    oci_free_statement($stmt);
    return true;
}

public function getUnreadSummary($userId)
{
    $sql = "
        SELECT 
            m.SENDER_ID,
            u.NAMA,
            u.ROLE_NAME,
            u.EMAIL,
            COUNT(*) AS UNREAD_COUNT,
            MAX(m.CREATED_AT) AS LAST_TIME
        FROM messages m
        JOIN users u ON u.user_id = m.sender_id
        WHERE 
            m.RECEIVER_ID = :me
            AND m.IS_READ = 'N'
        GROUP BY 
            m.SENDER_ID, u.NAMA, u.ROLE_NAME, u.EMAIL
        ORDER BY LAST_TIME DESC
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':me', $userId);
    oci_execute($stmt);

    $result = [];
    while ($row = oci_fetch_assoc($stmt)) {
        // convert CLOB/TIMESTAMP if perlu
        if (isset($row['LAST_TIME']) && $row['LAST_TIME'] instanceof OCILob) {
            $row['LAST_TIME'] = $row['LAST_TIME']->load();
        } elseif (isset($row['LAST_TIME']) && $row['LAST_TIME'] !== null) {
            $row['LAST_TIME'] = (string) $row['LAST_TIME'];
        }

        $result[] = $row;
    }

    return $result;
}

public function countTotalUnread($userId)
{
    $sql = "
        SELECT COUNT(*) AS TOTAL
        FROM messages
        WHERE receiver_id = :me
          AND is_read = 'N'
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':me', $userId);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);
    return $row ? (int)$row['TOTAL'] : 0;
}

public function markAsRead($me, $other)
{
    $sql = "
        UPDATE messages
        SET is_read = 'Y'
        WHERE receiver_id = :me
          AND sender_id   = :other
          AND is_read     = 'N'
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':me', $me);
    oci_bind_by_name($stmt, ':other', $other);

    $ok = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    if (!$ok) {
        $e = oci_error($stmt);
        throw new Exception("DB Error (markAsRead): " . $e['message']);
    }
}


}
