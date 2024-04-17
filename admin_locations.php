<?php
session_start();
require_once 'db_connect.php'; // Your database connection file

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect non-admins to login page
    exit();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $location_name = $_POST['location_name'];
        $stmt = $conn->prepare("INSERT INTO hub_locations (location_name) VALUES (?)");
        $stmt->bind_param("s", $location_name);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $location_name = $_POST['location_name'];
        $location_id = $_POST['location_id'];
        $stmt = $conn->prepare("UPDATE hub_locations SET location_name = ? WHERE id = ?");
        $stmt->bind_param("si", $location_name, $location_id);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $location_id = $_POST['location_id'];
        $stmt = $conn->prepare("DELETE FROM hub_locations WHERE id = ?");
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
    }
}

// Fetch all locations
$result = $conn->query("SELECT * FROM hub_locations");
$locations = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hub Locations</title>
</head>
<body>
    <h1>Manage Hub Locations</h1>

    <h2>Create New Location</h2>
    <form method="post">
        <input type="text" name="location_name" required>
        <button type="submit" name="create">Create Location</button>
    </form>

    <h2>Existing Locations</h2>
    <?php foreach ($locations as $location): ?>
    <form method="post">
        <input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">
        <input type="text" name="location_name" value="<?php echo $location['location_name']; ?>">
        <button type="submit" name="update">Update</button>
        <button type="submit" name="delete">Delete</button>
    </form>
    <?php endforeach; ?>
</body>
</html>
