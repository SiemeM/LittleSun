<?php
require_once 'db/db_connect.php'; // Zorg ervoor dat dit het correcte pad is naar je db_connect.php bestand
require_once 'classes/UserManager.php'; // Het pad naar de UserManager klasse
// Voeg require_once toe voor andere klassen die je nodig hebt

session_start();

// Controleer of de gebruiker ingelogd is en de rol van admin heeft
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$userManager = new UserManager($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Hier zou je de profielfoto uploaden met de ProfilePictureUploader klasse
        // en de bestandsnaam terugkrijgen om deze door te geven aan de addHubManager methode.

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
    <input type="number" name="hub_location" required><br>
    <button type="submit">Voeg Toe</button>
</form>
</body>
</html>
