<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'vendor/autoload.php'; // If using Composer
// require 'src/PHPMailer.php'; // If manually downloaded
// require 'src/SMTP.php'; // If manually downloaded
// require 'src/Exception.php'; // If manually downloaded

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["fname"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.goneo.de'; // Use your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'info@drinkrack.de'; // Your Gmail
        $mail->Password = 'your_app_password'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 465;

        // Email Settings
        $mail->setFrom($email, $name);
        $mail->addAddress('info@drinkrack.de'); // Recipient Email
        $mail->addReplyTo($email, $name);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Message from $name";
        $mail->Body = "<p><strong>Name:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Message:</strong><br>$message</p>";

        $mail->send();
        echo "Message sent successfully!";
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>