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
            $email = $row['Email'];
            $name = $row['FirstName'] . ' ' . $row['LastName'];

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@example.com';
                $mail->Password = 'your_email_password';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('your_email@example.com', 'Your Company');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Grooming Booking Rejected';
                $mail->Body = 'Dear ' . $name . ',<br>Your grooming booking has been rejected. Reason: ' . $reason . '.<br>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        // Redirect to sgroomingdetail.php with a success message
        header("Location: sgroomingdetail.php?success=Booking rejected successfully");
        exit();
    } else {
        // Handle error
        $error_message = "Error rejecting booking: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Check if booking ID is set in the query string
if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
} else {
    // Redirect back to sgroomingdetail.php if no booking ID is provided
    header("Location: sgroomingdetail.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "head.inc.php"; ?>
</head>
<body>
    <!-- Topbar Start -->
    <?php include "topbar.inc.php"; ?>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <?php include "adminnav.php"; ?>
    <!-- Navbar End -->

    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-3">
            <div class="row">
                <div class="col text-center mb-4">
                    <h1>Reject Grooming Booking</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <?php
                    if (isset($error_message)) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
                    }
                    ?>
                    <form action="sgroomingreject.php" method="post">
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                        <div class="form-group">
                            <label for="reason">Reason for Rejection</label>
                            <textarea id="reason" name="reason" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Reject Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    <?php include "footer.inc.php"; ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/select2/js/select2.full.min.js"></script>
    <script src="lib/sweetalert/sweetalert.min.js"></script>
    <script src="lib/jquery-steps/jquery.steps.min.js"></script>
    <script src="lib/parsleyjs/parsley.min.js"></script>
    <script src="lib/Chart.js/Chart.min.js"></script>
    <script src="js/main.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Custom JavaScript can be added here
    </script>
</body>
</html>
