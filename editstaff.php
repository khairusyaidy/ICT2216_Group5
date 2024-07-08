<?php
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

// Handle form submission to update staff details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);
    $role = trim($_POST["role"]);

    // Check if password is provided to update
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // Keep existing hashed password if no new password is provided
        $hashed_password = $staff['Password'];
    }

    // Update staff details in the database
    $stmt = $conn->prepare("UPDATE staff SET FirstName=?, LastName=?, Email=?, PhoneNo=?, Password=?, Role=? WHERE ID=?");
    $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $phone, $hashed_password, $role, $staff_id);

    if ($stmt->execute()) {
        // Set success message in session variable
        $_SESSION['successMsg'] = "Staff details updated successfully.";

        // Redirect back to staffaccount.php with success message
        header("Location: staffaccount.php");
        exit;
    } else {
        $errorMsg = "Error updating staff details: " . $conn->error;
    }

    $stmt->close();
}

// Function to hash password (adjust as per your hashing method)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
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
                    <h1>Edit Staff Account</h1>
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
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($staff['FirstName']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($staff['LastName']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staff['Email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['PhoneNo']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="staff" <?php echo ($staff['Role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                                        <option value="admin" <?php echo ($staff['Role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Staff</button>
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
