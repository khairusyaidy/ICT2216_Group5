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
                <a href="index.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="about.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a>
                <a href="service.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'service.php' ? 'active' : '' ?>">Service</a>
                <a href="price.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'price.php' ? 'active' : '' ?>">Price</a>
                <a href="booking.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'booking.php' ? 'active' : '' ?>">Booking</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?= basename($_SERVER['PHP_SELF']) == 'blog.php' || basename($_SERVER['PHP_SELF']) == 'single.php' ? 'active' : '' ?>" data-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="blog.php" class="dropdown-item <?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : '' ?>">Blog Grid</a>
                        <a href="single.php" class="dropdown-item <?= basename($_SERVER['PHP_SELF']) == 'single.php' ? 'active' : '' ?>">Blog Detail</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </div>
        </div>
    </nav>
</div>
