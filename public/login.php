<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($username, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['register'])) {
        $username = $_POST['new_username'];
        $password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($password !== $confirm_password) {
            $error = "Passwords do not match";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long";
        } else {
            try {
                register_user($username, $password);
                $success = "User registered successfully. You can now log in.";
            } catch (PDOException $e) {
                $error = "Registration failed. Username may already exist.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Welcome</h1>
            <?php 
            if (!empty($error)) echo "<p class='error'>$error</p>";
            if (!empty($success)) echo "<p class='success'>$success</p>";
            ?>
            
            <div class="button-box">
                <div id="btn"></div>
                <button type="button" class="toggle-btn" onclick="toggleForm('login')">Log In</button>
                <button type="button" class="toggle-btn" onclick="toggleForm('register')">Register</button>
            </div>
            
            <form id="login" class="input-group active" method="post" action="">
                <input type="text" name="username" class="input-field" placeholder="Username" required>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
                <button type="submit" name="login" class="submit-btn">Log In</button>
            </form>
            
            <form id="register" class="input-group" method="post" action="">
                <input type="text" name="new_username" class="input-field" placeholder="Username" required>
                <input type="password" name="new_password" class="input-field" placeholder="Password" required>
                <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required>
                <button type="submit" name="register" class="submit-btn">Register</button>
            </form>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>
