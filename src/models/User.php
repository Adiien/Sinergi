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
}
