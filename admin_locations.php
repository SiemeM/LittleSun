<?php
require_once 'classes/SessionManager.php'; // Assumes this class manages session controls
require_once 'db/db_connect.php'; // Assumes this file sets up the database connection
require_once 'classes/LocationManager.php'; // Manages location-related actions

$sessionManager = new SessionManager();
$sessionManager->checkAdmin(); // Check if the user is an admin

$locationManager = new LocationManager($conn); // Create a new instance of the location manager
$message = ''; // Message for user feedback

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['create'])) {
            $locationName = $_POST['location_name'];
            if (!empty($locationName)) {
                $locationManager->createLocation($locationName);
                $message = "Location created successfully.";
            } else {
                $message = "Location name cannot be empty.";
            }
        } elseif (isset($_POST['update'])) {
            $locationName = $_POST['location_name'];
            $locationId = $_POST['location_id'];
            if (!empty($locationName) && !empty($locationId)) {
                $locationManager->updateLocation($locationId, $locationName);
                $message = "Location updated successfully.";
            } else {
                $message = "Location name and location ID cannot be empty.";
            }
        } elseif (isset($_POST['delete'])) {
            $locationId = $_POST['location_id'];
            if (!empty($locationId)) {
                $locationManager->deleteLocation($locationId);
                $message = "Location deleted successfully.";
            } else {
                $message = "Location ID cannot be empty.";
            }
        }
    }
} catch (Exception $e) {
    $message = $e->getMessage();
}

$locations = $locationManager->getLocations();
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
