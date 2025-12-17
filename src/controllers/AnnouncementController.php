<?php

require_once __DIR__ . '/../models/Announcement.php';

class AnnouncementController
{
    private $conn;
    private $announcementModel;

    public function __construct()
    {
        $this->conn = koneksi_oracle();
        $this->announcementModel = new Announcement($this->conn);
    }

    public function index()
    {
        $announcements = $this->announcementModel->getAll();
        require __DIR__ . '/../../views/announcement/index.php';
    }
}
