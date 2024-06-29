<?php
session_start();
require_once 'db_connect.php';

$booking_id = $_SESSION['booking_id'];

// Retrieve booking details from database
$booking_query = "SELECT * FROM booking WHERE ID = '$booking_id'";
$result = $conn->query($booking_query);

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    $food = ($booking['Food'] === '1') ? 'Yes' : 'No';
    $paid = ($booking['Paid'] === '1') ? 'Yes' : 'No';

    //to get ServiceName
    $service_query = "SELECT ServiceName FROM service WHERE ID = {$booking['ServiceID']}";
    $service_query_result = $conn->query($service_query); // Execute the query
    if ($service_query_result) {
        $service_row = $service_query_result->fetch_assoc();
        if ($service_row) {
            $serviceName = $service_row['ServiceName'];
        }
    }

    //to get Customer Name
    $customer_query = "SELECT FirstName, LastName FROM customer WHERE ID = {$booking['CustomerID']}";
    $customer_query_result = $conn->query($customer_query);
    if ($customer_query_result) {
        $customer_row = $customer_query_result->fetch_assoc();
        if ($customer_row) {
            $customerName = $customer_row['FirstName'] . ' ' . $customer_row['LastName'];
        }
    }

    //to get Pet Name and Weight
    $pet_details_query = "SELECT Name, Weight FROM pet WHERE ID = {$booking['PetID']}";
    $pet_details_query_result = $conn->query($pet_details_query);
    if ($pet_details_query_result) {
        $pet_row = $pet_details_query_result->fetch_assoc();
        if ($pet_row) {
            $petName = $pet_row['Name'];
            $petWeight = $pet_row['Weight'];
        }
    }
} else {
    echo "Booking not found";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <?php
        include "head.inc.php";
        ?>
        <link rel="stylesheet" href="css/summary.css">
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


        <div id="booking_successful_container">
            <h1>Your booking is successful!</h1>

            <div class="card">
                <h3>Booking summary</h3>

                <p>Drop-off Date: <?php echo $booking['DropOffDate'] ?></p>
                <p>Pick-off Date: <?php echo $booking['PickUpDate'] ?></p>
                <p>Food: <?php echo $food ?></p>
                <p>Remarks: <?php echo $booking['Remarks'] ?></p>
                <p>Total Price: $<?php echo $booking['TotalPrice'] ?></p>
                <p>Paid: <?php echo $paid ?></p>
                <p>Service: <?php echo $serviceName ?></p>
                <p>Customer Name: <?php echo $customerName ?></p>
                <p>Pet Name: <?php echo $petName ?></p>
                <p>Pet Weight: <?php echo $petWeight ?></p>

            </div>
            <div id="book_summary_btn">
                <input type="submit" id="home_btn" name="home" value="home" class="btn btn-lg btn-primary mt-3 mt-md-4 px-4">
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

    <script>
        document.getElementById('home_btn').addEventListener('click', function (event) {
            window.location.href = 'index.php';
        });
    </script>


</html>