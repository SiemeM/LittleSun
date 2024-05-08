<?php
class ScheduleManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function assignTask($userId, $hubLocationId, $taskTypeId, $date, $startTime, $endTime) {
        // Eerst controleren of de vereiste velden niet leeg zijn
        if (empty($date) || empty($startTime) || empty($endTime)) {
            return "Date and time fields must be filled.";
        }
    
        $sql = "INSERT INTO work_schedules (user_id, hub_location_id, task_type_id, work_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $hubLocationId, PDO::PARAM_INT);
        $stmt->bindValue(3, $taskTypeId, PDO::PARAM_INT);
        $stmt->bindValue(4, $date, PDO::PARAM_STR);
        $stmt->bindValue(5, $startTime, PDO::PARAM_STR);
        $stmt->bindValue(6, $endTime, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            return "Error assigning task: " . $stmt->errorInfo()[2];
        }
        return "Task successfully assigned.";
    }

    public function getTasksForCalendar() {
        $sql = "SELECT ws.id, u.name AS title, ws.start_time AS start, ws.end_time AS end, l.location_name, tt.name AS task_type 
                FROM work_schedules ws
                JOIN users u ON ws.user_id = u.id
                JOIN hub_locations l ON ws.hub_location_id = l.id
                JOIN task_types tt ON ws.task_type_id = tt.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tasks as &$row) {
            $row['title'] = $row['title'] . ' - ' . $row['task_type'] . ' at ' . $row['location_name'];
        }
        return $tasks;
    }

    private function checkTimeOffOrConflicts($userId, $date, $startTime, $endTime) {
        $sql = "SELECT COUNT(*) FROM work_schedules WHERE user_id = ? AND work_date = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $date, $startTime, $startTime, $endTime, $endTime]);
        $count = $stmt->fetchColumn(); // Gebruik fetchColumn om de eerste kolom van de eerste rij op te halen
        return $count > 0;
    }

    public function getTaskTypes() {
        $taskTypes = [];
        $sql = "SELECT id, name FROM task_types";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $taskTypes[] = $row;
            }
        } else {
            echo "Error retrieving task types: " . $this->db->error;
        }
        return $taskTypes;
    }

    public function getTaskTypesForUser($userId) {
        // Je zou extra logica hier kunnen toevoegen om te bepalen welke taken een gebruiker kan uitvoeren.
        $query = "SELECT id, name FROM task_types"; // Vereenvoudigd voorbeeld
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Correct gebruik van PDO om alle resultaten te fetchen
    }

    

    
}

