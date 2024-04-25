<?php
require_once 'db/db_connect.php';
require_once 'classes/UserManager.php';
require_once 'classes/SessionManager.php';

$sessionManager = new SessionManager();
$sessionManager->checkManager(); // Zorgt ervoor dat alleen managers toegang hebben

$userManager = new UserManager($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $profilePicture = $_FILES['profile_picture']['name']; // Verwerk de upload van de afbeelding correct

        $userManager->createUser($name, $email, $password, $profilePicture);
        $message = "Gebruiker succesvol aangemaakt.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Gebruiker Aanmaken</title>
</head>
<body>
<h1>Gebruiker Aanmaken</h1>
<p><?php echo $message; ?></p>

<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Naam:</label>
    <input type="text" name="name" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>
    <label for="password">Wachtwoord:</label>
    <input type="password" name="password" required><br>
    <label for="profile_picture">Profielfoto:</label>
    <input type="file" name="profile_picture" required><br>
    <button type="submit">Gebruiker Aanmaken</button>
</form>
</body>
</html>
