<?php
session_start();
require_once 'db/db_connect.php';
require_once 'classes/UserManager.php';
require_once 'classes/FileUploader.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userManager = new UserManager($conn);
$user = $userManager->getUserById($_SESSION['user_id']);
$fileUploader = new FileUploader("profile_pictures/");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? $user['name'];
    $email = $_POST['email'] ?? $user['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $profilePicture = $user['profile_picture'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadResult = $fileUploader->upload($_FILES['profile_picture']);
        if (is_string($uploadResult)) {
            $profilePicture = $uploadResult;
        } else {
            $errors[] = $uploadResult;
        }
    }

    if (empty($errors)) {
        if ($userManager->updateUser($user['id'], $name, $email, $password, $profilePicture)) {
            echo "<p>Profile successfully updated.</p>";
            $user = $userManager->getUserById($_SESSION['user_id']);
        } else {
            $errors[] = "An error occurred updating the profile.";
        }
    }
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
    <title>Profile - Little Sun Shiftplanner</title>
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://use.typekit.net/qcm6xlo.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/styleUserProfile.css">
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
    <?php if (!empty($errors)): ?>
    <div class="error">
        <?= implode('<br>', $errors); ?>
    </div>
    <?php endif; ?>
    <table>
        <tr>
            <th colspan="2">User Profile</th>
        </tr>
        <tr>
            <td class="label">Name</td>
            <td class="input"><?= htmlspecialchars($user['name']); ?></td>
        </tr>
        <tr>
        <td class="label">Email</td>
        <td class="input"><?= htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
        <td class="label">Role</td>
        <td class="input"><?= htmlspecialchars($user['role']); ?></td>
        </tr>
        <tr>
        <td class="label">Tasks</td>
        <td class="input"><?= htmlspecialchars($user['tasks'] ?? 'No tasks assigned'); ?></td>
        </tr>
        <tr>
        <td class="label">Profile Picture</td>
        <td class="input"><img src="<?= htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"></td>
        </tr>
        <tr>
            <td class="tdForm" colspan="2">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>"><br>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"><br>
                    <input type="password" name="password" placeholder="Password (leave blank to not change)"><br>
                    <input type="file" name="profile_picture" required id="uploadBtn">
                    <label class="uploadLabel" for="uploadBtn">Change Profile Picture</label>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
    </table>
</body>
</html>
