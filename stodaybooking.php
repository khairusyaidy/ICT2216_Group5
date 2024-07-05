<?php ob_start(); ?>
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

    <style>
        .package-title {
            text-align: center;
        }
    </style>

    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-3">
            <div class="row">
                <div class="col text-center mb-4">
                    <h1>Today's Bookings</h1>
                </div>
            </div>

            <main>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Current Bookings</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                        // Include your database configuration file here if not already included
                                        include "dbconntest.php";

                                        // Fetch current bookings and their services for today
                                        $sql = "SELECT b.ID, b.DropOffDate, b.PickUpDate, s.ServiceName, s.Price, b.Status, c.FirstName, c.LastName 
                                                FROM booking b
                                                INNER JOIN service s ON b.ServiceID = s.ID
                                                INNER JOIN customer c ON b.CustomerID = c.ID
                                                WHERE DATE(b.DropOffDate) = CURDATE()"; // Change condition as per your requirement

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['DropOffDate']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['PickUpDate']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['ServiceName']) . '</td>';
                                                echo '<td>$' . htmlspecialchars($row['Price']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['Status']) . '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="7">No bookings found for today.</td></tr>';
                                        }

                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
        </div>
</body>
</html>
