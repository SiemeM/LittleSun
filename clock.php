<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Stel de tijdzone in voor Zambia (UTC+2)
date_default_timezone_set('Africa/Lusaka');

require_once 'db/db_connect.php';
$database = new Database();
$db = $database->getConnection();

$userId = $_SESSION['user_id'] ?? null;
$message = '';
$workedHours = 0;
$overtimeHours = 0;

if (!$userId) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['clockIn'])) {
    $currentTime = date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $query = "INSERT INTO time_tracking (user_id, clock_in_time, date) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $userId);
    $stmt->bindParam(2, $currentTime);
    $stmt->bindParam(3, $date);
    if ($stmt->execute()) {
        $message = "Successfully clocked in at " . date('H:i:s', strtotime($currentTime));
    } else {
        $message = "Error clocking in.";
    }
}

if (isset($_POST['clockOut'])) {
    $currentTime = date('Y-m-d H:i:s');
    $query = "UPDATE time_tracking SET clock_out_time = ? WHERE user_id = ? AND date = CURDATE() AND clock_out_time IS NULL";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $currentTime);
    $stmt->bindParam(2, $userId);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $message = "Successfully clocked out at " . date('H:i:s', strtotime($currentTime));
    } else {
        $message = "Error clocking out or already clocked out.";
    }
}

// Bereken gewerkte uren en overuren
if ($userId) {
    $query = "SELECT clock_in_time, clock_out_time FROM time_tracking WHERE user_id = ? AND date = CURDATE()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $clockInTime = strtotime($result['clock_in_time']);
        $clockOutTime = strtotime($result['clock_out_time']);
        if ($clockInTime && $clockOutTime) {
            $workedHours = ($clockOutTime - $clockInTime) / 3600;
            $overtimeHours = $workedHours - 8; // Veronderstelt een 8-urige werkdag
            $overtimeHours = $overtimeHours > 0 ? $overtimeHours : 0;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Schedule Tracker</title>
</head>
<body>
    <h1>Work Schedule Tracker</h1>
    <p><?php echo $message; ?></p>
    <form method="post">
        <button type="submit" name="clockIn">Clock In</button>
        <button type="submit" name="clockOut">Clock Out</button>
    </form>
    <?php if ($workedHours): ?>
        <p>Total Worked Hours Today: <?php echo number_format($workedHours, 2); ?> hours</p>
        <p>Overtime Hours Today: <?php echo number_format($overtimeHours, 2); ?> hours</p>
    <?php endif; ?>
</body>
</html>
