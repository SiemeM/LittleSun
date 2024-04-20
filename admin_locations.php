<?php
require_once 'classes/SessionManager.php'; // Zorg ervoor dat deze klasse de sessie beheert
require_once 'db/db_connect.php'; // Dit blijft het bestand dat de databaseverbinding opzet
require_once 'classes/LocationManager.php'; // De nieuwe locatie manager klasse


$sessionManager = new SessionManager();
$sessionManager->checkAdmin(); // Check of de gebruiker admin is

$locationManager = new LocationManager($conn); // Maak een nieuwe instantie van de locatiemanager

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $locationManager->createLocation($_POST['location_name']);
    } elseif (isset($_POST['update'])) {
        $locationManager->updateLocation($_POST['location_id'], $_POST['location_name']);
    } elseif (isset($_POST['delete'])) {
        $locationManager->deleteLocation($_POST['location_id']);
    }
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
