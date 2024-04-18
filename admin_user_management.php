<?php
session_start(); // Start een nieuwe sessie of hervat de bestaande sessie
require_once 'db_connect.php'; // Voeg het bestand voor de databaseverbinding toe

// Beveiligingscontrole om te verzekeren dat alleen een admin deze pagina kan bezoeken
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}

// Behandel het indienen van wijzigingen in de rol van gebruikers
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_role'])) {
    $user_id = $_POST['user_id']; // Het ID van de gebruiker wiens rol wordt gewijzigd
    $new_role = $_POST['role']; // De nieuwe rol die aan de gebruiker wordt toegewezen
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?"); // Bereid het SQL-statement voor
    $stmt->bind_param("si", $new_role, $user_id); // Koppel de parameters aan het SQL-statement
    $stmt->execute(); // Voer het SQL-statement uit
}

// Haal alle gebruikers op uit de database
$result = $conn->query("SELECT id, email, role FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC); // Sla de resultaten op in een associatieve array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Management</title>
</head>
<body>
    <h1>User Management</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role">
                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <button type="submit" name="change_role">Change Role</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
