<?php
session_start();
require_once 'db_connect.php';

// Controleer of de gebruiker ingelogd is en de rol van admin heeft
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verwerk het formulier wanneer het wordt ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Haal de gegevens uit het formulier
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profilePicture = $_FILES['profile_picture']['name'];
    $hubLocation = $_POST['hub_location'];

    // Hash het wachtwoord voor veilige opslag
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL-query om een nieuwe hub manager toe te voegen
    $sql = "INSERT INTO users (name, email, password, role, profile_picture, hub_location) VALUES (?, ?, ?, 'manager', ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $hashedPassword, $profilePicture, $hubLocation);
    if ($stmt->execute()) {
        // Omleiden naar een bevestigingspagina of toon een succesbericht
        echo "Hub manager toegevoegd.";
    } else {
        echo "Er is een fout opgetreden: " . $conn->error;
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
