<?php
session_start(); // Start een nieuwe sessie of hervat de bestaande sessie
require_once 'db_connect.php'; // Voeg het bestand voor de databaseverbinding toe

// Beveiligingscontrole om te verzekeren dat alleen een admin deze pagina kan bezoeken
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Stuur niet-admin gebruikers naar de loginpagina
    exit();
}

// Verwerkt POST-verzoeken voor het maken, bijwerken of verwijderen van locaties
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Haal de locatienaam op uit het formulier en voer het in de database in
        $location_name = $_POST['location_name'];
        $stmt = $conn->prepare("INSERT INTO hub_locations (location_name) VALUES (?)");
        $stmt->bind_param("s", $location_name);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        // Haal de nieuwe locatienaam en het ID op, en werk de gegevens bij in de database
        $location_name = $_POST['location_name'];
        $location_id = $_POST['location_id'];
        $stmt = $conn->prepare("UPDATE hub_locations SET location_name = ? WHERE id = ?");
        $stmt->bind_param("si", $location_name, $location_id);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Verwijder de locatie gebaseerd op het ID uit de database
        $location_id = $_POST['location_id'];
        $stmt = $conn->prepare("DELETE FROM hub_locations WHERE id = ?");
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
    }
}

// Haal alle locaties op uit de database
$result = $conn->query("SELECT * FROM hub_locations");
$locations = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Hub Locaties</title>
</head>
<body>
    <h1>Hub Locaties Beheren</h1>

    <h2>Nieuwe Locatie Aanmaken</h2>
    <form method="post">
        <input type="text" name="location_name" required>
        <button type="submit" name="create">Locatie Aanmaken</button>
    </form>

    <h2>Bestaande Locaties</h2>
    <?php foreach ($locations as $location): ?>
    <form method="post">
        <input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">
        <input type="text" name="location_name" value="<?php echo htmlspecialchars($location['location_name']); ?>">
        <button type="submit" name="update">Bijwerken</button>
        <button type="submit" name="delete">Verwijderen</button>
    </form>
    <?php endforeach; ?>
</body>
</html>
