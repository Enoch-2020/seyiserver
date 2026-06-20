<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// Debug (testing only)
$mail->SMTPDebug  = 2;
$mail->Debugoutput = 'html';
$mail->Timeout = 20;

try {
    // Enable SMTP
    $mail->isSMTP();

    // ✅ CORRECT SMTP HOST (NOT EMAIL)
    $mail->Host = 'mail.seyimultiservice.com.ng';
    $mail->SMTPAuth = true;

    // Hosting email credentials
    $mail->Username = 'support@seyimultiservice.com.ng';
    $mail->Password = 'EnochandHannah@2020';

    // Encryption & port
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender MUST match the SMTP user/domain
    $mail->setFrom('support@seyimultiservice.com.ng', 'Website Notifications');

    // Receiver (your Gmail)
    $mail->addAddress('seyikabiru@gmail.com');

    // Email content
    $mail->isHTML(false);
    $mail->Subject = 'PHPMailer SMTP Test (Hosting SMTP)';
    $mail->Body    = 'If you received this email, hosting SMTP is working perfectly.';

    // Send
    $mail->send();

    echo '✅ SMTP test successful using hosting SMTP.';
} catch (Exception $e) {
    echo '❌ SMTP failed: ' . $mail->ErrorInfo;
}
