<?php
require_once 'classes/SessionManager.php'; // Veronderstelt dat deze klasse sessiecontroles beheert
require_once 'db/db_connect.php'; // Veronderstelt dat dit het bestand is dat de databaseverbinding opzet
require_once 'classes/LocationManager.php'; // Beheert locatiegerelateerde acties

$sessionManager = new SessionManager();
$sessionManager->checkAdmin(); // Check of de gebruiker admin is

$locationManager = new LocationManager($conn); // Maak een nieuwe instantie van de locatiemanager
$message = ''; // Bericht voor feedback aan de gebruiker

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['create'])) {
            $locationName = $_POST['location_name'];
            if (!empty($locationName)) {
                $locationManager->createLocation($locationName);
                $message = "Locatie succesvol aangemaakt.";
            } else {
                $message = "De locatienaam mag niet leeg zijn.";
            }
        } elseif (isset($_POST['update'])) {
            $locationName = $_POST['location_name'];
            $locationId = $_POST['location_id'];
            if (!empty($locationName) && !empty($locationId)) {
                $locationManager->updateLocation($locationId, $locationName);
                $message = "Locatie succesvol bijgewerkt.";
            } else {
                $message = "De locatienaam en locatie-ID mogen niet leeg zijn.";
            }
        } elseif (isset($_POST['delete'])) {
            $locationId = $_POST['location_id'];
            if (!empty($locationId)) {
                $locationManager->deleteLocation($locationId);
                $message = "Locatie succesvol verwijderd.";
            } else {
                $message = "Locatie-ID mag niet leeg zijn.";
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
