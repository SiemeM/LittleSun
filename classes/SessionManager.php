<?php
class SessionManager {
    public function __construct() {
        session_start();
    }

    public function checkUserLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    public function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit();
        }
    }
}
