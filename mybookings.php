<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
// Ensure session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'db_connect.php';

// Retrieve CustomerID from session
    if (!isset($_SESSION['id'])) {
        die("Customer ID not found in session.");
    }
    $customerID = $_SESSION['id'];

// Check if booking ID is provided
    if (isset($_POST['booking_id'])) {
        $bookingID = $_POST['booking_id'];

// Fetch booking information to check if it belongs to the current customer (for security)
        $stmt = $conn->prepare("SELECT CustomerID, DropOffDate FROM booking WHERE ID = ?");
        $stmt->bind_param("i", $bookingID);
        $stmt->execute();
        $stmt->bind_result($bookingCustomerID, $dropOffDate);
        $stmt->fetch();
        $stmt->close();

// Check if the booking exists and belongs to the current customer
        if ($bookingCustomerID == $customerID) {
// Check if it's allowed to delete (if drop-off date is at least 2 days from today)
            $today = date('Y-m-d');
            $dropOff = new DateTime($dropOffDate);
            $todayObj = new DateTime($today);
            $interval = $todayObj->diff($dropOff);
            $daysDifference = $interval->format('%a');

            if ($daysDifference < 2) {
                echo json_encode(["success" => false, "message" => "Not allowed to cancel booking less than 2 days from drop-off date."]);
                exit();
            }

// Proceed with deletion
            $reason = isset($_POST['reason']) ? $_POST['reason'] : '';

            if (empty($reason)) {
                echo json_encode(["success" => false, "message" => "Reason is required for cancellation."]);
                exit();
            }

            $stmt = $conn->prepare("UPDATE booking SET Status = 'Cancelled', Reason = ? WHERE ID = ?");
            $stmt->bind_param("si", $reason, $bookingID);

            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete booking."]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Unauthorized action. This booking does not belong to you."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No booking ID provided."]);
    }

    $conn->close();
    exit(); // Terminate script execution after handling AJAX request
}

//For fetching bookings
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
        WHERE b.CustomerID = '$customerID' AND b.Status != 'Cancelled'";

$result = $conn->query($sql);

$currentBookings = [];
$historyBookings = [];
$today = date('Y-m-d');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['DropOffDate'] >= $today) {
            $currentBookings[] = $row;
        } else {
            $historyBookings[] = $row;
        }
    }
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
        <!--Topbar Start-->
        <?php include "topbar.inc.php";
        ?>
        <!-- Topbar End -->

        <!-- Navbar Start -->
        <?php include "nav.inc.php"; ?>
        <!-- Navbar End -->


        <div class="container mt-5">

            <h3>Current Bookings</h3>
            <div class="row">
                <?php if (!empty($currentBookings)): ?>
                    <?php foreach ($currentBookings as $booking): ?>
                        <div class="col-md-4" id="booking-card-<?php echo $booking['ID']; ?>">
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
                                        <a href="#" class="mr-2" onclick="checkEditDateAndProceed('<?php echo $booking['DropOffDate']; ?>', 'edit_booking.php?booking_id=<?php echo $booking['ID']; ?>')"><i class="fas fa-edit"></i></a>
                                        <a href="#" onclick="checkDeleteDateAndProceed('<?php echo $booking['DropOffDate']; ?>', '<?php echo $booking['ID']; ?>')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No current bookings found.</p>
                <?php endif; ?>
            </div>

            <h3>Booking History</h3>
            <div class="row">
                <?php if (!empty($historyBookings)): ?>
                    <?php foreach ($historyBookings as $booking): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($booking['ServiceName']); ?></h5>
                                    <p class="card-text"><b>Pet Name:</b> <?php echo htmlspecialchars($booking['PetName']); ?></p>
                                    <p class="card-text"><b>Pet Weight:</b> <?php echo htmlspecialchars($booking['PetWeight']); ?></p>
                                    <p class="card-text"><b>Drop-off Date:</b> <?php echo htmlspecialchars($booking['DropOffDate']); ?></p>
                                    <p class="card-text"><b>Pick-up Date:</b> <?php echo htmlspecialchars($booking['PickUpDate']); ?></p>
                                    <p class="card-text"><b>Food:</b> <?php echo $booking['Food'] ? 'Yes' : 'No'; ?></p>
                                    <p class="card-text"><b>Remarks:</b> <?php echo htmlspecialchars($booking['Remarks']); ?></p>
                                    <p class="card-text"><b>Total Price:</b> $<?php echo htmlspecialchars($booking['TotalPrice']); ?></p>

                                    <?php if (!isset($booking['ReviewID']) || $booking['ReviewID'] === null): ?>
                                        <div class="col text-center mb-4">
                                            <button onclick="window.location.href = 'addreview.php?booking_id=<?php echo $booking['ID']; ?>'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Add Review</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No booking history found.</p>
                <?php endif; ?>
            </div>
        </div>

        <br>


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
                                        function checkEditDateAndProceed(dropOffDate, url) {
                                            const today = new Date();
                                            const dropOff = new Date(dropOffDate);
                                            const timeDifference = dropOff.getTime() - today.getTime();
                                            const dayDifference = timeDifference / (1000 * 3600 * 24);

                                            if (dayDifference < 2) {
                                                alert("Not allowed to modify booking.");
                                            } else {
                                                window.location.href = url;
                                            }
                                        }

                                        function checkDeleteDateAndProceed(dropOffDate, bookingId) {
                                            const today = new Date();
                                            const dropOff = new Date(dropOffDate);
                                            const timeDifference = dropOff.getTime() - today.getTime();
                                            const dayDifference = timeDifference / (1000 * 3600 * 24);

                                            if (dayDifference < 2) {
                                                alert("Not allowed to cancel booking less than 2 days from drop-off date.");
                                            } else {
                                                if (confirm("Are you sure you want to delete this booking?")) {
                                                    const reason = prompt("Please enter the reason for cancellation:");
                                                    if (reason !== null && reason.trim() !== "") {
                                                        console.log("Initiating AJAX request to cancel booking with ID:", bookingId);
                                                        // AJAX request to cancel the booking
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "mybookings.php", // Adjust URL if needed
                                                            data: {
                                                                action: 'cancel',
                                                                booking_id: bookingId,
                                                                reason: reason
                                                            },
                                                            dataType: "json",
                                                            success: function (response) {
                                                                if (response.success) {
                                                                    // Remove the booking card from the UI immediately
                                                                    $('#booking-card-' + bookingId).remove();
                                                                    console.log("Booking card removed successfully.");
                                                                    location.reload();
                                                                } else {
                                                                    console.error("Failed to cancel booking:", response.message);
                                                                    alert(response.message || "Failed to cancel booking.");
                                                                }
                                                            },
                                                            error: function (xhr, status, error) {
                                                                console.error("Failed to cancel booking. Server returned status:", status);
                                                                alert("Failed to cancel booking. Please try again.");
                                                            }
                                                        });
                                                    } else {
                                                        console.error("Reason is required for cancellation.");
                                                        alert("Reason is required for cancellation.");
                                                    }
                                                }
                                            }
                                        }
        </script>
    </body>
</html>
