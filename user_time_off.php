<?php
session_start();
require_once 'db/db_connect.php';
require_once 'classes/TimeOffManager.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $reason = $_POST['reason'];

    $timeOffManager = new TimeOffManager($conn);
    $timeOffManager->requestTimeOff($userId, $startDate, $endDate, $reason);

    echo "<p>Your request has been submitted for approval.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Time Off</title>
</head>
<body>
    <h1>Request Time Off</h1>
    <form method="post" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required><br>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required><br>
        <label for="reason">Reason:</label>
        <select name="reason">
            <option value="vacation">Vacation</option>
            <option value="birthday">Birthday</option>
            <option value="maternity">Maternity</option>
        </select><br>
        <button type="submit">Request Time Off</button>
    </form>
</body>
</html>
