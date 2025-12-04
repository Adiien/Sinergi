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

    case 'post/comment/like': // Tambah ini di index.php
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->likeComment();
        break;

    case 'report/create':
        require_once 'src/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->create();
        break;

    case 'user/follow':
        require_once 'src/Controllers/UserController.php';
        $controller = new UserController();
        $controller->follow();
        break;

    case 'messages':
        require_once 'src/Controllers/MessageController.php';
        $controller = new MessageController();
        $controller->index();
        break;

    case 'messages/show':
        require_once 'src/Controllers/MessageController.php';
        $controller = new MessageController();
        $controller->show();
        break;

    case 'messages/send':
        require_once 'src/Controllers/MessageController.php';
        $controller = new MessageController();
        $controller->send();
        break;
    
    case 'api/messages/fetch':
        require_once 'src/Controllers/MessageController.php';
        $controller = new MessageController();
        $controller->fetch();
        break;


    case 'forum':
        require_once 'src/Controllers/ForumController.php';
        $controller = new ForumController();
        $controller->index();
        break;

    case 'forum/create':
        require_once 'src/Controllers/ForumController.php';
        $controller = new ForumController();
        $controller->create();
        break;

    case 'forum/show':
        require_once 'src/Controllers/ForumController.php';
        $controller = new ForumController();
        $controller->show();
        break;

    case 'forum/explore':
        require_once 'src/Controllers/ForumController.php';
        $controller = new ForumController();
        $controller->explore();
        break;

    case 'profile':
        require_once 'src/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->index();
        break;

    case 'settings':
        require_once 'src/Controllers/SettingsController.php';
        $controller = new SettingsController();
        $controller->index();
        break;

    case 'settings/update':
        require_once 'src/Controllers/SettingsController.php';
        $controller = new SettingsController();
        $controller->update();
        break;

    case 'api/updates':
        require_once 'src/Controllers/PostController.php';
        $controller = new PostController();
        $controller->getUpdates();
        break;

    case 'api/search/users':
        require_once 'src/Controllers/UserController.php';
        $controller = new UserController();
        $controller->ajaxSearch();
        break;

    case 'api/search/forums':
        require_once 'src/Controllers/ForumController.php';
        $controller = new ForumController();
        $controller->ajaxSearch();
        break;

    case 'api/notifications':
        require_once 'src/Controllers/NotificationController.php';
        $controller = new NotificationController();
        $controller->getNotifications();
        break;

    case 'api/notifications/read':
        require_once 'src/Controllers/NotificationController.php';
        $controller = new NotificationController();
        $controller->markRead();
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
