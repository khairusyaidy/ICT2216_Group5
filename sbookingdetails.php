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
                <div class="col">
                    <h1>Booking Details</h1>
                </div>
            </div>

            <?php
            // Include your database configuration file here if not already included
            include "db_connect.php";

            // Check if booking ID is provided
            if (!isset($_GET['id'])) {
                echo '<div class="alert alert-danger">Booking ID not provided.</div>';
                exit;
            }

            // Sanitize and validate input
            $booking_id = intval($_GET['id']); // Assuming integer ID

            // Retrieve booking details from database
            $sql = "SELECT b.*, s.ServiceName, p.Name AS PetName, c.FirstName, c.LastName, c.Email
                    FROM Booking b
                    INNER JOIN Service s ON b.ServiceID = s.ID
                    INNER JOIN Pet p ON b.PetID = p.ID
                    INNER JOIN Customer c ON b.CustomerID = c.ID
                    WHERE b.ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Display booking details
                echo '<div class="card">';
                echo '<div class="card-header">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['PetName']) . '</h5>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<p class="card-text">Service: ' . htmlspecialchars($row['ServiceName']) . '</p>';
                echo '<p class="card-text">Drop-Off Date: ' . htmlspecialchars($row['DropOffDate']) . '</p>';
                echo '<p class="card-text">Pick-Up Date: ' . htmlspecialchars($row['PickUpDate']) . '</p>';
                echo '<p class="card-text">Remarks: ' . htmlspecialchars($row['Remarks']) . '</p>';
                echo '<p class="card-text">Total Price: ' . htmlspecialchars($row['TotalPrice']) . '</p>';
                echo '<p class="card-text">Paid: ' . ($row['Paid'] ? 'Yes' : 'No') . '</p>';
                echo '<p class="card-text">Customer: ' . htmlspecialchars($row['FirstName']) . ' ' . htmlspecialchars($row['LastName']) . '</p>';
                echo '<p class="card-text">Email: ' . htmlspecialchars($row['Email']) . '</p>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning">Booking not found.</div>';
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
