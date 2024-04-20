<?php
class UserManager {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }

    public function addHubManager($name, $email, $password, $profilePicture, $hubLocation) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, role, profile_picture, hub_location) VALUES (?, ?, ?, 'manager', ?, ?)";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $hashedPassword, $profilePicture, $hubLocation);
        if (!$stmt->execute()) {
            throw new Exception("Er is een fout opgetreden: " . $stmt->error);
        }
    }

    public function register($email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("sss", $email, $hashedPassword, $role);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }
    }
}
