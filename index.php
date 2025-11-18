<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

define('BASE_URL', 'http://localhost/Sinergi');



$route = $_GET['url'] ?? '';

switch ($route) {
    case '':
    case 'login':
        require_once 'src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->index();
        break;

    case 'auth/login':
        require_once 'src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'auth/register':
        require_once 'src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
        
    case 'admin':
        require_once 'src/Controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;
    
    case 'admin/delete':
        require_once 'src/Controllers/AdminController.php';
        $controller = new AdminController();
        $controller->delete();
        break;

    case 'home':
        require_once 'src/Controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'post/create':
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->create();
        break;
    
    case 'post/delete':
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->delete();
        break;

    case 'post/like':
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->like();
        break;

    case 'post/comment':
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->comment();
        break;

    case 'report/create':
        require_once 'src/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->create();
        break;


    case 'logout':
        require_once 'src/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
