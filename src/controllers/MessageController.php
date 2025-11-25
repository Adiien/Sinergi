<?php

class MessageController
{
    public function index()
    {
        // Cek login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Data Dummy untuk List Kontak (Kiri)
        $contacts = [
            [
                'id' => 1,
                'name' => 'Ahmad Faris',
                'avatar_color' => 'bg-purple-600',
                'last_message' => 'Last chat',
                'time' => '1m',
                'unread' => true
            ],
            [
                'id' => 2,
                'name' => 'Ulya Sara',
                'avatar_color' => 'bg-pink-600',
                'last_message' => 'Last chat',
                'time' => '15m',
                'unread' => false
            ]
        ];

        // Data Dummy untuk Isi Chat (Kanan) - Percakapan dengan Ulya Sara
        $activeChat = [
            'user' => [
                'name' => 'Ulya Sara',
                'handle' => '@ulyasara',
                'avatar_color' => 'bg-pink-600'
            ],
            'messages' => [
                [
                    'type' => 'sent',
                    'content' => 'Lorem ipsum hi floreuw shicu manul falici consul hi help old united fisha mihtgb.',
                    'time' => '3 Oct 2025 10:19 a.m',
                    'read' => true
                ],
                [
                    'type' => 'received',
                    'content' => 'Lorem ipsum hi floreuw shicu manul falici consul hi help old united fisha mihtgb.',
                    'time' => '3 Oct 2025 10:20 a.m'
                ],
                [
                    'type' => 'received', // Double bubble example
                    'content' => 'Lorem ipsum hi floreuw shicu manul falici consul hi help old united fisha mihtgb.',
                    'time' => '3 Oct 2025 10:20 a.m'
                ],
                [
                    'type' => 'sent',
                    'content' => 'Lorem ipsum hi floreuw shicu manul falici consul hi help old united fisha mihtgb.',
                    'time' => '3 Oct 2025 10:28 a.m',
                    'read' => true
                ],
                [
                    'type' => 'received',
                    'content' => 'Lorem ipsum hi floreuw shicu manul falici consul hi help old united fisha mihtgb.',
                    'time' => '3 Oct 2025 10:29 a.m'
                ]
            ]
        ];

        require 'views/messages/index.php';
    }
}