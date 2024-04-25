<?php
class TaskTypeManager {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }

    public function addTaskType($taskTypeName) {
        $sql = "INSERT INTO task_types (name) VALUES (?)";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("s", $taskTypeName);
        if (!$stmt->execute()) {
            if ($this->dbConnection->errno == 1062) {  // 1062 is de error code voor 'Duplicate entry' voor een key
                throw new Exception("Een taaktype met de naam '$taskTypeName' bestaat al.");
            } else {
                throw new Exception("Kon taaktype niet toevoegen: " . $stmt->error);
            }
        }
        return $stmt->insert_id;
    }

    public function deleteTaskType($taskTypeId) {
        $stmt = $this->dbConnection->prepare("DELETE FROM task_types WHERE id = ?");
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->dbConnection->error);
        }
        $stmt->bind_param("i", $taskTypeId);
        if (!$stmt->execute()) {
            throw new Exception("Kon taaktype niet verwijderen: " . $stmt->error);
        }
    }

    public function getAllTaskTypes() {
        $sql = "SELECT id, name FROM task_types";
        $result = $this->dbConnection->query($sql);
        if (!$result) {
            throw new Exception("Kon taaktypen niet ophalen: " . $this->dbConnection->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
