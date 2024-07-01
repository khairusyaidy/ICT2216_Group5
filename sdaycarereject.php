<?php
// Include your database configuration file here if not already included
include "db_connect.php";

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\PHPMailer-master\src\Exception.php';
require 'C:\xampp\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\PHPMailer-master\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['reason'])) {
    // Sanitize and validate input
    $booking_id = intval($_POST['booking_id']);
    $reason = htmlspecialchars($_POST['reason']);

    // Update booking status and reason in the database
    $status = "Rejected";
    $sql = "UPDATE Booking SET Status = ?, Reason = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $reason, $booking_id);

    if ($stmt->execute()) {
        // Booking rejected successfully
        // Send email notification (using PHPMailer)
        $sql = "SELECT c.Email, c.FirstName, c.LastName FROM Booking b INNER JOIN Customer c ON b.CustomerID = c.ID WHERE b.ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_email = $row['Email'];
            $customer_name = $row['FirstName'] . ' ' . $row['LastName'];

            // Send email notification
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ictssdproj@gmail.com'; // SMTP username
                $mail->Password   = 'prlc xrsj tdib furv'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('ictssdproj@gmail.com', 'Fur Hotel');
                $mail->addAddress($customer_email, $customer_name);

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Booking Rejection Notification';
                $mail->Body    = "Dear $customer_name,\n\nYour daycare booking has been rejected for the following reason:\n\n$reason\n\nPlease contact us for further assistance.";

                $mail->send();
                echo '<div class="alert alert-success">Daycare booking rejected successfully. Email notification sent.</div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Daycare booking rejected successfully. Failed to send email notification. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Customer email not found. Email notification not sent.</div>';
        }

        // Redirect back to the daycare details page after rejection
        header('Location: sdaycaredetail.php');
        exit();
    } else {
        echo '<div class="alert alert-danger">Failed to reject daycare booking.</div>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request.</div>';
}

$conn->close();
?>
