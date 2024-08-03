<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/sms.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$history = get_sms_history();
// Get success message if any
$success_message = isset($_GET['success']) ? $_GET['success'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>SMS History</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>SMS History</h1>
        <?php if ($success_message): ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <table>
            <tr>
                <th>Recipient</th>
                <th>Message</th>
                <th>Sender ID</th>
                <th>Sent At</th>
            </tr>
            <?php foreach ($history as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['recipient']); ?></td>
                <td><?php echo htmlspecialchars($entry['message']); ?></td>
                <td><?php echo htmlspecialchars($entry['sender_id'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($entry['sent_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>