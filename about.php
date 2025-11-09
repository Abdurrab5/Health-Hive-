<?php
$page_title = "About Us - Health Hive";
require_once 'header.php';
?>

<!-- About Hero Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <h1 class="display-5 fw-bold text-primary">About Health Hive</h1>
                <p class="lead">Empowering healthcare through technology. We're bridging the gap between patients, labs, and pharmacies.</p>
                <a href="register.php" class="btn btn-success mt-3"><i class="bi bi-person-plus-fill"></i> Join Health Hive</a>
            </div>
            <div class="col-md-6">
                <img src="assets/images/2.jpg" class="img-fluid rounded shadow" alt="About Health Hive">
            </div>
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<div class="container py-5">
    <div class="row text-center">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <i class="bi bi-flag-fill display-4 text-success mb-3"></i>
                    <h4 class="card-title">Our Mission</h4>
                    <p class="card-text">To make healthcare services accessible, fast, and affordable by digitizing lab bookings and medicine delivery.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <i class="bi bi-eye-fill display-4 text-primary mb-3"></i>
                    <h4 class="card-title">Our Vision</h4>
                    <p class="card-text">To become a trusted digital health hub that empowers users and providers with seamless, secure solutions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Highlight -->
<div class="container pb-5">
    <h3 class="text-center mb-4">Why Choose Health Hive?</h3>
    <div class="row text-center">
        <div class="col-md-3 mb-3">
            <i class="bi bi-flask-fill fs-1 text-info"></i>
            <h5 class="mt-2">Lab Test Booking</h5>
            <p>Book tests online at certified labs with reports delivered digitally.</p>
        </div>
        <div class="col-md-3 mb-3">
            <i class="bi bi-capsule-pill fs-1 text-danger"></i>
            <h5 class="mt-2">Online Pharmacy</h5>
            <p>Shop trusted medicines from local pharmacies and get them delivered fast.</p>
        </div>
        <div class="col-md-3 mb-3">
            <i class="bi bi-wallet2 fs-1 text-success"></i>
            <h5 class="mt-2">Secure Payments</h5>
            <p>Pay using card, online, or opt for cash on delivery with secure tracking.</p>
        </div>
        <div class="col-md-3 mb-3">
            <i class="bi bi-person-check-fill fs-1 text-warning"></i>
            <h5 class="mt-2">Verified Providers</h5>
            <p>Only approved labs and pharmacies are allowed to ensure trust and safety.</p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
