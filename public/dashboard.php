<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>Welcome to your Dashboard</h1>
        <nav>
            <ul>
                <li><a href="send_sms.php">Send SMS</a></li>
                <li><a href="history.php">SMS History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>