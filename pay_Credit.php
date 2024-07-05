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
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
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
        <link rel="stylesheet" href="css/credit.css">
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

        <div id="form_container">
            <form method="post">
                <b>Cardholder Name:</b>
                <input type="text" name="cardholder_name" style="width: 300px; height: 25px;" required>

                <br>

                <b>Card Number:</b>
                <input type="text" maxlength="19" id="number-input" style="width: 300px; height: 25px;" required>
                <span id="numErrorMessage" class="error-message">Please enter exactly 16 digits.</span>

                <br>

                <b>Card Expiration:</b>
                <input type="text" maxlength="5" id="date-input" placeholder="MM/YY" style="width: 80px; height: 25px;" required>
                <span id="expErrorMessage" class="error-message">Please enter a valid date in MM/YY format.</span>

                <br>

                <b>CVC:</b>
                <input type="number" maxlength="3" id="cvc_input" style="width: 80px; height: 25px;" required>
                <span id="cvcErrorMessage" class="error-message">Please enter exactly 3 digits.</span>

                <br><br>

                <p><b>Total payable amount:</b> $<?php echo $total_price; ?></p><br>

                <br>

                <input type="submit" id="credit_btn" name="pay" value="Pay" class="btn btn-lg btn-primary mt-3 mt-md-4 px-4">

            </form>
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

        <script>
            //for card number input
            const input = document.getElementById('number-input');
            const numErrorMessage = document.getElementById('numErrorMessage');

            input.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
                if (value.length > 16) {
                    value = value.slice(0, 16); // Limit to 16 digits
                }
                e.target.value = value.replace(/(\d{4})(?=\d)/g, '$1 '); // Add space after every 4 digits
            });

            input.addEventListener('blur', function () {
                const value = input.value.replace(/\s/g, ''); // Remove spaces
                if (value.length !== 16) {
                    numErrorMessage.style.display = 'block'; // Show error message
                } else {
                    numErrorMessage.style.display = 'none'; // Hide error message
                }
            });

            //for date input
            const dateInput = document.getElementById('date-input');
            const expErrorMessage = document.getElementById('expErrorMessage');

            dateInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-digit characters
                if (value.length >= 3) {
                    value = value.slice(0, 2) + '/' + value.slice(2, 4); // Insert '/' after MM
                }
                e.target.value = value;
            });

            dateInput.addEventListener('blur', function () {
                const value = dateInput.value;
                const parts = value.split('/');
                const month = parseInt(parts[0], 10);
                const day = parseInt(parts[1], 10);

                if (parts.length !== 2 || isNaN(month) || isNaN(day) || month < 1 || month > 12 || day < 1 || day > 31) {
                    expErrorMessage.style.display = 'block'; // Show error message
                } else {
                    expErrorMessage.style.display = 'none'; // Hide error message
                }
            });

            //for CVC input
            const cvcInput = document.getElementById('cvc_input');
            const cvcErrorMessage = document.getElementById('cvcErrorMessage');

            cvcInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
                if (value.length > 3) {
                    value = value.slice(0, 3); // Limit to 3 digits
                }
                e.target.value = value;
            });

            cvcInput.addEventListener('blur', function () {
                const value = cvcInput.value;
                if (value.length !== 3) {
                    cvcErrorMessage.style.display = 'block'; // Show error message
                } else {
                    cvcErrorMessage.style.display = 'none'; // Hide error message
                }
            });
        </script>
    </body>

</html>