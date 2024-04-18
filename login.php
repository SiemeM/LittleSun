<?php
// Start een sessie
session_start();
// databaseverbinding
require_once 'db_connect.php';

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal gebruikersinvoer op
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL-query 
    $sql = "SELECT id, email, password, role FROM users WHERE email = ?";
    // Bereid de SQL-instructie voor op uitvoering
    $stmt = $conn->prepare($sql);
    // Koppel de e-mailvariabele aan de voorbereide instructie als een string
    $stmt->bind_param("s", $email);
    // Voer de voorbereide instructie uit
    $stmt->execute();
    // Haal de resultaatset op uit de instructie
    $result = $stmt->get_result();

    // Controleer of er precies één gebruiker is gevonden
    if ($result->num_rows == 1) {
        // Haal de rij op als een associatieve array
        $user = $result->fetch_assoc();
        // Verifieer het wachtwoord met het gehashte wachtwoord in de database
        if (password_verify($password, $user['password'])) {
            // Stel sessievariabelen in voor gebruikersdetails
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Sla de rol van de gebruiker op in de sessie

            // Leid de gebruiker om naar de indexpagina
            header("Location: index.php");
            exit();
        } else {
            // foutbericht als het wachtwoord niet overeenkomt
            $login_error = "Ongeldig e-mailadres of wachtwoord.";
        }
    } else {
        // foutbericht als er geen gebruiker wordt gevonden met het e-mailadres
        $login_error = "Ongeldig e-mailadres of wachtwoord.";
    }
}
?>


<form action="" method="post">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="password">Wachtwoord:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
    <a href="register.php" class="btn">Registreer</a>
</form>

<!-- Toon het login-foutbericht als het er is -->
<?php if (!empty($login_error)): ?>
    <p><?php echo $login_error; ?></p>
<?php endif; ?>
