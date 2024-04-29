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
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        table { width: 100%; max-width: 600px; margin: auto; background-color: #fff; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f9f9f9; }
        img { width: 100px; height: auto; border-radius: 50%; }
        input, button { padding: 10px; width: 95%; margin-top: 5px; }
        button { background-color: #5C67F2; color: #fff; border: none; cursor: pointer; }
        button:hover { background-color: #5058E5; }
        .error { color: red; } /* Stijl voor foutmeldingen */
    </style>
</head>
<body>
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
            <td>Name:</td>
            <td><?= htmlspecialchars($user['name']); ?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?= htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <td>Role:</td>
            <td><?= htmlspecialchars($user['role']); ?></td>
        </tr>
        <tr>
            <td>Tasks:</td>
            <td><?= htmlspecialchars($user['tasks'] ?? 'No tasks assigned'); ?></td>
        </tr>
        <tr>
            <td>Profile Picture:</td>
            <td><img src="<?= htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"></td>
        </tr>
        <tr>
            <td colspan="2">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>"><br>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"><br>
                    <input type="password" name="password" placeholder="Password (leave blank to not change)"><br>
                    <input type="file" name="profile_picture"><br>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
    </table>
</body>
</html>
