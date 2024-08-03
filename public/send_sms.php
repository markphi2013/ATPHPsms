<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/sms.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
//require_login();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $senderId = !empty($_POST['senderId']) ? $_POST['senderId'] : null;

        if (isset($_POST['single_sms'])) {
            $to = $_POST['to'];
            $sms_message = $_POST['message'];
            $result = send_single_sms($to, $sms_message, $senderId);
            $message = "Single SMS sent successfully!";
        }

        if (isset($_POST['bulk_sms']) && isset($_FILES['csv_file'])) {
            if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
                $csv = array_map('str_getcsv', file($_FILES['csv_file']['tmp_name']));
                // Flatten the nested array
                $recipients = [];
                foreach ($csv as $row) {
                    if (isset($row[0])) {
                        $recipients[] = $row[0];
                    }
                }

                // Remove any empty values
                $recipients = array_filter($recipients);
                $sms_message = $_POST['message'];
                $senderId = !empty($_POST['senderId']) ? $_POST['senderId'] : null;
                $result = send_bulk_sms($recipients, $sms_message, $senderId);
                $message = "Bulk SMS sent successfully!";
            } else {
                throw new Exception("Error uploading CSV file: " . $_FILES['csv_file']['error']);
            }
        }

        // Redirect to history page on success
        header("Location: history.php?success=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Send SMS</title>
    <link rel="stylesheet" href="css/sendsms.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="container">
        <h1>Send SMS</h1>
        <?php if (isset($error) && $error) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="toggle-buttons">
            <button type="button" class="toggle-btn active" onclick="showSection('single-sms-section', this)">Single SMS</button>
            <button type="button" class="toggle-btn" onclick="showSection('bulk-sms-section', this)">Bulk SMS</button>
        </div>

        <div id="single-sms-section" class="form-section active">
            <h2>Single SMS</h2>
            <form method="post">
                <input type="text" name="to" placeholder="Recipient Phone Number" required><br>
                <input type="text" name="senderId" placeholder="Sender ID (optional)"><br>
                <textarea name="message" placeholder="Message" required></textarea><br>
                <input type="submit" name="single_sms" value="Send Single SMS">
            </form>
        </div>

        <div id="bulk-sms-section" class="form-section">
            <h2>Bulk SMS</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv" required><br>
                <input type="text" name="senderId" placeholder="Sender ID (optional)"><br>
                <textarea name="message" placeholder="Message" required></textarea><br>
                <input type="submit" name="bulk_sms" value="Send Bulk SMS">
            </form>
        </div>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>

    <script>
        function showSection(sectionId, btn) {
            const sections = document.querySelectorAll('.form-section');
            const buttons = document.querySelectorAll('.toggle-btn');
            sections.forEach(section => section.classList.remove('active'));
            buttons.forEach(button => button.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
            btn.classList.add('active');
        }
    </script>
</body>

</html>

