<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Retrieve CustomerID from session
if (!isset($_SESSION['id'])) {
    die("Customer ID not found in session.");
}
$customerID = $_SESSION['id'];

// Fetch bookings for the specific user
$sql = "SELECT b.ID, b.DropOffDate, b.PickUpDate, b.Food, b.Remarks, b.TotalPrice, s.ServiceName, p.Name AS PetName, p.Weight AS PetWeight
        FROM booking b
        JOIN service s ON b.ServiceID = s.ID
        JOIN pet p ON b.PetID = p.ID
        WHERE b.CustomerID = '$customerID'";

$result = $conn->query($sql);

$bookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    echo "No bookings found.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.inc.php"; ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1R4Kmve1gKv9VObNUz3cvhPOeVr5J4kNIPN7A0JQquz7m0Y4rJ/9iiWE6Us52frG4Vap5lKfYiz/+O5Bl1bMOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <!-- Topbar Start -->
        <?php include "topbar.inc.php"; ?>
        <!-- Topbar End -->

        <!-- Navbar Start -->
        <?php include "nav.inc.php"; ?>
        <!-- Navbar End -->


        <div class="container mt-5">
            <h2>My Bookings</h2>
            <div class="row">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($booking['ServiceName']); ?></h5>
                                    <p class="card-text"><b>Pet Name:</b> <?php echo htmlspecialchars($booking['PetName']); ?></p>
                                    <p class="card-text"><b>Pet Weight:</b> <?php echo htmlspecialchars($booking['PetWeight']); ?> kg</p>
                                    <p class="card-text"><b>Drop-off Date:</b> <?php echo htmlspecialchars($booking['DropOffDate']); ?></p>
                                    <p class="card-text"><b>Pick-up Date:</b> <?php echo htmlspecialchars($booking['PickUpDate']); ?></p>
                                    <p class="card-text"><b>Food:</b> <?php echo $booking['Food'] ? 'Yes' : 'No'; ?></p>
                                    <p class="card-text"><b>Remarks:</b> <?php echo htmlspecialchars($booking['Remarks']); ?></p>
                                    <p class="card-text"><b>Total Price:</b> $<?php echo htmlspecialchars($booking['TotalPrice']); ?></p>

                                    <div class="d-flex justify-content-end">
                                        <a href="edit_booking.php?booking_id=<?php echo $booking['ID']; ?>" class="mr-2"><i class="fas fa-edit"></i></a>
                                        <a href="delete_booking.php?booking_id=<?php echo $booking['ID']; ?>"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No bookings found.</p>
                <?php endif; ?>
            </div>
        </div>

        <br>

        <div class="col text-center mb-4">
            <button onclick="window.location.href = 'addreview.php'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Add Review</button>
        </div>



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
