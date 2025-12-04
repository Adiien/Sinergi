<?php

require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/User.php';

class MessageController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /Sinergi/public/auth");
            exit;
        }

        $db = koneksi_oracle();
        $userModel = new User($db);

        $me = $_SESSION['user_id'];

        $contacts = $userModel->getContactsWithLastMessage($me);

        require_once __DIR__ . '/../../views/messages/index.php';
    }

 public function show()
    {
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized access");
    }

    if (!isset($_GET['user_id'])) {
        die("Missing target user");
    }

    $me    = $_SESSION['user_id'];
    $other = $_GET['user_id'];

    $db         = koneksi_oracle();
    $msg        = new Message($db);
    $userModel  = new User($db);

    // ⬅ INI PENTING: ambil semua user lain untuk sidebar
    $contacts = $userModel->getContactsWithLastMessage($me);

    // data lawan chat
    $conversation = $msg->getConversation($me, $other);
    $userData     = $userModel->getUserById($other);

    $msg->markAsRead($me, $other);

        require_once 'views/messages/show.php';
    }

    public function send()
{
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit;
    }

    $sender   = $_SESSION['user_id'];
    $receiver = $_POST['receiver_id'] ?? null;

    if (!$receiver) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing receiver']);
        exit;
    }

    $content  = trim($_POST['content'] ?? '');
    $filePath = null;
    $msgType  = 'text';

    // HANDLE FILE UPLOAD (opsional)
    if (!empty($_FILES['attachment']['name'])) {

        $uploadDir = "public/uploads/messages/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName   = time() . "_" . basename($_FILES['attachment']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
            $filePath = $fileName;
            $msgType  = 'image';
        }
    }

    // Kalau tidak ada file dan text kosong → tolak
    if ($msgType === 'text' && $content === '') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Message empty']);
        exit;
    }

    // Pastikan content minimal string kosong (bukan NULL)
    if ($content === null) {
        $content = '';
    }

    $db  = koneksi_oracle();
    $msg = new Message($db);

    try {
        $msg->sendMessage($sender, $receiver, $content, $filePath, $msgType);

        require_once __DIR__ . '/../models/Notification.php';

        $notif = new NotificationModel(koneksi_oracle());
        $notif->create(
        $receiver,          // target
        $sender,            // pelaku
        'dm',               // tipe notifikasi
        null                // reference_id sekarang boleh null
        );

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;

    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
        exit;
    }
}


        public function fetch()
    {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit;
    }

    if (!isset($_GET['user_id'])) {
        http_response_code(400);
        exit;
    }

    $me    = $_SESSION['user_id'];
    $other = $_GET['user_id'];

    $db  = koneksi_oracle();
    $msg = new Message($db);

    $conversation = $msg->getConversation($me, $other);

    // JAGA-JAGA: kalau masih ada CLOB nyangkut
    foreach ($conversation as &$m) {
        if (isset($m['CONTENT']) && $m['CONTENT'] instanceof OCILob) {
            $m['CONTENT'] = $m['CONTENT']->load();
        }
    }
    unset($m);

    header('Content-Type: application/json');
    echo json_encode($conversation);
    exit;
    }

    public function unreadSummary()
{
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }

    $me = $_SESSION['user_id'];

    $db  = koneksi_oracle();
    $msg = new Message($db);

    $summary = $msg->getUnreadSummary($me);
    $total   = $msg->countTotalUnread($me);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'total'   => $total,
        'items'   => $summary
    ]);
    exit;
}



}