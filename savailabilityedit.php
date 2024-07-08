<?php
include "dbconntest.php";

// Check if user is logged in, otherwise redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if the user has the 'staff' role
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] !== 'staff') {
    header("location: unauthorized.php");
    exit;
}

// Initialize variables
$id = $start_date = $end_date = $update_reason = '';

// Process form submission when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $id = $_POST['id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $update_reason = $_POST['update_reason'];

    // Update query
    $sql_update = "UPDATE availability SET StartDate = ?, EndDate = ?,  Update_Reason = ?, Updated_At = NOW() WHERE ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $start_date, $end_date, $update_reason, $id);

    if ($stmt_update->execute()) {
        // Redirect to availability list after successful update
        header("location: savailability.php");
        exit;
    } else {
        echo '<div class="alert alert-danger">Error updating record: ' . $stmt_update->error . '</div>';
    }
} else {
    // Retrieve availability record based on ID from query parameter
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch record details
        $sql_select = "SELECT ID, StartDate, EndDate FROM availability WHERE ID = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $start_date = $row['StartDate'];
            $end_date = $row['EndDate'];
        } else {
            echo '<div class="alert alert-danger">Record not found.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Invalid request.</div>';
    }
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
                <h6 class="m-0 font-weight-bold text-primary">Edit Availability Record</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="update_reason">Update Reason:</label>
                        <input type="text" class="form-control" id="update_reason" name="update_reason" value="<?php echo htmlspecialchars($update_reason); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="savailability.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include "footer.inc.php"; ?>
    <!-- Footer End -->

</body>
</html>

<?php
$conn->close();
?>
