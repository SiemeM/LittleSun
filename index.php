<?php
// Start een nieuwe sessie of hervat de bestaande sessie
session_start();

// Controleer of de gebruiker is ingelogd door gebruik te maken van een sessievariabele die is ingesteld tijdens het inloggen
if (!isset($_SESSION['user_id'])) {
    // Als de gebruiker niet is ingelogd, omleiden naar de inlogpagina
    header("Location: login.php");
    exit();
}

// Afhandelen van uitloggen
if (isset($_POST['logout'])) {
    // Vernietig de sessie
    session_destroy();
    // Omleiden naar de inlogpagina
    header("Location: login.php");
    exit();
}

// Bepaal de inhoud op basis van de rol van de gebruiker
function getUserContent($role) {
    switch ($role) {
        case 'admin':
            // Retourneert content specifiek voor beheerders
            return "
                <p>Welkom, Admin! Hier zijn je beheerdersgereedschappen en analyses.</p>
                <ul>
                    <li><a href='admin_user_management.php'>Gebruikersbeheer</a></li>
                    <li><a href='admin_locations.php'>Locatiebeheer</a></li>
                </ul>
            ";
        case 'manager':
            // Retourneert content specifiek voor managers
            return "<p>Welkom, Manager! Hier is je management dashboard.</p>";
        case 'user':
            // Retourneert content voor standaard gebruikers
            return "<p>Welkom, Gebruiker! Geniet van je bezoek.</p>";
        default:
            // Retourneert een standaardbericht als de rol niet is gedefinieerd
            return "<p>Welkom! Neem contact op met de ondersteuning om je rol toe te wijzen.</p>";
    }
}

// Haal de juiste content op voor de gebruiker gebaseerd op hun rol
$userContent = getUserContent($_SESSION['role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <?php echo $userContent; ?>
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
