<?php
require_once 'classes/SessionManager.php';
require_once 'classes/ContentGenerator.php';

$sessionManager = new SessionManager();
$contentGenerator = new ContentGenerator();

$sessionManager->checkUserLoggedIn();

if (isset($_POST['logout'])) {
    $sessionManager->logout();
}

$userContent = $contentGenerator->getUserContent($_SESSION['role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <?php echo $userContent; ?>
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
