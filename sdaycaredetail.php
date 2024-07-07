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
                    <h1>Daycare Bookings Details</h1>
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

            // Set timezone to Singapore
            date_default_timezone_set('Asia/Singapore');
            
            // Get today's date
            $today = date('Y-m-d');

            // Query to fetch daycare bookings for today
            $sqlToday = "SELECT b.ID, b.DropOffDate, b.PickUpDate, b.Food, b.Remarks, b.TotalPrice, b.Paid, b.Status, b.Reason, p.Name AS PetName, c.FirstName, c.LastName
                FROM booking b
                JOIN pet p ON b.PetID = p.ID
                JOIN customer c ON b.CustomerID = c.ID
                WHERE b.ServiceID = 2
                AND DATE(b.DropOffDate) = '$today'";

            $resultToday = $conn->query($sqlToday);

            if ($resultToday->num_rows > 0) {
                echo '<div class="row">';
                echo '<div class="col text-center mb-4">';
                echo '<h2>Today\'s Bookings</h2>';
                echo '</div>';
                echo '</div>';

                while ($row = $resultToday->fetch_assoc()) {
                    echo '<div class="row mb-4">';
                    echo '<div class="col-lg-6 offset-lg-3">';
                    echo '<div class="card shadow">';
                    echo '<div class="card-header py-3">';
                    echo '<h6 class="m-0 font-weight-bold text-primary">' . htmlspecialchars($row['PetName']) . '</h6>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Owner:</strong> ' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</p>';
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
                        echo '<a href="sdaycareedit.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary mr-2">Edit Booking</a>';
                        echo '</div>';

                        // Add a reject button that redirects to sdaycarereject.php with the booking ID
                        echo '<div class="mb-3">';
                        echo '<a href="sdaycarereject.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-danger">Reject Booking</a>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-danger">Booking Rejected</p>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="row">';
                echo '<div class="col">';
                echo '<div class="alert alert-info">No daycare bookings found for today.</div>';
                echo '</div>';
                echo '</div>';
            }

            // Query to fetch upcoming daycare bookings
            $sqlUpcoming = "SELECT b.ID, b.DropOffDate, b.PickUpDate, b.Food, b.Remarks, b.TotalPrice, b.Paid, b.Status, b.Reason, p.Name AS PetName, c.FirstName, c.LastName
                FROM booking b
                JOIN pet p ON b.PetID = p.ID
                JOIN customer c ON b.CustomerID = c.ID
                WHERE b.ServiceID = 2
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
                    echo '<div class="row mb-4">';
                    echo '<div class="col-lg-6 offset-lg-3">';
                    echo '<div class="card shadow">';
                    echo '<div class="card-header py-3">';
                    echo '<h6 class="m-0 font-weight-bold text-primary">' . htmlspecialchars($row['PetName']) . '</h6>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Owner:</strong> ' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</p>';
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
                        echo '<a href="sdaycareedit.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary mr-2">Edit Booking</a>';
                        echo '</div>';

                        // Add a reject button that redirects to sdaycarereject.php with the booking ID
                        echo '<div class="mb-3">';
                        echo '<a href="sdaycarereject.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-danger">Reject Booking</a>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-danger">Booking Rejected</p>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="row">';
                echo '<div class="col">';
                echo '<div class="alert alert-info">No upcoming daycare bookings found.</div>';
                echo '</div>';
                echo '</div>';
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

</body>
</html>
