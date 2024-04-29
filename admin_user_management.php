<?php
require_once 'db/db_connect.php'; // Make sure this is the correct path to your db_connect.php file
require_once 'classes/SessionManager.php';
require_once 'classes/UserManager.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);


$sessionManager = new SessionManager();
$sessionManager->checkAdmin();

$userManager = new UserManager($conn);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $userId = $_POST['user_id'];
    $newPassword = $_POST['new_password'];

    try {
        $userManager->resetPassword($userId, $newPassword);
        $message = "Password successfully reset for user ID: $userId";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

$managers = $userManager->getAllHubManagers(); // This method still needs to be added to UserManager
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Hub Manager Password</title>
</head>
<body>
<h1>Reset Hub Manager Password</h1>
<p><?php echo $message; ?></p>

<form action="" method="post">
    <label for="user_id">Select Hub Manager:</label>
    <select name="user_id" required>
        <?php foreach ($managers as $manager): ?>
            <option value="<?php echo $manager['id']; ?>"><?php echo htmlspecialchars($manager['name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required><br>

    <button type="submit" name="reset_password">Reset Password</button>
</form>
</body>
</html>
