<?php

class AuthController {
    public function index() {
        $data['pageTitle'] = 'Auth Sinergi Page';
        require_once __DIR__ . '/../../views/auth/index.php';
    }
}