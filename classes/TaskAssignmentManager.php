<?php
class TaskAssignmentManager {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }

    public function assignTaskTypeToUser($userId, $taskTypeId) {
        $sql = "INSERT INTO user_task_types (user_id, task_type_id) VALUES (?, ?)";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("ii", $userId, $taskTypeId);
        if (!$stmt->execute()) {
            throw new Exception("Kon taaktype niet toewijzen aan gebruiker: " . $stmt->error);
        }
    }

    public function getAvailableTaskTypes() {
        $sql = "SELECT id, name FROM task_types";
        $result = $this->dbConnection->query($sql);
        if (!$result) {
            throw new Exception("Kon taaktypen niet ophalen: " . $this->dbConnection->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

