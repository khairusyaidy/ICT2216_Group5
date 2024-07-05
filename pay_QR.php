<?php
session_start();
require_once 'dbconntest.php';

// Initialize variables to store booking ID and total price
$booking_id = null;
$total_price = null;

// Check if the booking ID and total price are available in the session
if (isset($_SESSION['booking_id']) && isset($_SESSION['total_price'])) {
    $booking_id = $_SESSION['booking_id'];
    $total_price = $_SESSION['total_price'];

    // If the "Done" button is clicked
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['done'])) {
        // Update the Paid status to 1 for this booking ID
        $update_sql = "UPDATE booking SET Paid = 1, Status = 'Accepted' WHERE ID = '$booking_id'";

        if ($conn->query($update_sql) === TRUE) {
            // Redirect to booking summary page
            header('Location: booking_summary.php');
        } else {
            echo "Error updating payment: " . $conn->error;
        }
    }
} else {
    echo "Booking ID or Total Price not found in session.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <?php
        include "head.inc.php";
        ?>
        <link rel="stylesheet" href="css/qr.css">
    </head>

    <body>
        <!-- Topbar Start -->
        <?php
        include "topbar.inc.php";
        ?>
        <!-- Topbar End -->


        <!-- Navbar Start -->
        <?php
        include "nav.inc.php";
        ?>
        <!-- Navbar End -->

        <div id="qr_container">
            <p><b>Total payable amount:</b> $<?php echo $total_price; ?></p><br>

            <img src="img/qrcode.png" alt="qrcode">

            <div id="button_container">
                <form method="post">
                    <input type="submit" id="done_btn" name="done" value="Done" class="btn btn-lg btn-primary mt-3 mt-md-4 px-4">
                </form>
            </div>
        </div>

        <!-- Footer Start -->
        <?php
        include "footer.inc.php";
        ?>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>