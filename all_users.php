<?php
require_once 'db/db_connect.php';
require_once 'classes/UserManager.php';

$userManager = new UserManager($conn);

$users = $userManager->getUsersWithTasks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Overview</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        img { width: 100px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Users Overview</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Photo</th>
            <th>Assigned Task Type</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td>
                    <?php if ($user['profile_picture']): ?>
                        <img src="profile_pictures/<?= htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                    <?php else: ?>
                        No photo
                    <?php endif; ?>
                </td>
                <td><?= $user['task_type']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
