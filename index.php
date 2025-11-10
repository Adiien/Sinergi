<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'database copy.php';

define('BASE_URL', '/Sinergi/public');


function parseUrl() {
    if (isset($_GET['url'])) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
    }
    return [];
}

$url = parseUrl();

// ROUTING
$controllerName = 'AuthController'; // Controller default
if (!empty($url[0])) {
    $controllerName = ucfirst($url[0]) . 'Controller';
}

$methodName = 'index'; // Method default
if (isset($url[1])) {
    $methodName = $url[1];
}

$params = [];
if (isset($url[2])) {
    $params = array_slice($url, 2);
}

$controllerFile = 'src/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $controller = new $controllerName;

        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            echo "Error: Method '$methodName' tidak ditemukan di controller '$controllerName'.";
        }
    } else {
        echo "Error: Class controller '$controllerName' tidak ditemukan.";
    }
}