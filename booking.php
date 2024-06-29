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


    <!-- Services Start -->
    <div class="container-fluid bg-light pt-5">
        <div class="container py-5">
            <div class="row pb-3">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-house display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Boarding</h3>
                        <p>Enjoy peace of mind knowing your dog is in good hands with our top-quality boarding services.</p>
                        <a class="text-uppercase font-weight-bold" href="book_boarding.php">Book Now</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-dog display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Daycare</h3>
                        <p>Our daycare program provides a fun and engaging environment for your dog while you're away.</p>
                        <a class="text-uppercase font-weight-bold" href="book_daycare.php">Book Now</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="d-flex flex-column text-center bg-white mb-2 p-3 p-sm-5">
                        <h3 class="flaticon-grooming display-3 font-weight-normal text-secondary mb-3"></h3>
                        <h3 class="mb-3">Pet Grooming</h3>
                        <p>Keep your dog looking and feeling their best with our comprehensive grooming services.</p>
                        <a class="text-uppercase font-weight-bold" href="book_grooming.php">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->


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