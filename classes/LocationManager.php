<?php
class LocationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createLocation($locationName) {
        // Controleer of de locatienaam niet leeg is
        if (empty($locationName)) {
            throw new Exception("De locatienaam mag niet leeg zijn.");
        }

        // Controleer of de locatie al bestaat om duplicaten te voorkomen
        if ($this->doesLocationExist($locationName)) {
            throw new Exception("Deze locatie bestaat al.");
        }

        $stmt = $this->db->prepare("INSERT INTO hub_locations (location_name) VALUES (?)");
        $stmt->bind_param("s", $locationName);
        if (!$stmt->execute()) {
            throw new Exception("Kon de locatie niet aanmaken: " . $stmt->error);
        }
        return true;
    }

    public function updateLocation($locationId, $locationName) {
        // Controleer of geen van de waarden leeg is
        if (empty($locationName) || empty($locationId)) {
            throw new Exception("De locatienaam en locatie-ID mogen niet leeg zijn.");
        }

        $stmt = $this->db->prepare("UPDATE hub_locations SET location_name = ? WHERE id = ?");
        $stmt->bind_param("si", $locationName, $locationId);
        if (!$stmt->execute()) {
            throw new Exception("Kon de locatie niet bijwerken: " . $stmt->error);
        }
        return true;
    }

    public function deleteLocation($locationId) {
        // Controleer of de locatie-ID niet leeg is
        if (empty($locationId)) {
            throw new Exception("Locatie-ID mag niet leeg zijn.");
        }

        $stmt = $this->db->prepare("DELETE FROM hub_locations WHERE id = ?");
        $stmt->bind_param("i", $locationId);
        if (!$stmt->execute()) {
            throw new Exception("Kon de locatie niet verwijderen: " . $stmt->error);
        }
        return true;
    }

    public function getLocations() {
        $result = $this->db->query("SELECT * FROM hub_locations");
        if (!$result) {
            throw new Exception("Kon locaties niet ophalen: " . $this->db->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function doesLocationExist($locationName) {
        $stmt = $this->db->prepare("SELECT id FROM hub_locations WHERE location_name = ?");
        $stmt->bind_param("s", $locationName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
