<?php
ob_start();
?>

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
    <?php include "adminnav.php"; ?>
    <!-- Navbar End -->

    <style>
        .package-title {
            text-align: center;
        }
    </style>

    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-3">
            <div class="row">
                <div class="col text-center mb-4">
                    <h1>Add Staff Account</h1>
                </div>
            </div>

            <main>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Enter Staff Details</h6>
                        </div>
                        <div class="card-body">
                            <form method="post" action="process_addstaff.php">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Account</button>
                                <a href="staffaccount.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
   </section>
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
        </div>
</body>
</html>
