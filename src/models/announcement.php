<?php

class Announcement
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAll()
{
    $sql = "
        SELECT 
            ANNOUNCEMENT_ID,
            CONTENT,
            TO_CHAR(CREATED_AT, 'DD Mon YYYY, HH24:MI') AS CREATED_AT
        FROM ANNOUNCEMENTS
        ORDER BY CREATED_AT DESC
    ";

    $stmt = oci_parse($this->conn, $sql);
    oci_execute($stmt);

    $data = [];
    while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        if (is_object($row['CONTENT'])) {
            $row['CONTENT'] = $row['CONTENT']->load();
        }
        $data[] = $row;
    }

    return $data;
}

    public function create($content, $adminId)
{
    $sql = "
        INSERT INTO announcements (
            announcement_id,
            content,
            created_at,
            created_by
        ) VALUES (
            announcements_seq.NEXTVAL,
            :content,
            SYSTIMESTAMP,
            :created_by
        )
    ";

    $stmt = oci_parse($this->conn, $sql);

    oci_bind_by_name($stmt, ':content', $content);
    oci_bind_by_name($stmt, ':created_by', $adminId);

    return oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
}


}
