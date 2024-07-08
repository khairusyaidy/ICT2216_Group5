<?php
ob_start();
date_default_timezone_set('Asia/Singapore');

// Include your database configuration file here if not already included
include "dbconntest.php";

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files from the same directory
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Check if the user has the 'staff' role
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] !== 'staff') {
    header("location: unauthorized.php");
    exit;
}

// Check if form is submitted and data is valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    // Sanitize and validate input
    $booking_id = intval($_POST['booking_id']);
    $dropoff_date = $_POST['dropoff_date']; // Example field, adjust as per your form
    $reason = $_POST['reason']; // Reason for changing the date

    // Set pickup date same as dropoff date
    $pickup_date = $dropoff_date;

    // Update booking in database
    $sql = "UPDATE booking SET DropOffDate = ?, PickUpDate = ?, Reason = ?, Status = 'Updated' WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $dropoff_date, $pickup_date, $reason, $booking_id);

    if ($stmt->execute()) {
        // Booking updated successfully
        // Example of sending email notification (using PHPMailer)
        $sql = "SELECT c.Email, c.FirstName, c.LastName FROM booking b INNER JOIN customer c ON b.CustomerID = c.ID WHERE b.ID = ?";
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
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'ictssdproj@gmail.com'; // SMTP username
                $mail->Password = 'prlc xrsj tdib furv'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('ictssdproj@gmail.com', 'Fur Hotel');
                $mail->addAddress($customer_email, $customer_name);

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Booking Update Notification';
                $mail->Body = "Dear $customer_name,\n\nYour booking details have been updated.\n\nNew Drop-Off Date: $dropoff_date\nReason: $reason\n\nPlease check your account for more information.";

                $mail->send();
                echo '<div class="alert alert-success">Booking updated successfully. Email notification sent.</div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Booking updated successfully. Failed to send email notification. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Customer email not found. Email notification not sent.</div>';
        }

        // Redirect to sgroomingdetail.php after successful update
        header('Location: sgroomingdetail.php');
        exit();
    } else {
        echo '<div class="alert alert-danger">Failed to update booking.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "head.inc.php"; ?>
    <style>
        .form-control-small {
            width: 50%; /* Adjust as needed for the smaller size */
        }
    </style>
    <script src="js/inactivity.js"></script>
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
                <div class="col">
                    <h1>Edit Grooming Booking</h1>
                </div>
            </div>

            <?php
            // Retrieve booking details from database if editing
            if (isset($_GET['id'])) {
                // Sanitize and validate input
                $booking_id = intval($_GET['id']); // Assuming integer ID
                // Retrieve booking details from database
                $sql = "SELECT * FROM booking WHERE ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $booking_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Display edit form
                    echo '<form action="sgroomingedit.php" method="post">';
                    echo '<input type="hidden" name="booking_id" value="' . htmlspecialchars($row['ID']) . '">';

                    // Display fields for editing (e.g., drop-off date, remarks)
                    // Example: Drop-Off Date
                    echo '<div class="form-group">';
                    echo '<label for="dropoff_date">Drop-Off Date:</label>';
                    echo '<input type="date" class="form-control form-control-small" id="dropoff_date" name="dropoff_date" value="' . htmlspecialchars($row['DropOffDate']) . '" required>';
                    echo '</div>';

                    // Add reason for changing the drop-off date
                    echo '<div class="form-group">';
                    echo '<label for="reason">Reason:</label>';
                    echo '<input type="text" class="form-control" id="reason" name="reason" placeholder="Enter reason for changing the date" required>';
                    echo '</div>';

                    // Continue with other fields as needed (e.g., food, remarks)

                    echo '<button type="submit" class="btn btn-primary">Update Booking</button>';
                    echo '<a href="sgroomingdetail.php" class="btn btn-secondary ml-2">Cancel</a>';
                    echo '</form>';
                } else {
                    echo '<div class="alert alert-warning">Booking not found.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Booking ID not provided.</div>';
            }

            $conn->close();
            ?>
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
