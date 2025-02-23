

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $message = htmlspecialchars($_POST["message"]);
    
    // File upload handling
    $uploadDir = 'uploads/'; // Ensure this folder exists and is writable
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = "";
    if (isset($_FILES["payment_proof"]) && $_FILES["payment_proof"]["error"] == 0) {
        $fileName = basename($_FILES["payment_proof"]["name"]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $filePath)) {
            $fileUploaded = true;
        } else {
            $fileUploaded = false;
        }
    } else {
        $fileUploaded = false;
    }

    $mail = new PHPMailer(true);
    
    try {
        // SMTP Configuration for Localhost & Hostinger Mail
       // SMTP Configuration
       $mail->isSMTP();
       $mail->Host = 'smtp.hostinger.com'; // Replace with your SMTP host
       $mail->SMTPAuth = true;
       $mail->Username = 'Gorakhpur@worldvivah.com'; // Replace with your email
       $mail->Password = 'Worldvivah@12'; // Replace with your app password (not Gmail password)
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
       $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom('Gorakhpur@worldvivah.com', 'Your Name');
        $mail->addAddress('anurudhraj06@gmail.com'); // Receiving email

        // Attach file if uploaded
        if ($fileUploaded) {
            $mail->addAttachment($filePath, $fileName);
        }

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission with Payment Proof";
        $mail->Body = "<strong>Name:</strong> $name <br>
                       <strong>Message:</strong> $message <br>
                       <strong>Payment Proof:</strong> " . ($fileUploaded ? "Attached" : "Not Provided");

        if ($mail->send()) {
            echo "Message sent successfully!";
        } else {
            echo "Message could not be sent.";
        }
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>
