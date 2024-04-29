<?php
require_once 'db/db_connect.php';
require_once 'classes/SessionManager.php';
require_once 'classes/TaskTypeManager.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$taskTypeManager = new TaskTypeManager($conn);
$message = '';

// Fetch task types outside the try-catch block
$taskTypes = $taskTypeManager->getAllTaskTypes();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['add'])) {
            $taskTypeName = $_POST['task_type_name'];
            $taskTypeManager->addTaskType($taskTypeName);
            $message = "Task type added.";
            // Refresh task types after successful addition
            $taskTypes = $taskTypeManager->getAllTaskTypes();
        } elseif (isset($_POST['delete'])) {
            $taskTypeId = $_POST['task_type_id'];
            $taskTypeManager->deleteTaskType($taskTypeId);
            $message = "Task type deleted.";
            // Refresh task types after successful deletion
            $taskTypes = $taskTypeManager->getAllTaskTypes();
        }
    }
} catch (Exception $e) {
    $message = $e->getMessage();
    // Refresh task types if an error occurs
    $taskTypes = $taskTypeManager->getAllTaskTypes();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Task Types</title>
</head>
<body>
<h1>Manage Task Types</h1>
<p><?php echo $message; ?></p>

<form action="" method="post">
    <input type="text" name="task_type_name" placeholder="Name of task type" required>
    <button type="submit" name="add">Add Task Type</button>
</form>

<?php if (!empty($taskTypes)): ?>
    <ul>
        <?php foreach ($taskTypes as $type): ?>
            <li><?php echo htmlspecialchars($type['name']); ?>
                <form method="post">
                    <input type="hidden" name="task_type_id" value="<?php echo $type['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No task types found.</p>
<?php endif; ?>
</body>
</html>
