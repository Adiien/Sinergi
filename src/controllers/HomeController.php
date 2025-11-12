<?php
class HomeController {
    public function index() {
        // Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        require 'views/home/index.php';
    }
}
