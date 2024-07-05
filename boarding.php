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
                <button onclick="window.location.href='<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'book_boarding.php' : 'login.php'; ?>'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Book Now</button>
            </div>
        </div>
    </div>
</section>


    <!-- boarding Services Section End -->

             <div class="container-fluid bg-light my-5 p-0 py-5">
    <div class="container p-0 py-5">
        <div class="d-flex flex-column text-center mb-5">
            
            <h1 class="display-4 m-0">Our Customers <span class="text-primary">Says</span></h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <?php
            // Include the database connection file
            include 'dbconntest.php';

            // Query to fetch reviews and service names where ServiceID is 3, 4, 5, 6, 7, 8, 9, 10, 11
            $sql = "SELECT review.Rating, review.Feedback
                FROM review 
                JOIN service ON review.ServiceID = service.ID 
                WHERE review.ServiceID = 1";
            $result = $conn->query($sql);
            
            $serviceName = "Boarding";
            
if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                    // Display each review
                    $ratingStars = str_repeat('★', $row['Rating']) . str_repeat('☆', 5 - $row['Rating']);
                    echo "<div class='bg-white mx-3 p-4'>
                            <div class='mb-3'>
                                <h5>{$serviceName}</h5>
                                <i>{$ratingStars}</i>
                            </div>
                            <p class='m-0'>\"{$row['Feedback']}\"</p>
                          </div>";
                
            }
}
           else {
                echo "<p>No reviews available.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>
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
