<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/src/SMTP.php';
require __DIR__ . '/vendor/phpmailer/src/Exception.php';

// Log file (make sure this file is writable)
$logFile = __DIR__ . '/email-debug.log';

// Send JSON response
header('Content-Type: application/json; charset=utf-8');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Helper to clean input
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Get form data
$name    = isset($_POST['fullname']) ? clean($_POST['fullname']) : '';
$email   = isset($_POST['emailaddr']) ? clean($_POST['emailaddr']) : '';
$phone   = isset($_POST['phonenumber']) ? clean($_POST['phonenumber']) : '';
$message = isset($_POST['inquiry']) ? clean($_POST['inquiry']) : '';
$honeypot = isset($_POST['url_field']) ? $_POST['url_field'] : '';

// Honeypot spam check
if ($honeypot !== '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// reCAPTCHA v2 verification (if secret configured)
$recaptchaSecret = getenv('RECAPTCHA_SECRET') ?: '';
$recaptchaResponse = isset($_POST['g-recaptcha-response']) ? trim($_POST['g-recaptcha-response']) : '';
if ($recaptchaSecret !== '') {
    if ($recaptchaResponse === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please verify that you are not a robot.']);
        exit;
    }
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $postFields = http_build_query([
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ]);
    $respBody = '';
    if (function_exists('curl_init')) {
        $ch = curl_init($verifyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $respBody = curl_exec($ch);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $postFields,
                'timeout' => 10
            ]
        ]);
        $respBody = @file_get_contents($verifyUrl, false, $context);
    }
    $ok = false;
    if ($respBody) {
        $data = json_decode($respBody, true);
        $ok = is_array($data) && !empty($data['success']);
    }
    if (!$ok) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'reCAPTCHA verification failed. Please try again.']);
        exit;
    }
}

// Validate required fields
if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email']);
    exit;
}

// SMTP configuration from environment variables for safety
$smtpHost = getenv('SMTP_HOST') ?: 'mail.hunterlogistics.in';
$smtpPort = getenv('SMTP_PORT') ?: 465;
$smtpUser = getenv('SMTP_USER') ?: 'info@hunterlogistics.in';
$smtpPass = getenv('SMTP_PASS') ?: 'hunter2025'; // Replace with your env variable
$smtpSecure = getenv('SMTP_SECURE') ?: 'ssl';

$mail = new PHPMailer(true);

try {
    // PHPMailer SMTP setup
    $mail->isSMTP();
    $mail->SMTPDebug = 2; // DEBUG MODE: change to 0 in production
    $mail->Debugoutput = 'error_log'; // logs to PHP error log
    $mail->SMTPAuth = true;
    $mail->Host = $smtpHost;
    $mail->Port = $smtpPort;

    if (strtolower($smtpSecure) === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;

    // Sender & recipient
    $mail->setFrom($smtpUser, 'Hunter Logistics');
    $mail->addAddress($smtpUser); // receive submissions
    $mail->addReplyTo($email, $name); // customer reply

    // Email content
    $mail->isHTML(false);
    $mail->Subject = "New Contact Form Submission - $name";
    $mail->Body = "=================================\n";
    $mail->Body .= "NEW CONTACT FORM SUBMISSION\n";
    $mail->Body .= "=================================\n\n";
    $mail->Body .= "Name: $name\n";
    $mail->Body .= "Email: $email\n";
    $mail->Body .= "Phone: $phone\n\n";
    $mail->Body .= "Message:\n$message\n";
    $mail->Body .= "-----------------------------------\n";
    $mail->Body .= "Submitted: " . date('Y-m-d H:i:s') . "\n";

    // Log attempt
    $logEntry = date('Y-m-d H:i:s') . " - Email Attempt\n";
    $logEntry .= "To: $smtpUser\nFrom: $smtpUser\nReply-To: $email\nCustomer: $name ($email)\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Send
    $mail->send();

    // Log success
    file_put_contents($logFile, "Result: SUCCESS\n---\n", FILE_APPEND);
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);

} catch (Exception $e) {
    // Log PHPMailer error
    $errorMsg = "Result: FAILED - {$mail->ErrorInfo}\n---\n";
    file_put_contents($logFile, $errorMsg, FILE_APPEND);

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send. Please try again.']);
}
?>