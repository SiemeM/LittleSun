<?php
require_once 'db/db_connect.php';
require_once 'classes/UserManager.php';
require_once 'classes/SessionManager.php';

$sessionManager = new SessionManager();
$sessionManager->checkManager(); // Ensures only managers have access

$userManager = new UserManager($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $profilePicture = $_FILES['profile_picture']['name']; // Process the image upload correctly

        $userManager->createUser($name, $email, $password, $profilePicture);
        $message = "User created successfully.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <title>Create User - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/styleCreateUser.css">
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
<h1>Create User</h1>
<p><?php echo $message; ?></p>

<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Name</label>
    <input type="text" name="name" required><br>
    <label for="email">Email</label>
    <input type="email" name="email" required><br>
    <label for="password">Password</label>
    <input type="password" name="password" required><br>
    <label for="profile_picture">Profile Picture</label>
    <input type="file" name="profile_picture" required id="uploadBtn">
    <label class="uploadLabel" for="uploadBtn">Upload File</label>
    <button type="submit">Create User</button>
</form>
</body>
</html>
