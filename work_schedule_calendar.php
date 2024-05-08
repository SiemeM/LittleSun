<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db/db_connect.php';
$database = new Database();
$db = $database->getConnection();

function getSchedule($startDate, $endDate) {
    global $db;
    $query = "SELECT ws.user_id, ws.task_type_id, ws.hub_location_id, ws.work_date, ws.start_time, ws.end_time, u.name as user_name, tt.name as task_name
    FROM work_schedules ws
    JOIN users u ON ws.user_id = u.id
    JOIN task_types tt ON ws.task_type_id = tt.id
    WHERE ws.work_date BETWEEN '2024-05-01' AND '2024-05-31'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $startDate);
    $stmt->bindParam(2, $endDate);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

function buildCalendar($year, $month) {
    global $db;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $firstDayOfWeek = date('w', $firstDayOfMonth);

    $startDate = date('Y-m-01', $firstDayOfMonth);
    $endDate = date('Y-m-t', $firstDayOfMonth);

    $schedules = getSchedule($startDate, $endDate);

    // Bouw een array om de planningen gemakkelijk toegankelijk te maken op datum
    $scheduleMap = [];
    foreach ($schedules as $schedule) {
        $scheduleMap[$schedule['work_date']][] = $schedule;
    }

    $calendar = '<table>';
    $calendar .= '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
    $calendar .= '<tr>';

    // Vul lege cellen voor de start van de maand
    for ($i = 0; $i < $firstDayOfWeek; $i++) {
        $calendar .= '<td></td>';
    }

    // Genereer de dagen van de maand
    for ($day = 1; $day <= $daysInMonth; $day++, $firstDayOfWeek++) {
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $calendar .= "<td>$day";

        // Voeg schema's toe indien beschikbaar voor deze datum
        if (isset($scheduleMap[$date])) {
            foreach ($scheduleMap[$date] as $schedule) {
                $calendar .= "<br><small>" . htmlspecialchars($schedule['user_name']) . ": " .
                             htmlspecialchars($schedule['task_name']) . " (" .
                             htmlspecialchars($schedule['start_time']) . '-' .
                             htmlspecialchars($schedule['end_time']) . ")</small>";
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

$month = isset($_GET['month']) ? $_GET['month'] : date('n');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
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
    <?php
    echo '<div>';
    echo '<a href="?month=' . ($month - 1) . '&year=' . $year . '">Previous Month</a>';
    echo ' | ';
    echo '<a href="?month=' . ($month + 1) . '&year=' . $year . '">Next Month</a>';
    echo '</div>';

    echo buildCalendar($year, $month);
    ?>
</body>
</html>
