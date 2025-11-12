<?php
// SELALU mulai session di barIS PALING ATAS
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Baru sertakan file lain
require_once 'database.php';

define('BASE_URL', 'http://localhost/Sinergi');


$route = $_GET['url'] ?? '';// tangkap parameter dari .htaccess

switch ($route) {
    case '':
    case 'login':
        require_once 'src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->index(); // misalnya tampilkan halaman login
        break;

    case 'home':
        require_once 'src/Controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index(); // tampilkan views/home/index.php
        break;

    case 'logout':
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>