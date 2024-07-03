<?php
// Include your database configuration file here if not already included
include "db_connect.php";

// Include PHPMailer library
require 'C:\xampp\PHPMailer-master\src\Exception.php';
require 'C:\xampp\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP as PHPMailerSMTP;

// Check if booking ID is provided via GET method
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Display form for rejection reason
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>';
    include "head.inc.php"; // Include head section
    echo '</head>
    <body>';
    include "topbar.inc.php"; // Include topbar
    include "adminnav.php"; // Include navbar
    
    echo '<section class="py-5">
        <div class="container px-4 px-lg-5 mt-3">
            <div class="row">
                <div class="col">
                    <h1>Reject Boarding Booking</h1>
                </div>
            </div>';

    // Form for rejection reason
    echo '<form action="sboardingreject.php" method="post">';
    echo '<input type="hidden" name="booking_id" value="' . $booking_id . '">';

    // Reason input
    echo '<div class="form-group">';
    echo '<label for="reason">Reason for rejection:</label>';
    echo '<input type="text" class="form-control" id="reason" name="reason" placeholder="Enter reason for rejecting the booking" required>';
    echo '</div>';

    // Submit button
    echo '<button type="submit" class="btn btn-danger">Reject Booking</button>';
    echo '</form>';

    echo '</div>
    </section>';

    include "footer.inc.php"; // Include footer
    echo '<a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>'; // Back to Top button
    echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>'; // JavaScript Libraries
    echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/easing/easing.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/owlcarousel/owl.carousel.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/tempusdominus/js/moment.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/select2/js/select2.full.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/sweetalert/sweetalert.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/jquery-steps/jquery.steps.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/parsleyjs/parsley.min.js"></script>'; // JavaScript Libraries
    echo '<script src="lib/Chart.js/Chart.min.js"></script>'; // JavaScript Libraries
    echo '<script src="js/main.js"></script>'; // Custom JavaScript
    echo '</body>
    </html>';

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['reason'])) {
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
                $mail->Body    = "Dear $customer_name,\n\nYour boarding booking has been rejected for the following reason:\n\n$reason\n\nPlease contact us for further assistance.";

                $mail->send();
                echo '<div class="alert alert-success">Booking rejected successfully. Email notification sent.</div>';
            } catch (PHPMailerException $e) {
                echo '<div class="alert alert-danger">Booking rejected successfully. Failed to send email notification. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Customer email not found. Email notification not sent.</div>';
        }

        // Redirect back to the boarding details page after rejection
        header('Location: sboardingdetail.php');
        exit();
    } else {
        echo '<div class="alert alert-danger">Failed to reject booking.</div>';
    }

} else {
    // Redirect back to sboardingdetail.php if booking ID is not provided or invalid request
    header('Location: sboardingdetail.php');
    exit();
}

$conn->close();
?>
