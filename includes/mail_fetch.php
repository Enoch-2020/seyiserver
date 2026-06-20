<?php
require __DIR__ . '/db.php'; // your existing DB connection
require __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'mail.seyimultiservice.com.ng';
    $mail->SMTPAuth = true;
    $mail->Username = 'support@seyimultiservice.com.ng';
    $mail->Password = 'EMAIL_PASSWORD'; // replace with hosting email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('support@seyimultiservice.com.ng', 'Website Notifications');

    // Fetch new enquiries and mark as processing (cron-safe)
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT * FROM enquiries_form WHERE status = 'new' FOR UPDATE");
    $stmt->execute();
    $enquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updateProcessing = $pdo->prepare("UPDATE enquiries_form SET status = 'processing' WHERE id = ?");
    foreach ($enquiries as $enquiry) {
        $updateProcessing->execute([$enquiry['id']]);
    }
    $pdo->commit();

    if (!empty($enquiries)) {
        // Keep SMTP connection open for all messages
        $mail->SMTPKeepAlive = true;

        foreach ($enquiries as $enquiry) {
            $mail->clearAddresses();
            $mail->clearReplyTos();
            $mail->clearCCs();
            $mail->clearBCCs();

            // Main recipient
            $mail->addAddress('seyikabiru@gmail.com');

            // Hidden copy to support
            $mail->addBCC('support@seyimultiservice.com.ng');

            // Reply goes to client
            $mail->addReplyTo($enquiry['email'], $enquiry['full_name']);

            $mail->isHTML(true);
            $mail->Subject = "New Enquiry from {$enquiry['full_name']}";

            // HTML email body
            $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    h2 { color: #2E86C1; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 8px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <h2>New Enquiry Received</h2>
                <table>
                    <tr><td><strong>Full Name</strong></td><td>{$enquiry['full_name']}</td></tr>
                    <tr><td><strong>Email</strong></td><td>{$enquiry['email']}</td></tr>
                    <tr><td><strong>Phone</strong></td><td>{$enquiry['phone']}</td></tr>
                    <tr><td><strong>Budget</strong></td><td>{$enquiry['budget']}</td></tr>
                    <tr><td><strong>Project Purpose</strong></td><td>{$enquiry['project_purpose']}</td></tr>
                    <tr><td><strong>Project Description</strong></td><td>{$enquiry['project_description']}</td></tr>
                    <tr><td><strong>Submitted At</strong></td><td>{$enquiry['created_at']}</td></tr>
                </table>
            </body>
            </html>
            ";

            // Plain text fallback
            $mail->AltBody = 
                "New Enquiry Received\n\n" .
                "Full Name: {$enquiry['full_name']}\n" .
                "Email: {$enquiry['email']}\n" .
                "Phone: {$enquiry['phone']}\n" .
                "Budget: {$enquiry['budget']}\n" .
                "Project Purpose: {$enquiry['project_purpose']}\n" .
                "Project Description: {$enquiry['project_description']}\n" .
                "Submitted At: {$enquiry['created_at']}";

            // Send email
            $mail->send();

            // Mark as contacted
            $update = $pdo->prepare("UPDATE enquiries_form SET status = 'contacted' WHERE id = ?");
            $update->execute([$enquiry['id']]);
        }

        // Close SMTP connection after all messages
        $mail->smtpClose();
    }

    echo "✅ All new enquiries sent to Gmail and support email successfully.";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ Mailer Error: " . $mail->ErrorInfo;
}
