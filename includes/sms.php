<?php
// require_once __DIR__ . '/../vendor/autoload.php';
// use AfricasTalking\SDK\AfricasTalking;
require_once '../includes/AfricasTalkingGateway.php';


$username = '234';
$apiKey = '23';

$gateway = new AfricasTalkingGateway($username, $apiKey);

try {
    //$AT = new AfricasTalking($username, $apiKey);
    $gateway = new AfricasTalkingGateway($username, $apiKey);
    //$sms = $AT->sms();
    //$sms = $gateway->sendMessage();
} catch (Exception $e) {
    error_log("Error initializing Africa's Talking SDK: " . $e->getMessage());
    throw new Exception("Failed to initialize SMS service. Please try again later.");
}


function send_single_sms($to, $message, $senderId = null) {
    global $sms, $pdo, $gateway;
    
    try {
        // $options = [
        //     'to' => $to,
        //     'message' => $message
        // ];
        
        if ($senderId) {
            //$options['from'] = $senderId;
            $result = $gateway->sendMessage($to, $message, $senderId);
        }
        
        //$result = $sms->send($options);
        $result = $gateway->sendMessage($to, $message);

        $stmt = $pdo->prepare("INSERT INTO sms_history (user_id, recipient, message, sender_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $to, $message, $senderId]);

        return $result;
    } catch (Exception $e) {
        error_log("Error sending single SMS: " . $e->getMessage());
        throw new Exception("Failed to send SMS. Please try again later.");
    }
}

function send_bulk_sms($recipients, $message, $senderId = null) {
    global $sms, $pdo, $gateway;
    
    try {
        $to = implode(',', $recipients);

        if ($senderId) {
            $result = $gateway->sendMessage($to, $message, $senderId);
        }
        $result = $gateway->sendMessage($to, $message);
        
        //$result = $sms->send($options);

        foreach ($recipients as $recipient) {
            $stmt = $pdo->prepare("INSERT INTO sms_history (user_id, recipient, message, sender_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $recipient, $message, $senderId]);
        }

        return $result;
    } catch (Exception $e) {
        error_log("Error sending bulk SMS: " . $e->getMessage());
        throw new Exception("Failed to send bulk SMS. Please try again later.");
    }
}

function get_sms_history() {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM sms_history WHERE user_id = ? ORDER BY sent_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching SMS history: " . $e->getMessage());
        throw new Exception("Failed to retrieve SMS history. Please try again later.");
    }
}