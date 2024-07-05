<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <?php
        include "head.inc.php";
        ?>
        <link rel="stylesheet" href="css/payment.css">
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


        <div id="payment_container">
            <h1>Select payment method</h1>

            <div class="card">
                <input type="radio" id="credit" name="payment-method" value="credit">
                <p>
                    <label for="credit">Credit Card</label>
                    <img src="img/credit.png" alt="creditcard" style="width: 30px; height: auto;">
                </p>
            </div>

            <div class="card">
                <input type="radio" id="qr" name="payment-method" value="qr">
                <p>
                    <label for="qr">QR Code</label>
                    <img src="img/qr_icon.png" alt="qrcode" style="width: 30px; height: auto;">
                </p>
            </div>

            <input type="submit" id="next_btn" name="next" value="Next" class="btn btn-lg btn-primary mt-3 mt-md-4 px-4">
        </div>

        <!-- Footer Start -->
        <?php
        include "footer.inc.php";
        ?>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

        <script>
            document.getElementById('next_btn').addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the form from submitting normally

                const creditRadio = document.getElementById('credit');
                const qrRadio = document.getElementById('qr');

                if (creditRadio.checked) {
                    window.location.href = 'pay_Credit.php';
                } else if (qrRadio.checked) {
                    window.location.href = 'pay_QR.php';
                } else {
                    alert('Please select a payment method.');
                }
            });
        </script>


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
