<?php
// Stel verbinding in met de database
require_once 'db/db_connect.php';

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functie om de resultaten te genereren op basis van de filtercriteria
function generateReport($conn, $location, $user, $task_type, $sick) {
    // Bouw de query op basis van de filters
    $query = "SELECT ws.*, u.name AS user_name, h.location_name AS hub_location, tt.name AS task_type
              FROM work_schedules ws
              INNER JOIN users u ON ws.user_id = u.id
              INNER JOIN hub_locations h ON ws.hub_location_id = h.id
              INNER JOIN task_types tt ON ws.task_type_id = tt.id";

    // Voeg filters toe aan de query indien gespecificeerd
    $filters = [];
    if (!empty($location)) {
        $filters[] = "h.location_name = '$location'";
    }

    if (!empty($user)) {
        $filters[] = "u.name = '$user'";
    }

    if (!empty($task_type)) {
        $filters[] = "tt.name = '$task_type'";
    }

    if ($sick !== "") {
        $filters[] = "ws.sick = '$sick'";
    }

    if (!empty($filters)) {
        $query .= " WHERE " . implode(" AND ", $filters);
    }

    // Voer de query uit
    $result = $conn->query($query);

    // Toon de resultaten
    if ($result->num_rows > 0) {
        // Output data van elke rij
        while($row = $result->fetch_assoc()) {
            echo "Gebruiker: " . $row["user_name"]. " - Locatie: " . $row["hub_location"]. " - Taaktype: " . $row["task_type"]. " - Werkdatum: " . $row["work_date"]. " - Starttijd: " . $row["start_time"]. " - Eindtijd: " . $row["end_time"]. "<br>";
        }
    } else {
        echo "Geen resultaten gevonden";
    }
}

// Als het formulier is verzonden, roep dan de generateReport-functie aan met de ingediende filtercriteria
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $location = isset($_GET['location']) ? $_GET['location'] : '';
    $user = isset($_GET['user']) ? $_GET['user'] : '';
    $task_type = isset($_GET['task_type']) ? $_GET['task_type'] : '';
    $sick = isset($_GET['sick']) ? $_GET['sick'] : '';
    
    generateReport($conn, $location, $user, $task_type, $sick);
}

// Haal alle gebruikers op
$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);
$users = [];
if ($result_users->num_rows > 0) {
    while($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

// Haal alle locaties op
$query_locations = "SELECT * FROM hub_locations";
$result_locations = $conn->query($query_locations);
$locations = [];
if ($result_locations->num_rows > 0) {
    while($row = $result_locations->fetch_assoc()) {
        $locations[] = $row;
    }
}

// Haal alle taaktypes op
$query_task_types = "SELECT * FROM task_types";
$result_task_types = $conn->query($query_task_types);
$task_types = [];
if ($result_task_types->num_rows > 0) {
    while($row = $result_task_types->fetch_assoc()) {
        $task_types[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="filterForm">
        <label for="location">Location:</label>
        <select name="location" id="location">
            <option value="">Select Location</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="user">User:</label>
        <select name="user" id="user">
            <option value="">Select User</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['name']; ?>"><?php echo $user['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="task_type">Task Type:</label>
        <select name="task_type" id="task_type">
            <option value="">Select Task Type</option>
            <?php foreach ($task_types as $task_type): ?>
                <option value="<?php echo $task_type['name']; ?>"><?php echo $task_type['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="sick">Sick:</label>
        <select name="sick" id="sick">
            <option value="">Select Sick</option>
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>

        <button type="submit">Generate Report</button>
    </form>

    <div id="reportResults"></div>

    <script>
        $(document).ready(function() {
            $('#filterForm').submit(function(event) {
                event.preventDefault(); // Voorkom standaard formulierinzending

                // Verzamel formuliergegevens
                var formData = $(this).serialize();

                // Voer een AJAX-verzoek uit naar dezelfde pagina
                $.ajax({
                    type: 'GET',
                    url: window.location.href,
                    data: formData,
                    success: function(response) {
                        $('#reportResults').html(response); // Update de resultaten in de div
                    }
                });
            });
        });
    </script>
</body>
</html>
