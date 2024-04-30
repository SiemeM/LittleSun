<?php
session_start();
require_once 'db/db_connect.php';
require_once 'classes/TimeOffManager.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);


$timeOffManager = new TimeOffManager($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestId = $_POST['request_id'];
    if ($_POST['action'] == 'approve') {
        $timeOffManager->approveTimeOff($requestId);
        echo "<p>The request has been approved.</p>";
    } elseif ($_POST['action'] == 'decline') {
        $managerNotes = $_POST['manager_notes'] ?? '';
        $timeOffManager->declineTimeOff($requestId, $managerNotes);
        echo "<p>The request has been declined.</p>";
    }
}

$requests = $timeOffManager->getAllPendingRequests(); // Deze methode moet nog worden toegevoegd in TimeOffManager klasse
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Time Off Requests</title>
</head>
<body>
    <h1>Manage Time Off Requests</h1>
    <?php foreach ($requests as $request): ?>
        <div>
            <p>User ID: <?= $request['user_id']; ?></p>
            <p>Start Date: <?= $request['start_date']; ?></p>
            <p>End Date: <?= $request['end_date']; ?></p>
            <p>Reason: <?= $request['reason']; ?></p>
            <form method="post" action="">
                <input type="hidden" name="request_id" value="<?= $request['id']; ?>">
                <button type="submit" name="action" value="approve">Approve</button>
                <button type="submit" name="action" value="decline">Decline</button>
                <input type="text" name="manager_notes" placeholder="Reason for declining (optional)">
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
