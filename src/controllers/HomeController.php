<?php

class HomeController {
    public function index() {
        $data['pageTitle'] = 'Home Sinergi Page';
        require_once __DIR__ . '/../../views/home/index.php';
    }
}