<?php
session_start();


require_once 'db/db_connect.php'; // Zorg ervoor dat deze klasse de databaseverbinding correct retourneert
require_once 'classes/ScheduleManager.php';
require_once 'classes/UserManager.php';
require_once 'classes/LocationManager.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

// Initialiseer eenmaal de database verbinding
$database = new Database();
$db = $database->getConnection();

// Initialiseer je managers met de databaseverbinding
$scheduleManager = new ScheduleManager($db);
$userManager = new UserManager($db);
$locationManager = new LocationManager($db);

// Verkrijg gebruikers en locaties
$users = $userManager->getUsersByRole('user');
$locations = $locationManager->getLocations();
$taskTypes = isset($_POST['user_id']) ? $scheduleManager->getTaskTypesForUser($_POST['user_id']) : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $scheduleManager->assignTask(
        $_POST['user_id'],
        $_POST['hub_location_id'],
        $_POST['task_type_id'],
        $_POST['work_date'],
        $_POST['start_time'],
        $_POST['end_time']
    );
    echo "<p>$result</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <title>Assign Tasks - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/styleAssignTasks.css">
</head>
<body>
<nav>
        <a href="index.php">
        <img src="img/logo.png" alt="" id="logo">
        </a>
        <div class="navItems">
            <a href="login.php" class="navItem4">Logout</a>
        </div>
</nav>
    <div class="container">
        <h1>Assign Tasks to Users</h1>
        <form method="post" action="">
            <label for="user_id">User</label>
            <select name="user_id" required onchange="this.form.submit()">
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id']; ?>" <?= isset($_POST['user_id']) && $_POST['user_id'] == $user['id'] ? 'selected' : '' ?>><?= $user['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="hub_location_id">Location</label>
            <select name="hub_location_id" required>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id']; ?>"><?= $location['location_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="task_type_id">Task Type</label>
            <select name="task_type_id" required>
                <?php foreach ($taskTypes as $taskType): ?>
                    <option value="<?= $taskType['id']; ?>"><?= $taskType['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="work_date">Work Date</label>
            <input type="date" name="work_date" required>

            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" required>

            <label for="end_time">End Time</label>
            <input type="time" name="end_time" required>

            <button type="submit">Assign Task</button>
        </form>
    </div>
</body>
</html>
