<?php
require_once 'src/models/Notification.php';
require_once 'src/helpers/AuthGuard.php';

class NotificationController
{
    private $notifModel;
    private $conn;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->notifModel = new NotificationModel($this->conn);
    }

    public function getNotifications()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $notifs = $this->notifModel->getUserNotifications($_SESSION['user_id']);
        $unread = $this->notifModel->getUnreadCount($_SESSION['user_id']);

        echo json_encode([
            'notifications' => $notifs,
            'unread_count' => $unread
        ]);
        exit;
    }

    public function markRead()
    {
        if (isset($_SESSION['user_id'])) {
            $this->notifModel->markAsRead($_SESSION['user_id']);
        }
    }
}
