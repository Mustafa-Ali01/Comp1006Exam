<?php
require 'connect.php';
$alert = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $mail = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';

    // REGISTER
    if (isset($_POST['do_register'])) {
        if (empty($user) || empty($pass) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $alert = "Error: Fill all fields correctly.";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$user, $mail, $hashed]);
            $alert = "Account created! You can now log in.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Admin Portal</title></head>
<body>
    <h2>Admin Access</h2>
    <p style="color: blue;"><?= $alert ?></p>

    <form method="POST">
        <input type="text" name="user" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email (Register only)"><br>
        <input type="password" name="pass" placeholder="Password" required><br>
        <button name="do_register">Register New Admin</button>
        <button name="do_login">Login</button>
    </form>
</body>
</html>