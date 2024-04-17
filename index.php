<?php
session_start();

// Check if the user is logged in, using a session variable set during login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Determine the content based on the role
function getUserContent($role) {
    switch ($role) {
        case 'admin':
            return "Welcome, Admin! Here are your admin tools and analytics.";
        case 'manager':
            return "Welcome, Manager! Here is your management dashboard.";
        case 'user':
            return "Welcome, User! Enjoy your visit.";
        default:
            return "Welcome! Please contact support to assign your role.";
    }
}

$userContent = getUserContent($_SESSION['role']);
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
    <p><?php echo $userContent; ?></p>
    <form action="" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
