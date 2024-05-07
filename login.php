<?php
// Start een sessie
session_start();
// databaseverbinding
require_once 'db/db_connect.php';

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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <title>Login - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
<nav>
    <img src="img/logo.png" alt="" id="logo">
    <div class="navItems">
        <a href="" class="navItem1">ITEM</a>
        <a href="" class="navItem2">ITEM</a>
        <a href="" class="navItem3">HELP</a>
        <a href="" class="navItem4">BUTTON</a>
    </div>
</nav>
<div class="container">
<div class="bodyText">
    <h1>Little Sun <span>Shiftplanner</span></h1>
    <p>Welcome to Little Sun Shiftplanner, the ultimate platform for shift planners in Zambia! At Little Sun Shiftplanner, we empower workers to take control of their schedules by defining their roles and selecting preferred work locations. Our user-friendly interface allows workers to plan their availability for shifts and even schedule well-deserved vacations with ease.</p>
</div>

<form action="" method="post" class="loginForm">
    <span class="formTitle">Welcome</span>
    <label for="email">E-mail</label>
    <input type="email" name="email" required>
    <label for="password">Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
    <div class="formLinks">
    <a href="register.php" class="btn">Don't have an account?</a>
    <a href="#" class="btn">Forgot password?</a>
    </div>
</form>
</div>
</body>

<!-- Toon het login-foutbericht als het er is -->
<?php if (!empty($login_error)): ?>
    <p><?php echo $login_error; ?></p>
<?php endif; ?>
