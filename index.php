<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "head.inc.php";
    ?>
</head>

<body>
    <!-- Topbar Start -->
      <?php
        include "topbar.inc.php";
        ?>
    <!-- Topbar End -->


    <!-- Navbar Start -->
     <?php
        include "nav.inc.php";
        ?>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h3 class="text-white mb-3 d-none d-sm-block">Best Pet Services</h3>
                            <h1 class="display-3 text-white mb-3">Keep Your Pet Happy</h1>
                            <h5 class="text-white mb-3 d-none d-sm-block">Keep your pet happy with our dedicated care and expert grooming services at Fur Season Hotel.
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h3 class="text-white mb-3 d-none d-sm-block">Best Pet Services</h3>
                            <h1 class="display-3 text-white mb-3">Pet Spa & Grooming</h1>
                            <h5 class="text-white mb-3 d-none d-sm-block">Experience premium pet spa and grooming services at Fur Season Hotel, where your furry friends receive expert care and pampering.</h5>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                <div class="btn btn-primary rounded" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon mb-n2"></span>
                </div>
            </a>
            <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                <div class="btn btn-primary rounded" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon mb-n2"></span>
                </div>
            </a>
        </div>
    </div>
    <!-- Carousel End -->


   


    <!-- About Start -->
    <div class="container py-5">
        <div class="row py-5">
            <div class="col-lg-7 pb-5 pb-lg-0 px-3 px-lg-5">
                <h4 class="text-secondary mb-3">About Us</h4>
                <h1 class="display-4 mb-4">Our Commitment to <span class="text-primary">Your Pet</span></h1>
                <p class="mb-4">At Fur Season Hotel, we are dedicated to providing exceptional care and comfort for your furry friends. Our state-of-the-art facility is designed to cater to the unique needs of each dog, ensuring they receive the highest quality of care and attention. Whether you need grooming, daycare, or boarding services, our experienced and compassionate team is here to ensure your pet enjoys a safe, fun, and relaxing experience.</p>
                <ul class="list-inline">
                    <li><h6><i class="fa fa-check-double text-secondary mr-2"></i>Professionals passionate about animals, committed to top-notch care.</h6></li>
                    <li><h6><i class="fa fa-check-double text-secondary mr-2"></i>Safety and cleanliness ensure your dog's health and security.</h6></li>
                    <li><h6><i class="fa fa-check-double text-secondary mr-2"></i>Activities promote your dog's well-being.</h6></li>
                    <li><h6><i class="fa fa-check-double text-secondary mr-2"></i>Personalized care tailored to each dog's unique needs.</h6></li>
                </ul>

                
            </div>
            <div class="col-lg-5">
                <div class="row px-3">
                    <div class="col-12 p-0">
                        <img class="img-fluid w-100" src="img/about-1.jpg" alt="">
                    </div>
                    <div class="col-6 p-0">
                        <img class="img-fluid w-100" src="img/about-2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Services Start -->
    <div class="container-fluid bg-light pt-5">
        <div class="container py-5">
            <div class="d-flex flex-column text-center mb-5">
                <h4 class="text-secondary mb-3">Our Services</h4>
                <h1 class="display-4 m-0"><span class="text-primary">Premium</span> Pet Services</h1>
            </div>
            <div class="row pb-3">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-house display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Boarding</h3>
                        <p>Enjoy peace of mind knowing your dog is in good hands with our top-quality boarding services.</p>
                        <a class="text-uppercase font-weight-bold" href="boarding.php">Read More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-dog display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Daycare</h3>
                        <p>Our daycare program provides a fun and engaging environment for your dog while you're away.</p>
                        <a class="text-uppercase font-weight-bold" href="daycare.php">Read More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-grooming display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Grooming</h3>
                        <p>Keep your dog looking and feeling their best with our comprehensive grooming services.</p>
                        <a class="text-uppercase font-weight-bold" href="grooming.php">Read More</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Services End -->


    <!-- Features Start -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <img class="img-fluid w-100" src="img/feature.jpg" alt="">
            </div>
            <div class="col-lg-7 py-5 py-lg-0 px-3 px-lg-5">
                <h4 class="text-secondary mb-3">Why Choose Us?</h4>
                <h1 class="display-4 mb-4"><span class="text-primary">Special Care</span> On Pets</h1>
                <p class="mb-4">Experience special care for your pets at Fur Season Hotel, where their well-being is our priority.</p>
                <div class="row py-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="flaticon-cat font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Expert Care</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="flaticon-doctor font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Personalized Attention</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <h1 class="flaticon-care font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Safe Environment</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <h1 class="flaticon-dog font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Engaging Activities</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->



    <!-- Testimonial Start -->
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
                JOIN service ON review.ServiceID = service.ID";
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
    <!-- Testimonial End -->


    <!-- Footer Start -->
      <?php
        include "footer.inc.php";
        ?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>