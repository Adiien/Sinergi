<?php

class MitraController
{
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/home');
            exit;
        }

        require 'views/mitra/dashboard.php';
    }
}
