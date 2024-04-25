<?php
require_once 'db/db_connect.php';
require_once 'classes/TaskAssignmentManager.php';
require_once 'classes/UserManager.php';
require_once 'classes/SessionManager.php';

$sessionManager = new SessionManager();
$sessionManager->checkManager(); // Verifieert dat de huidige gebruiker een manager is


$taskManager = new TaskAssignmentManager($conn);
$userManager = new UserManager($conn); // Correcte instantiatie van UserManager
$message = '';

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId = $_POST['user_id'];
        $taskTypeId = $_POST['task_type_id'];
        $taskManager->assignTaskTypeToUser($userId, $taskTypeId);
        $message = "Taaktype succesvol toegewezen aan gebruiker.";
    }
    $taskTypes = $taskManager->getAvailableTaskTypes();
    $users = $userManager->getAllUsers(); // Nu correct opgehaald met UserManager
} catch (Exception $e) {
    $message = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Taaktype Toewijzen aan Gebruiker</title>
</head>
<body>
<h1>Taaktype Toewijzen</h1>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form action="" method="post">
    <label for="user_id">Gebruiker:</label>
    <select name="user_id" required>
        <?php foreach ($users as $user): ?>
            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
        <?php endforeach; ?>
    </select><br>
    <label for="task_type_id">Taaktype:</label>
    <select name="task_type_id" required>
        <?php foreach ($taskTypes as $type): ?>
            <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Toewijzen</button>
</form>
</body>
</html>