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
    <?php include "sadminnav.php"; ?>
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
                    <h1>Account Management - Staff Accounts</h1>
                </div>
            </div>

            <main>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Staff Accounts</h6>
                            <a href="addstaffaccount.php" class="btn btn-success">Add</a>
                        </div>
                        <div class="card-body">
                            <?php
                            // Display success message if set
                            if (isset($_SESSION['successMsg'])) {
                                echo "<div class='alert alert-success'>" . $_SESSION['successMsg'] . "</div>";
                                unset($_SESSION['successMsg']); // Clear the session variable after displaying once
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php getUsers(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

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
    </section>
</body>
</html>

<?php

// Include your database configuration file here if not already included
// Helper function to get all staff accounts
function getUsers() {
    global $errorMsg, $success; // Ensure these variables are defined if needed
    // Include your database configuration file here if not already included
    include "db_connect.php"; // Ensure to adjust the path to your actual configuration file
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName, Email, PhoneNo, Role FROM staff");

        // Execute SQL statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Display staff accounts in a table format
        while ($row = $result->fetch_assoc()) {
            echo "<tr id='" . htmlspecialchars($row['ID']) . "'>";
            echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['FirstName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['PhoneNo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Role']) . "</td>";
            echo "<td>";
            echo "<figure>";
            echo "<a href='editstaff.php?id=" . htmlspecialchars($row['ID']) . "'><img class='edit_account' src='img/edit-button.png' alt='Edit Account' data-user-id='" . htmlspecialchars($row['ID']) . "'></a>";
            echo "<a href='deletestaff.php?id=" . htmlspecialchars($row['ID']) . "'><img class='delete_account' src='img/trash.png' alt='Delete Account' data-user-id='" . htmlspecialchars($row['ID']) . "'></a>";
            echo "</figure>";
            echo "</td>";
            echo "</tr>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
