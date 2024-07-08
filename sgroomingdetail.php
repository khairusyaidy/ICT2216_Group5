<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.inc.php"; ?>
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
                    <div class="col text-center mb-4">
                        <h1>Grooming Bookings Details</h1>
                    </div>
                </div>

                <?php
                // Include your database configuration file here if not already included
                include "dbconntest.php";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Check if the user has the 'staff' role
                if (!isset($_SESSION["Role"]) || $_SESSION["Role"] !== 'staff') {
                    header("location: unauthorized.php");
                    exit;
                }

                // Set timezone to Singapore
                date_default_timezone_set('Asia/Singapore');

                // Get today's date
                $today = date('Y-m-d');

                // Query to fetch grooming bookings for today
                $sqlToday = "SELECT b.ID, b.DropOffDate, b.PickUpDate, b.Food, b.Remarks, b.TotalPrice, b.Paid, b.Status, b.Reason, s.ServiceName, p.Name AS PetName
                FROM booking b
                JOIN service s ON b.ServiceID = s.ID
                JOIN pet p ON b.PetID = p.ID
                WHERE s.ID BETWEEN 3 AND 11
                AND DATE(b.DropOffDate) = '$today'";

                $resultToday = $conn->query($sqlToday);

                if ($resultToday->num_rows > 0) {
                    echo '<div class="row">';
                    echo '<div class="col text-center mb-4">';
                    echo '<h2>Today\'s Bookings</h2>';
                    echo '</div>';
                    echo '</div>';

                    while ($row = $resultToday->fetch_assoc()) {
                        displayBookingCard($row);
                    }
                } else {
                    echo '<div class="row">';
                    echo '<div class="col">';
                    echo '<div class="alert alert-info">No grooming bookings found for today.</div>';
                    echo '</div>';
                    echo '</div>';
                }

                // Query to fetch upcoming grooming bookings
                $sqlUpcoming = "SELECT b.ID, b.DropOffDate, b.PickUpDate, b.Food, b.Remarks, b.TotalPrice, b.Paid, b.Status, b.Reason, s.ServiceName, p.Name AS PetName
                FROM booking b
                JOIN service s ON b.ServiceID = s.ID
                JOIN pet p ON b.PetID = p.ID
                WHERE s.ID BETWEEN 3 AND 11
                AND DATE(b.DropOffDate) > '$today'
                ORDER BY b.DropOffDate ASC";

                $resultUpcoming = $conn->query($sqlUpcoming);

                if ($resultUpcoming->num_rows > 0) {
                    echo '<div class="row mt-5">';
                    echo '<div class="col text-center mb-4">';
                    echo '<h2>Upcoming Bookings</h2>';
                    echo '</div>';
                    echo '</div>';

                    while ($row = $resultUpcoming->fetch_assoc()) {
                        displayBookingCard($row);
                    }
                } else {
                    echo '<div class="row">';
                    echo '<div class="col">';
                    echo '<div class="alert alert-info">No upcoming grooming bookings found.</div>';
                    echo '</div>';
                    echo '</div>';
                }

                $conn->close();

                // Function to display booking card
                function displayBookingCard($row) {
                    echo '<div class="row mb-4">';
                    echo '<div class="col-lg-6 offset-lg-3">';
                    echo '<div class="card shadow">';
                    echo '<div class="card-header py-3">';
                    echo '<h6 class="m-0 font-weight-bold text-primary">' . htmlspecialchars($row['PetName']) . '</h6>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Service:</strong> ' . htmlspecialchars($row['ServiceName']) . '</p>';
                    echo '<p><strong>Drop-off Date:</strong> ' . htmlspecialchars($row['DropOffDate']) . '</p>';
                    echo '<p><strong>Pick-up Date:</strong> ' . htmlspecialchars($row['PickUpDate']) . '</p>';
                    echo '<p><strong>Food:</strong> ' . ($row['Food'] ? 'Yes' : 'No') . '</p>';
                    echo '<p><strong>Remarks:</strong> ' . htmlspecialchars($row['Remarks']) . '</p>';
                    echo '<p><strong>Total Price:</strong> $' . htmlspecialchars($row['TotalPrice']) . '</p>';
                    echo '<p><strong>Paid:</strong> ' . ($row['Paid'] ? 'Yes' : 'No') . '</p>';
                    echo '<p><strong>Status:</strong> ' . htmlspecialchars($row['Status']) . '</p>';

                    // Display reason if status is rejected
                    if ($row['Status'] === 'Rejected') {
                        echo '<p><strong>Reason for Rejection:</strong> ' . htmlspecialchars($row['Reason']) . '</p>';
                    }

                    // Display different buttons based on booking status
                    if ($row['Status'] != 'Rejected') {
                        echo '<div class="mb-3">'; // Adding margin bottom
                        echo '<a href="sgroomingedit.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary mr-2">Edit Booking</a>';
                        echo '</div>';

                        // Replace the form for rejecting booking with a redirect button
                        echo '<a href="sgroomingreject.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-danger">Reject Booking</a>';
                    } else {
                        echo '<p class="text-danger">Booking Rejected</p>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
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
