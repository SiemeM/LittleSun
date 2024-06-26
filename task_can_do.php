<?php
    require_once 'db/db_connect.php';
    require_once 'classes/TaskAssignmentManager.php';
    require_once 'classes/UserManager.php';
    require_once 'classes/SessionManager.php';

    $sessionManager = new SessionManager();
    $sessionManager->checkManager(); // Verifies that the current user is a manager

    $taskManager = new TaskAssignmentManager($conn);
    $userManager = new UserManager($conn); // Correct instantiation of UserManager
    $message = '';

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    try {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $taskTypeId = $_POST['task_type_id'];
            $taskManager->assignTaskTypeToUser($userId, $taskTypeId);
            $message = "Task type successfully assigned to user.";
        }
        $taskTypes = $taskManager->getAvailableTaskTypes();
        $users = $userManager->getAllUsers(); // Now fetched correctly using UserManager
    } catch (Exception $e) {
        $message = $e->getMessage();
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
        <link rel="stylesheet" href="style/styleTaskCanDo.css">
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
        <h1>Assign Task Type</h1>
        <?php if ($message) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label for="user_id">User</label>
            <select name="user_id" required>
                <?php foreach ($users as $user) : ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="task_type_id">Task Type</label>
            <select name="task_type_id" required>
                <?php foreach ($taskTypes as $type) : ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                <?php endforeach; ?>
            </select><br>
            <button type="submit">Assign</button>
        </form>
    </body>

    </html>