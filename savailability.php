<?php
ob_start();
include "dbconntest.php";

// Check if user is logged in, otherwise redirect to login page
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "head.inc.php"; ?>
    <style>
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 50px auto;
        }
        .card-header h6 {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Topbar Start -->
    <?php include "topbar.inc.php"; ?>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <?php include "adminnav.php"; ?>
    <!-- Navbar End -->

    <div class="container">
        <!-- Current Availability Records -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Current Availability Records
                    <a href="savailabilityadd.php" class="btn btn-success  float-right">Add</a> <!-- Add button -->
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="availabilityTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th>Created By</th>
                                <th>Updated At</th>
                                <th>Updated Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT ID, StartDate, EndDate, Reason, Created_By, Updated_At, Update_Reason 
                                    FROM availability
                                    WHERE Deleted_At IS NULL";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                    echo '<td>' . ($row['StartDate'] !== null ? htmlspecialchars($row['StartDate']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['EndDate'] !== null ? htmlspecialchars($row['EndDate']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Reason'] !== null ? htmlspecialchars($row['Reason']) : 'NIL') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['Created_By']) . '</td>';
                                    echo '<td>' . ($row['Updated_At'] !== null ? htmlspecialchars($row['Updated_At']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Update_Reason'] !== null ? htmlspecialchars($row['Update_Reason']) : 'NIL') . '</td>';
                                    echo '<td>';
                                    echo '<a href="savailabilityedit.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary btn-sm mr-1">Edit</a>'; // Edit button
                                    echo '<a href="savailabilitydelete.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-danger btn-sm">Delete</a>'; // Delete button
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8">No current availability records found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Past Availability Records -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Past Availability Records</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="pastAvailabilityTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th>Created By</th>
                                <th>Updated At</th>
                                <th>Updated Reason</th>
                                <th>Deleted At</th>
                                <th>Deleted Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT ID, StartDate, EndDate, Reason, Created_By, Updated_At, Update_Reason, Deleted_At, Deleted_Reason 
                                    FROM availability
                                    WHERE Deleted_At IS NOT NULL";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                    echo '<td>' . ($row['StartDate'] !== null ? htmlspecialchars($row['StartDate']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['EndDate'] !== null ? htmlspecialchars($row['EndDate']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Reason'] !== null ? htmlspecialchars($row['Reason']) : 'NIL') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['Created_By']) . '</td>';
                                    echo '<td>' . ($row['Updated_At'] !== null ? htmlspecialchars($row['Updated_At']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Update_Reason'] !== null ? htmlspecialchars($row['Update_Reason']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Deleted_At'] !== null ? htmlspecialchars($row['Deleted_At']) : 'NIL') . '</td>';
                                    echo '<td>' . ($row['Deleted_Reason'] !== null ? htmlspecialchars($row['Deleted_Reason']) : 'NIL') . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="9">No past availability records found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
