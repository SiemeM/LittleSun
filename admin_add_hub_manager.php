<?php
require_once 'db/db_connect.php'; // Database connectie
require_once 'classes/UserManager.php'; // UserManager klasse
require_once 'classes/LocationManager.php'; // LocationManager klasse

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$locationManager = new LocationManager($conn);
$locations = $locationManager->getLocations(); // Haal locaties op

$userManager = new UserManager($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $userManager->addHubManager($_POST['name'], $_POST['email'], $_POST['password'], $_FILES['profile_picture']['name'], $_POST['hub_location']);
        echo "Hub manager toegevoegd.";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voeg Hub Manager Toe</title>
</head>
<body>
<h2>Voeg een nieuwe Hub Manager toe</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Naam:</label>
    <input type="text" name="name" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>
    <label for="password">Wachtwoord:</label>
    <input type="password" name="password" required><br>
    <label for="profile_picture">Profielfoto:</label>
    <input type="file" name="profile_picture" required><br>
    <label for="hub_location">Hub Locatie:</label>
    <select name="hub_location" required>
        <?php foreach ($locations as $location): ?>
            <option value="<?php echo $location['id']; ?>"><?php echo htmlspecialchars($location['location_name']); ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Voeg Toe</button>
</form>
</body>
</html>