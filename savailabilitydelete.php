<?php
include "dbconntest.php";

// Check if user is logged in, otherwise redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Initialize variables
$id = $deleted_reason = '';

// Process form submission when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $id = $_POST['id'];
    $deleted_reason = $_POST['deleted_reason'];

    // Update query
    $sql_update = "UPDATE availability SET Deleted_At = NOW(), Deleted_Reason = ? WHERE ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $deleted_reason, $id);

    if ($stmt_update->execute()) {
        // Redirect to availability list after successful delete
        header("location: savailability.php");
        exit;
    } else {
        echo '<div class="alert alert-danger">Error deleting record: ' . $stmt_update->error . '</div>';
    }
} else {
    // Check if ID parameter is passed via GET
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch record details to display on delete form
        $sql_select = "SELECT ID, StartDate, EndDate, Reason FROM availability WHERE ID = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $start_date = $row['StartDate'];
            $end_date = $row['EndDate'];
            $reason = $row['Reason'];
        } else {
            echo '<div class="alert alert-danger">Record not found.</div>';
            exit;
        }
    } else {
        echo '<div class="alert alert-danger">Invalid request.</div>';
        exit;
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
                <h6 class="m-0 font-weight-bold text-primary">Delete Availability Record</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason:</label>
                        <input type="text" class="form-control" id="reason" name="reason" value="<?php echo htmlspecialchars($reason); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="deleted_reason">Deleted Reason:</label>
                        <textarea class="form-control" id="deleted_reason" name="deleted_reason" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Delete</button>
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
