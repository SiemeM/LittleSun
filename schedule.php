<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db/db_connect.php';
$database = new Database();
$db = $database->getConnection();

function getSchedule($startDate, $endDate, $userId) {
    global $db;
    // Query voor werkschema's
    $query = "SELECT * FROM work_schedules WHERE start_time BETWEEN ? AND ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $startDate);
    $stmt->bindParam(2, $endDate);
    $stmt->bindParam(3, $userId);
    $stmt->execute();
    $workResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query voor ziektedagen
    $query = "SELECT * FROM time_slots WHERE start_time BETWEEN ? AND ? AND sick = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $startDate);
    $stmt->bindParam(2, $endDate);
    $stmt->execute();
    $sickResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combineer resultaten van werkschema's en ziektedagen
    $results = array_merge($workResults, $sickResults);

    return $results;
}


function buildCalendar($year, $month, $schedule) {
    // Hier bouwen we de kalender op met de werkschema's van de gebruiker
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $calendar = '<table>';
    $calendar .= '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
    $calendar .= '<tr>';

    // Vul lege cellen voor de start van de maand
    $firstDayOfWeek = date('w', mktime(0, 0, 0, $month, 1, $year));
    for ($i = 0; $i < $firstDayOfWeek; $i++) {
        $calendar .= '<td></td>';
    }

    // Genereer de dagen van de maand
    for ($day = 1; $day <= $daysInMonth; $day++, $firstDayOfWeek++) {
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $calendar .= "<td>$day<br>";

        // Zoek naar werkschema's voor deze datum
        foreach ($schedule as $event) {
            if ($event['work_date'] == $date) {
                // Controleer of de index 'task_name' bestaat voordat je deze gebruikt
                $taskName = isset($event['task_name']) ? $event['task_name'] : 'No Task';
                $calendar .= $event['start_time'] . '-' . $event['end_time'] . ': ' . $taskName . '<br>';
            }
        }
        
        $calendar .= "</td>";

        // Start een nieuwe rij elke zondag
        if ($firstDayOfWeek % 7 == 6 && $day != $daysInMonth) {
            $calendar .= "</tr><tr>";
        }
    }

    // Vul lege cellen aan het einde van de maand
    while ($firstDayOfWeek % 7 != 0) {
        $calendar .= '<td></td>';
        $firstDayOfWeek++;
    }

    $calendar .= '</tr></table>';
    return $calendar;
}


// Controleer of de gebruiker is ingelogd en haal de gebruikers-ID op
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user_id'];

// Haal de huidige maand en jaar op, of gebruik de meegegeven waarden
$month = isset($_GET['month']) ? $_GET['month'] : date('n');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Roep de getSchedule-functie aan om het werkschema van de gebruiker op te halen
$schedule = getSchedule(date('Y-m-01', mktime(0, 0, 0, $month, 1, $year)), date('Y-m-t', mktime(0, 0, 0, $month, 1, $year)), $userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Schedule Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Work Schedule Calendar for <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h1>
    <div>
        <a href="?month=<?php echo ($month - 1); ?>&year=<?php echo $year; ?>">Previous Month</a>
        | 
        <a href="?month=<?php echo ($month + 1); ?>&year=<?php echo $year; ?>">Next Month</a>
    </div>

    <?php
    // Nu kun je de opgehaalde werkschema's gebruiken om de kalender te bouwen
    echo buildCalendar($year, $month, $schedule);
    ?>
</body>
</html>
