<?php
ob_start();
// Start session at the beginning of the script
session_start();

// Check if user is logged in
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Redirect to login if not logged in
if (!$logged_in) {
    header("Location: login.php");
    exit;
}

// Include your database configuration file here if not already included
include "dbconntest.php"; // Adjust the path as per your configuration

$errorMsg = "";
$successMsg = "";

// Check if the user has the 'admin' role
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] !== 'admin') {
    header("location: unauthorized.php");
    exit;
}

// Check if ID is provided in the URL
if (!isset($_GET['id'])) {
    die("No staff ID selected.");
}

// Fetch staff details based on ID
$staff_id = $_GET['id'];
$stmt = $conn->prepare("SELECT FirstName, LastName, Email, PhoneNo, Role FROM staff WHERE ID = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
} else {
    die("Staff not found.");
}

$stmt->close();

// Handle deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    // Perform deletion
    $stmt = $conn->prepare("DELETE FROM staff WHERE ID = ?");
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        // Set success message in session variable
        $_SESSION['successMsg'] = "Staff account deleted successfully.";

        // Redirect back to staffaccount.php with success message
        header("Location: staffaccount.php");
        exit;
    } else {
        $errorMsg = "Error deleting staff account: " . $conn->error;
    }

    $stmt->close();
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
                    <h1>Delete Staff Account</h1>
                </div>
            </div>

            <main>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Staff Details</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($errorMsg)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMsg; ?>
                                </div>
                            <?php endif; ?>
                            <form method="post">
                                <p>Are you sure you want to delete the following staff member?</p>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($staff['FirstName'] . " " . $staff['LastName']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($staff['Email']); ?></p>
                                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($staff['PhoneNo']); ?></p>
                                <input type="hidden" name="confirm_delete" value="1">
                                <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                <a href="staffaccount.php" class="btn btn-secondary">Cancel</a>
                            </form>
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
