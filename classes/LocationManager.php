<?php
class LocationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createLocation($locationName) {
        $stmt = $this->db->prepare("INSERT INTO hub_locations (location_name) VALUES (?)");
        $stmt->bind_param("s", $locationName);
        return $stmt->execute();
    }

    public function updateLocation($locationId, $locationName) {
        $stmt = $this->db->prepare("UPDATE hub_locations SET location_name = ? WHERE id = ?");
        $stmt->bind_param("si", $locationName, $locationId);
        return $stmt->execute();
    }

    public function deleteLocation($locationId) {
        $stmt = $this->db->prepare("DELETE FROM hub_locations WHERE id = ?");
        $stmt->bind_param("i", $locationId);
        return $stmt->execute();
    }

    public function getLocations() {
        $result = $this->db->query("SELECT * FROM hub_locations");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
