<?php
ob_start();
session_start();
include "dbconntest.php"; // Ensure this file correctly establishes $conn
date_default_timezone_set('Asia/Singapore');

// Check for POST request to handle booking rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['reject_reason'])) {
    // Sanitize and validate input
    $booking_id = intval($_POST['booking_id']);
    $reason = htmlspecialchars($_POST['reject_reason']);

    // Update booking status and reason in the database
    $status = "Rejected";
    $sql = "UPDATE booking SET Status = ?, Reason = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssi", $status, $reason, $booking_id);

        if ($stmt->execute()) {
            // Booking rejected successfully
            echo '<div class="alert alert-success">Booking rejected successfully.</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to reject booking.</div>';
        }

        $stmt->close();
    } else {
        echo '<div class="alert alert-danger">Failed to prepare the statement.</div>';
    }
}
?>

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

        <style>
            .package-title {
                text-align: center;
            }
        </style>

        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-3">
                <div class="row">
                    <div class="col text-center mb-4">
                        <h1>Booking Management</h1>
                    </div>
                </div>

                <main>
                    <div class="container-fluid">
                        <!-- Upcoming Bookings -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Upcoming Bookings</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="upcomingTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer Name</th>
                                                <th>Drop-Off Date</th>
                                                <th>Pick-Up Date</th>
                                                <th>Service Name</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $today = date('Y-m-d');
                                            $sql_upcoming = "SELECT b.ID, b.DropOffDate, b.PickUpDate, s.ServiceName, s.Price, b.Status, b.ServiceID, c.FirstName, c.LastName
                                                         FROM booking b
                                                         INNER JOIN service s ON b.ServiceID = s.ID
                                                         INNER JOIN customer c ON b.CustomerID = c.ID
                                                         WHERE b.DropOffDate >= ?
                                                         ORDER BY b.DropOffDate ASC";
                                            $stmt_upcoming = $conn->prepare($sql_upcoming);
                                            $stmt_upcoming->bind_param("s", $today);
                                            $stmt_upcoming->execute();
                                            $result_upcoming = $stmt_upcoming->get_result();

                                            if ($result_upcoming->num_rows > 0) {
                                                while ($row = $result_upcoming->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</td>';
                                                    echo '<td>' . htmlspecialchars(date('Y-m-d', strtotime($row['DropOffDate']))) . '</td>';
                                                    echo '<td>' . htmlspecialchars(date('Y-m-d', strtotime($row['PickUpDate']))) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['ServiceName']) . '</td>';
                                                    echo '<td>$' . htmlspecialchars($row['Price']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['Status']) . '</td>';
                                                    echo '<td>';

                                                    // Determine edit and reject links based on ServiceID
                                                    $editPage = '';
                                                    $rejectPage = '';
                                                    if ($row['ServiceID'] == 1) {
                                                        $editPage = 'sboardingedit.php';
                                                        $rejectPage = 'sboardingreject.php';
                                                    } elseif ($row['ServiceID'] == 2) {
                                                        $editPage = 'sdaycareedit.php';
                                                        $rejectPage = 'sdaycarereject.php';
                                                    } elseif ($row['ServiceID'] >= 3 && $row['ServiceID'] <= 11) {
                                                        $editPage = 'sgroomingedit.php';
                                                        $rejectPage = 'sgroomingreject.php';
                                                    }

                                                    if ($editPage && $rejectPage) {
                                                        echo '<a href="' . $editPage . '?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary mr-2">Edit</a>';
                                                        echo '<a href="' . $rejectPage . '?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-danger">Reject</a>';
                                                    } else {
                                                        echo 'N/A';
                                                    }

                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="8">No upcoming bookings found.</td></tr>';
                                            }

                                            $stmt_upcoming->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Past Bookings -->
                        <div class="card shadow mb-4 mt-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Past Bookings</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="pastTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer Name</th>
                                                <th>Drop-Off Date</th>
                                                <th>Pick-Up Date</th>
                                                <th>Service Name</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql_past = "SELECT b.ID, b.DropOffDate, b.PickUpDate, s.ServiceName, s.Price, b.Status, c.FirstName, c.LastName
                                                     FROM booking b
                                                     INNER JOIN service s ON b.ServiceID = s.ID
                                                     INNER JOIN customer c ON b.CustomerID = c.ID
                                                     WHERE b.DropOffDate < ?
                                                     ORDER BY b.DropOffDate DESC";
                                            $stmt_past = $conn->prepare($sql_past);
                                            $stmt_past->bind_param("s", $today);
                                            $stmt_past->execute();
                                            $result_past = $stmt_past->get_result();

                                            if ($result_past->num_rows > 0) {
                                                while ($row = $result_past->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</td>';
                                                    echo '<td>' . htmlspecialchars(date('Y-m-d', strtotime($row['DropOffDate']))) . '</td>';
                                                    echo '<td>' . htmlspecialchars(date('Y-m-d', strtotime($row['PickUpDate']))) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['ServiceName']) . '</td>';
                                                    echo '<td>$' . htmlspecialchars($row['Price']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($row['Status']) . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="7">No past bookings found.</td></tr>';
                                            }

                                            $stmt_past->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
   </section>
                <?php include "footer.inc.php"; ?>

                <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

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
            </div>
    </body>
</html>
