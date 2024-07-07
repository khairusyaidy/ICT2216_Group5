<?php
include "dbconntest.php";

// Check if user is logged in, otherwise redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Initialize variables for form validation
$start_date = $end_date = $reason = "";
$start_date_err = $end_date_err = $reason_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate Start Date
    if (empty(trim($_POST["start_date"]))) {
        $start_date_err = "Please enter start date.";
    } else {
        $start_date = trim($_POST["start_date"]);
    }
    
    // Validate End Date
    if (empty(trim($_POST["end_date"]))) {
        $end_date_err = "Please enter end date.";
    } else {
        $end_date = trim($_POST["end_date"]);
    }
    
    // Validate Reason
    if (empty(trim($_POST["reason"]))) {
        $reason_err = "Please enter reason.";
    } else {
        $reason = trim($_POST["reason"]);
    }
    
    // Check input errors before inserting into database
    if (empty($start_date_err) && empty($end_date_err) && empty($reason_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO availability (StartDate, EndDate, Reason, Created_By) VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_start_date, $param_end_date, $param_reason, $param_created_by);
            
            // Set parameters
            $param_start_date = $start_date;
            $param_end_date = $end_date;
            $param_reason = $reason;
            $param_created_by = $_SESSION["id"]; // Staff ID of logged-in user
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to availability list page after successful insertion
                header("location: savailability.php");
                exit;
            } else {
                echo '<div class="alert alert-danger">Oops! Something went wrong. Please try again later.</div>';
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "head.inc.php"; ?>
    <style>
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
        }
    </style>
    <script src="js/inactivity.js"></script>
</head>
<body>
    <!-- Topbar Start -->
    <?php include "topbar.inc.php"; ?>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <?php include "adminnav.php"; ?>
    <!-- Navbar End -->

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add Availability Record</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                    <!-- Start Date -->
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control <?php echo (!empty($start_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $start_date; ?>">
                        <span class="invalid-feedback"><?php echo $start_date_err; ?></span>
                    </div>
                    
                    <!-- End Date -->
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control <?php echo (!empty($end_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $end_date; ?>">
                        <span class="invalid-feedback"><?php echo $end_date_err; ?></span>
                    </div>
                    
                    <!-- Reason -->
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea name="reason" class="form-control <?php echo (!empty($reason_err)) ? 'is-invalid' : ''; ?>"><?php echo $reason; ?></textarea>
                        <span class="invalid-feedback"><?php echo $reason_err; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="savailability.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include "footer.inc.php"; ?>
    <!-- Footer End -->
</body>
</html>
