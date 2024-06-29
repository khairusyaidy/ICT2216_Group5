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


    <!-- daycare Services Section -->
    <section class="py-5">
    <div class="container px-4 px-lg-5 mt-3">
        <div class="row">
            <div class="col text-center mb-4">
                <h1>Daycare Services at Fur Season Hotel</h1>
                <br>
                <img class="img-fluid w-50" src="img/about-1.jpg" alt="About Image">
                <br>
                <br>
                <p>
                    At Fur Season Hotel, we provide exceptional daycare services that cater to the needs of your beloved dogs. Our daycare program is designed to ensure that your pet enjoys a day filled with fun, exercise, and socialization in a safe and nurturing environment. Whether you're at work or just need a day to yourself, our dedicated staff will take care of your furry friend as if they were their own.
                </p>
                <br>
                <h2>Daycare Schedule</h2>
                <p>
                    Our daycare service operates on a convenient schedule to accommodate your busy life. You can drop off your dog in the morning and pick them up in the evening, giving you peace of mind throughout the day.
                </p>
                <p>
                    <span>Pickup: 7:00 AM – 9:00 AM</span><br>
                    <span>Drop-off: 4:00 PM – 6:00 PM</span>
                </p>
                <br>
                <h2>Daycare Activities</h2>
                <p>
                    At Fur Season Hotel, your dog will have the opportunity to engage in a variety of activities that promote physical and mental well-being. Our daycare includes supervised playtime, socialization with other dogs, and plenty of exercise. We also provide quiet time for rest and relaxation, ensuring that your pet returns home happy and tired after a full day of fun.
                </p>
                <br>
                <h2>Rates</h2>
                <p>
                    Our daycare service is competitively priced at $80 per day. This fee includes all activities and supervision, ensuring your dog receives the highest level of care and attention.
                </p>
                <br>
                
                <br>
                <h6>
                    At Fur Season Hotel, we understand the importance of providing a safe, fun, and stimulating environment for your dog while you're away. Trust us to take care of your furry friend, and they'll look forward to their time at our daycare as much as you enjoy your peace of mind. Click on "Book Now" below to secure a spot for your dog in our daycare program!
                </h6>
            </div>
        </div>
        <div class="row">
                <div class="col text-center mb-4">
                    <button onclick="window.location.href='book_daycare.php'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Book Now</button>
                </div>
            </div>
    </div>
</section>

           <div class="container-fluid bg-light my-5 p-0 py-5">
    <div class="container p-0 py-5">
        <div class="d-flex flex-column text-center mb-5">
            <h1 class="display-4 m-0">Our Customers <span class="text-primary">Says</span></h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <?php
            // Include the database connection file
            include 'db_connect.php';

            // Query to fetch reviews and service names where ServiceID is 3, 4, 5, 6, 7, 8, 9, 10, 11
            $sql = "SELECT review.Rating, review.Feedback
                FROM review 
                JOIN service ON review.ServiceID = service.ID 
                WHERE review.ServiceID = 2";
            $result = $conn->query($sql);
            
            $serviceName = "DayCare";
            
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

    <!-- daycare Services Section End -->

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
