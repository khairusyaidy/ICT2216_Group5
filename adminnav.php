<?php
// Start session at the beginning of the script
session_start();

// Check if user is logged in
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Redirect to login if not logged in
if (!$logged_in) {
    header("Location: login.php");
    exit;
}

// Handle logout request
if (isset($_POST['logout'])) {
    header("Location: logout.php");
    exit;
}
?>

<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-lg-5">
        <a href="" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 display-5 text-capitalize font-italic text-white"><span class="text-primary">Safety</span>First</h1>
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
            <div class="navbar-nav mr-auto py-0">
                <a href="staffhomepage.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'staffhomepage.php' ? 'active' : '' ?>">Home</a>
                <a href="availability.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'availability.php' ? 'active' : '' ?>">Availability</a>
                <a href="booking.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'booking.php' ? 'active' : '' ?>">Booking</a>
                <a href="staffaccount.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'staffaccount.php' ? 'active' : '' ?>">Accounts</a>
            </div>
            <div class="navbar-nav ml-auto">
                <a href="profilepage.php" class="nav-link">
                    <i class="fa fa-user-circle mr-1"></i> Profile
                </a>
                <form class="form-inline my-2 my-lg-0 ml-3" method="post">
                    <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</div>
