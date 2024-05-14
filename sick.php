<?php 
require_once 'db/db_connect.php';

// Controleren op fouten bij het maken van de verbinding
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Verwerk de gebruikersinput om een tijdslot als ziek te markeren
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controleer of alle vereiste velden zijn ingevuld
    if (!empty($_POST["sick_date"]) && !empty($_POST["start_time"]) && !empty($_POST["end_time"])) {
        // Ontvang en valideer de ingediende waarden
        $sick_date = mysqli_real_escape_string($connection, $_POST["sick_date"]);
        $start_time = mysqli_real_escape_string($connection, $_POST["start_time"]);
        $end_time = mysqli_real_escape_string($connection, $_POST["end_time"]);

        // Update de database om dit tijdslot als ziek te markeren
        $query = "INSERT INTO time_slots (datum, start_time, end_time, sick) VALUES ('$sick_date', '$start_time', '$end_time', TRUE)";
        if (mysqli_query($connection, $query)) {
            echo "Tijdslot is succesvol gemarkeerd als ziek.";
        } else {
            // Vang de specifieke foutmelding van MySQL op
            echo "Fout bij het markeren van het tijdslot als ziek: " . mysqli_errno($connection) . " - " . mysqli_error($connection);
        }
    } else {
        echo "Niet alle vereiste velden zijn ingevuld.";
    }
}

// Genereer het rapport over gewerkte tijd
$query = "SELECT * FROM time_slots WHERE sick = 0"; // Selecteer alleen niet-zieke tijdsloten
$result = mysqli_query($connection, $query);

// Controleer of de query succesvol is uitgevoerd
// if (!$result) {
//     $error_message = mysqli_error($connection);
//     if (empty($error_message)) {
//         $error_message = "Onbekende fout";
//     }
//     die("Query failed: " . $error_message);
// }



?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport over gewerkte tijd</title>
</head>
<body>
    <h2>Rapport over gewerkte tijd</h2>
    <ul>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li><?= $row['start_time'] ?> - <?= $row['end_time'] ?></li>
    <?php endwhile; ?>
    </ul>

    <h2>Markeer een tijdslot als ziek</h2>
    <form method="post">
        <label for="sick_date">Datum:</label>
        <input type="date" id="sick_date" name="sick_date"><br><br>
        <label for="start_time">Starttijd:</label>
        <input type="time" id="start_time" name="start_time"><br><br>
        <label for="end_time">Eindtijd:</label>
        <input type="time" id="end_time" name="end_time"><br><br>
        <button type="submit">Markeren als ziek</button>
    </form>
</body>
</html>
