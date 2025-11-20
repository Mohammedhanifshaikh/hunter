<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
  exit;
}

// Load environment variables from a private .env (one level above public/)
$envLoader = __DIR__ . '/../config/load_env.php';
if (file_exists($envLoader)) {
  require_once $envLoader;
  if (function_exists('load_env_file')) {
    @load_env_file(dirname(__DIR__) . '/.env');
  }
}

// Prefer Composer autoload (../vendor/autoload.php). If not available, fallback to manual src under public/vendor/phpmailer/src
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
  require_once $composerAutoload;
} else {
  $phpmailerBase = __DIR__ . '/vendor/phpmailer/src';
  if (is_dir($phpmailerBase)) {
    require_once $phpmailerBase . '/Exception.php';
    require_once $phpmailerBase . '/PHPMailer.php';
    require_once $phpmailerBase . '/SMTP.php';
  } else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Mail service not available: PHPMailer not found. Install via Composer (../vendor/autoload.php) or upload PHPMailer src to public/vendor/phpmailer/src.']);
    exit;
  }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sanitize_line_breaks($value) {
  return str_replace(["\r", "\n", "%0a", "%0d"], '', $value);
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
// Honeypot (hidden field) to stop bots
$honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';

// If honeypot is filled, pretend success without sending mail
if ($honeypot !== '') {
  echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
  exit;
}

// reCAPTCHA v3 verification removed

if ($name === '' || $email === '' || $phone === '' || $message === '') {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'All fields are required.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
  exit;
}

// Strip HTML
$name = strip_tags($name);
$email = strip_tags($email);
$phone = strip_tags($phone);
$message = strip_tags($message);

// Header injection protection (line breaks are already removed later for headers)

// Length limits and safe characters
$len = function($v) { return function_exists('mb_strlen') ? mb_strlen($v, 'UTF-8') : strlen($v); };
if ($len($name) < 2 || $len($name) > 80) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Name length must be between 2 and 80 characters.']);
  exit;
}
if ($len($email) > 254) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Email is too long.']);
  exit;
}
// Allow only letters, spaces, apostrophes, hyphens, and dots in name
if (!preg_match("/^[\p{L} .'-]+$/u", $name)) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Name contains invalid characters.']);
  exit;
}
// Phone: allow digits, spaces, plus, parentheses, and dashes; enforce overall length 10–14
if (!preg_match('/^[0-9+\-()\s]{10,14}$/', $phone)) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Phone number format is invalid.']);
  exit;
}
if ($len($message) < 10 || $len($message) > 2000) {
  http_response_code(422);
  echo json_encode(['success' => false, 'message' => 'Message length must be between 10 and 2000 characters.']);
  exit;
}

$to = getenv('MAIL_TO') ?: 'info@hunterlogistics.in';
$subject = 'New Contact Form Submission';

$safeName = sanitize_line_breaks($name);
$safeEmail = sanitize_line_breaks($email);
$safePhone = sanitize_line_breaks($phone);

$body = "You have received a new message from the contact form.\n\n" .
        "Name: {$safeName}\n" .
        "Email: {$safeEmail}\n" .
        "Phone: {$safePhone}\n\n" .
        "Message:\n{$message}\n";

$smtpHost = getenv('SMTP_HOST') ?: '';
$smtpPort = getenv('SMTP_PORT') ?: '';
$smtpUser = getenv('SMTP_USER') ?: '';
$smtpPass = getenv('SMTP_PASS') ?: '';
$smtpSecure = strtolower(getenv('SMTP_SECURE') ?: 'ssl');
$fromEmail = getenv('MAIL_FROM') ?: 'no-reply@hunterlogistics.in';
$fromName = getenv('MAIL_FROM_NAME') ?: 'Hunter Logistics';

if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '' || $smtpPort === '') {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'SMTP configuration is missing.']);
  exit;
}

try {
  $mail = new PHPMailer(true);
  $mail->CharSet = 'UTF-8';
  $mail->isSMTP();
  $mail->Host = $smtpHost;
  $mail->SMTPAuth = true;
  $mail->Username = $smtpUser;
  $mail->Password = $smtpPass;
  if ($smtpSecure === 'ssl') {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  } else {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  }
  $mail->Port = (int)$smtpPort;

  $mail->setFrom($fromEmail, $fromName);
  $mail->addAddress($to);
  if (filter_var($safeEmail, FILTER_VALIDATE_EMAIL)) {
    $mail->addReplyTo($safeEmail, $safeName);
  }

  $mail->isHTML(false);
  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->AltBody = $body;

  $mail->send();
  echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
}

