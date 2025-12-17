<?php

class RoleGuard
{
    public static function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public static function only(array $allowedRoles)
    {
        self::requireLogin();

        $role = $_SESSION['role_name'] ?? null;

        if (!in_array($role, $allowedRoles)) {
            http_response_code(403);
            exit('403 Forbidden');
        }
    }

    public static function forbid(array $blockedRoles)
    {
        self::requireLogin();

        $role = $_SESSION['role_name'] ?? null;

        if (in_array($role, $blockedRoles)) {
            http_response_code(403);
            exit('403 Forbidden');
        }
    }
}
