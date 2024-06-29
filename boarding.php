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
    <?php include "nav.inc.php"; ?>
    <!-- Navbar End -->

    
    <style>
    .package-title {
        text-align: center;
    }
</style>


    <!-- boarding Services Section -->
    <section class="py-5">
    <div class="container px-4 px-lg-5 mt-3">
        <div class="row">
            <div class="col text-center mb-4">
                <h1>Boarding Services at Fur Season Hotel</h1>
                <br>
                <img class="img-fluid w-50" src="img/about-2.jpg" alt="About Image">
                <br>
                <br>
                <p>
                    At Fur Season Hotel, we offer premier boarding services that ensure your dog enjoys a comfortable and engaging stay while you’re away. Our boarding program is designed to provide a safe, nurturing, and fun environment for your furry friend, complete with a structured daily routine to keep them happy and healthy.
                </p>
                <br>
                <h2>Boarding Schedule</h2>
                <p>
                    Our boarding service includes convenient drop-off and pickup times to fit your schedule. You can rest assured knowing that your pet is in good hands from the moment they arrive until you pick them up.
                </p>
                <p>
                    <span>Pickup: 7:00 AM – 9:00 AM</span><br>
                    <span>Drop-off: 4:00 PM – 6:00 PM</span>
                </p>
                <br>
                <h2>Daily Routine</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>8:00 AM – 9:00 AM</td>
                            <td>Breakfast & Potty Break</td>
                        </tr>
                        <tr>
                            <td>9:00 AM – 11:00 AM</td>
                            <td>Outdoor Time</td>
                        </tr>
                        <tr>
                            <td>11:00 AM – 12:00 PM</td>
                            <td>Playtime</td>
                        </tr>
                        <tr>
                            <td>12:00 PM – 2:00 PM</td>
                            <td>Nap Time</td>
                        </tr>
                        <tr>
                            <td>2:00 PM – 4:00 PM</td>
                            <td>Brushing, Massage, and Playtime</td>
                        </tr>
                        <tr>
                            <td>5:00 PM – 6:00 PM</td>
                            <td>Dinner Time & Potty Break</td>
                        </tr>
                        <tr>
                            <td>6:00 PM – 9:00 PM</td>
                            <td>Quiet Time</td>
                        </tr>
                        <tr>
                            <td>9:00 PM – 8:00 AM</td>
                            <td>Bed Time</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h2>Rates</h2>
                <p>
                    Our boarding service is competitively priced at $100 per day. This includes all meals, activities, and personalized care to ensure your dog has a pleasant and comfortable stay.
                </p>
                <br>
                <h6>
                    At Fur Season Hotel, we understand how important it is to know your dog is well taken care of while you’re away. Our boarding services are designed to provide peace of mind, knowing that your furry friend is enjoying their stay in a safe, fun, and loving environment. Click on "Book Now" below to book your dog’s boarding stay with us!
                </h6>
            </div>
        </div>
        <div class="row">
            <div class="col text-center mb-4">
                <button onclick="window.location.href='book_boarding.php'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Book Now</button>
            </div>
        </div>
    </div>
</section>


    <!-- boarding Services Section End -->

            <hr style="margin: 40px 0; border-top: 1px solid #ccc;">

<div class="col text-center mb-4">
    <h2>Ratings and Reviews</h2>
    <p>
        We value the feedback from our clients and continually strive to improve our services. Here are some of the reviews from our happy customers:
        <br><br>
        <?php
        // Include the database connection file
        include 'db_connect.php';

        // Query to fetch reviews and service names where ServiceID is 3, 4, or 5
        $sql = "SELECT review.Rating, review.Feedback, service.ServiceName 
                FROM review 
                JOIN service ON review.ServiceID = service.ID 
                WHERE review.ServiceID = 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Display each review
                $ratingStars = str_repeat('★', $row['Rating']) . str_repeat('☆', 5 - $row['Rating']);
                echo "<div style='border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px;'>
                        <strong>{$row['ServiceName']}</strong> - {$ratingStars}
                        <br>
                        \"{$row['Feedback']}\"
                      </div>";
            }
        } else {
            echo "No reviews available.";
        }

        // Close the connection
        $conn->close();
        ?>
    </p>
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
