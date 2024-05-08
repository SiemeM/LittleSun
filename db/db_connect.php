<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "littlesun";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "root";
    private $database = "littlesun";

    public function getConnection() {
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }
}

?>
