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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <title>Manager Panel - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/styleIndex.css">
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
    <div class="managerDash">
        <?php echo $userContent; ?>
    </div>
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>