<?php
// Inclusief het bestand voor databaseverbinding
require_once 'db/db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <title>Register - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/styleRegister.css">
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
            <button type="submit">Register</button>
            <div class="formLinks">
                <a href="login.php" class="btn">Already registered?</a>
            </div>
        </form>
    </div>
</body>