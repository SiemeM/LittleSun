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

// Verplaats de logica om de taaktypen op te halen buiten de try-catch block
try {
    $taskTypes = $taskTypeManager->getAllTaskTypes(); // Verplaats deze regel naar boven
} catch (Exception $e) {
    $message = "Kon taaktypen niet laden: " . $e->getMessage();
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['add'])) {
            $taskTypeName = $_POST['task_type_name'];
            $taskTypeManager->addTaskType($taskTypeName);
            $message = "Taaktype toegevoegd.";
            $taskTypes = $taskTypeManager->getAllTaskTypes(); // Haal opnieuw op na succesvol toevoegen
        } elseif (isset($_POST['delete'])) {
            $taskTypeId = $_POST['task_type_id'];
            $taskTypeManager->deleteTaskType($taskTypeId);
            $message = "Taaktype verwijderd.";
            $taskTypes = $taskTypeManager->getAllTaskTypes(); // Haal opnieuw op na succesvol verwijderen
        }
    }
} catch (Exception $e) {
    $message = $e->getMessage();
    $taskTypes = $taskTypeManager->getAllTaskTypes(); // Haal opnieuw op als er een fout optreedt
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Beheer Taaktypen</title>
</head>
<body>
<h1>Beheer Taaktypen</h1>
<p><?php echo $message; ?></p>

<form action="" method="post">
    <input type="text" name="task_type_name" placeholder="Naam van taaktype" required>
    <button type="submit" name="add">Taaktype Toevoegen</button>
</form>

<?php if (!empty($taskTypes)): ?>
    <ul>
        <?php foreach ($taskTypes as $type): ?>
            <li><?php echo htmlspecialchars($type['name']); ?>
                <form method="post">
                    <input type="hidden" name="task_type_id" value="<?php echo $type['id']; ?>">
                    <button type="submit" name="delete">Verwijderen</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Geen taaktypen gevonden.</p>
<?php endif; ?>
</body>
</html>