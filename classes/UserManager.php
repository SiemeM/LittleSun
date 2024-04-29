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

    public function resetPassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ? AND role = 'manager'";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $userId);
        if (!$stmt->execute()) {
            throw new Exception("Wachtwoord resetten mislukt: " . $stmt->error);
        }
    }

    public function getAllHubManagers() {
        $sql = "SELECT id, name FROM users WHERE role = 'manager'";
        $stmt = $this->dbConnection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->dbConnection->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createUser($name, $email, $password, $profilePicture) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, profile_picture, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $profilePicture);
        if (!$stmt->execute()) {
            throw new Exception("Kon gebruiker niet aanmaken: " . $stmt->error);
        }
        return $stmt->insert_id;
    }

    public function getAllUsers() {
        $sql = "SELECT id, name FROM users";
        $result = $this->dbConnection->query($sql);
        if ($result === false) {
            throw new Exception("Kon gebruikers niet ophalen: " . $this->dbConnection->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsersWithTasks() {
        $sql = "SELECT u.name, u.profile_picture, IFNULL(tt.name, 'Deze gebruiker heeft nog geen taak.') AS task_type
                FROM users u
                LEFT JOIN user_task_types utt ON u.id = utt.user_id
                LEFT JOIN task_types tt ON utt.task_type_id = tt.id
                WHERE u.role = 'user'";  // Filtert alleen gebruikers met de rol 'user'

        $stmt = $this->dbConnection->prepare($sql);
        if (!$stmt) {
            echo "Fout bij het voorbereiden van de query: " . $this->dbConnection->error;
            return [];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($userId) {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $name, $email, $password, $profilePicture) {
        $sql = "UPDATE users SET name = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?";
        $stmt = $this->dbConnection->prepare($sql);
        if (!$stmt) {
            echo "Prepare failed: (" . $this->dbConnection->errno . ") " . $this->dbConnection->error;
            return false;
        }
        $stmt->bind_param("ssssi", $name, $email, $password, $profilePicture, $id);
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        return true;
    }
    

}
