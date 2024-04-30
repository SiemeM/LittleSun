<?php
class TimeOffManager {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }

    public function requestTimeOff($userId, $startDate, $endDate, $reason) {
        $sql = "INSERT INTO time_off_requests (user_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("isss", $userId, $startDate, $endDate, $reason);
        $stmt->execute();
    }

    public function approveTimeOff($requestId) {
        $sql = "UPDATE time_off_requests SET status = 'approved' WHERE id = ?";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
    }

    public function declineTimeOff($requestId, $managerNotes) {
        $sql = "UPDATE time_off_requests SET status = 'declined', manager_notes = ? WHERE id = ?";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bind_param("si", $managerNotes, $requestId);
        $stmt->execute();
    }

    public function getAllPendingRequests() {
        $sql = "SELECT t.*, u.name AS user_name FROM time_off_requests AS t
                JOIN users AS u ON t.user_id = u.id
                WHERE t.status = 'pending'";
        $result = $this->dbConnection->query($sql);
        if (!$result) {
            error_log("SQL error in getAllPendingRequests: " . $this->dbConnection->error); // Log fout naar PHP error log
            return []; // Retourneer een lege array als er een fout optreedt
        }
        return $result->fetch_all(MYSQLI_ASSOC); // Retourneer alle rijen als associatieve array
    }
}
