<?php
class NotificationModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Simpan Notifikasi Baru
    public function create($user_id, $actor_id, $type, $reference_id)
    {
        // Jangan buat notifikasi jika pelaku adalah diri sendiri
        if ($user_id == $actor_id) {
            return false;
        }

        $query = "INSERT INTO notifications (notification_id, user_id, actor_id, type, reference_id, created_at)
                  VALUES (notifications_seq.NEXTVAL, :user_id, :actor_id, :type, :ref_id, SYSTIMESTAMP)";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_bind_by_name($stmt, ':actor_id', $actor_id);
        oci_bind_by_name($stmt, ':type', $type);
        oci_bind_by_name($stmt, ':ref_id', $reference_id);

        return oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    }

    // Ambil Notifikasi User (Join dengan tabel Users untuk nama pelaku)
    public function getUserNotifications($user_id)
    {
        $query = "SELECT n.*, u.nama as actor_name, 
                  TO_CHAR(n.created_at, 'YYYY-MM-DD HH24:MI:SS') as fmt_time
                  FROM notifications n
                  JOIN users u ON n.actor_id = u.user_id
                  WHERE n.user_id = :user_id
                  ORDER BY n.created_at DESC FETCH FIRST 10 ROWS ONLY";

        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt);

        $results = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $results[] = $row;
        }
        return $results;
    }

    // Hitung notifikasi belum dibaca
    public function getUnreadCount($user_id)
    {
        $query = "SELECT COUNT(*) as CNT FROM notifications WHERE user_id = :user_id AND is_read = 0";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt);
        $row = oci_fetch_array($stmt, OCI_ASSOC);
        return $row['CNT'] ?? 0;
    }

    // Tandai sudah dibaca
    public function markAsRead($user_id)
    {
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
        $stmt = oci_parse($this->conn, $query);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    }
}
