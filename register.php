<?php
// Inclusief het bestand voor databaseverbinding
require_once 'db_connect.php';

// Controleert of het formulier is ingediend 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haalt de ingevoerde e-mail en wachtwoord uit het POST-formulier
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'user'; // Standaard rol bij registratie

    // Hash het wachtwoord voor veilige opslag in de database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-instructie voor het invoegen van een nieuwe gebruiker in de database
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    // Bereidt de SQL-instructie voor om te voorkomen van SQL injecties
    $stmt = $conn->prepare($sql);
    // Koppelt de variabelen als strings aan de voorbereide SQL-instructie
    $stmt->bind_param("sss", $email, $hashed_password, $role);
    // Voert de voorbereide SQL-instructie uit
    if ($stmt->execute()) {
        echo "User registered successfully!"; // Geef succesmelding
    } else {
        echo "Error: " . $conn->error; // Geef foutmelding bij een mislukking
    }
}
?>


<form action="" method="post">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="password">Wachtwoord:</label>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
    <a href="login.php" class="btn">Login</a> 
</form>
