<?php
ob_start();
session_start();

// Check if the user has the 'customer' role
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] !== 'customer') {
    header("location: unauthorized_adminstaff.php");
    exit;
}

$bookingID = intval($_GET['booking_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include 'dbconntest.php';
    
    $bookingID = intval($_POST['booking_id']);

    $stmt = $conn->prepare("SELECT ServiceID FROM booking WHERE ID = ?");
    $stmt->bind_param("i", $bookingID);
    $stmt->execute();
    $stmt->bind_result($fetchedServiceID);
    $stmt->fetch();
    $stmt->close();

    // Sanitize user input
    $rating = intval($_POST['rating']);
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $serviceID = $fetchedServiceID;
    // Insert the review into the database
    $sql = "INSERT INTO review (Rating, Feedback, ServiceID) VALUES ($rating, '$feedback', $serviceID)";

    if ($conn->query($sql) === TRUE) {
        $reviewID = $conn->insert_id; // Get the ID of the last inserted review
        
        echo "<script>alert('Review submitted successfully!');</script>";
        
        // Update booking table to set review_id
        $updateBookingSql = "UPDATE booking SET ReviewID = ? WHERE ID = ?";
        $stmtUpdate = $conn->prepare($updateBookingSql);
        $stmtUpdate->bind_param("ii", $reviewID, $bookingID);
        
        if ($stmtUpdate->execute()) {
            echo "<script>console.log('Review ID updated in booking table.');</script>";
            echo "<script>window.location.href = 'mybookings.php';</script>";
        } else {
            echo "Error updating booking table: " . $conn->error;
        }

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
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
        <?php include "nav.inc.php"; ?>
        <!-- Navbar End -->

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Review - Fur Season Hotel</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 50%;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-top: 50px;
            }
            .header {
                text-align: center;
                margin-bottom: 40px;
            }
            .header h1 {
                font-size: 2.5em;
                margin-bottom: 20px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                font-size: 1.2em;
                margin-bottom: 10px;
            }
            .star-rating {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
                direction: rtl; /* Change the direction to rtl */
            }
            .star-rating input[type="radio"] {
                display: none;
            }
            .star-rating label {
                font-size: 2em;
                color: #ccc;
                cursor: pointer;
                transition: color 0.3s;
            }
            .star-rating input[type="radio"]:checked ~ label {
                color: #ccc;
            }
            .star-rating input[type="radio"]:checked + label,
            .star-rating input[type="radio"]:checked + label ~ label {
                color: #ffcc00;
            }
            .form-group textarea {
                width: 100%;
                padding: 10px;
                font-size: 1em;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            .buttons {
                text-align: center;
                margin-top: 20px;
            }
            .buttons button {
                padding: 10px 20px;
                font-size: 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>
<body>

    <div class="container">
        <div class="header">
            <h1>Add Review</h1>
        </div>

        <form id="review-form" method="post" action="addreview.php">
            <div class="form-group">
                <label for="rating">Rating (out of 5 stars):</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1">&#9733;</label>
                </div>
            </div>
            <div class="form-group">
                <label for="feedback">Feedback:</label>
                <textarea id="feedback" name="feedback" rows="5" required></textarea>
            </div>

            <div class="buttons">
                <button type="submit">Submit</button>
            </div>
            
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($bookingID); ?>">
        </form>
    </div>

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


</body>
</html>
