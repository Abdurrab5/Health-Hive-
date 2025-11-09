<?php
$page_title = "Welcome to Health Hive";
require_once 'header.php';
?>
    
<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/hero_banner.jpg" class="d-block w-100" alt="Health Hive Banner" style="max-height: 450px; object-fit: cover;">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
        <h2 class="text-light">Welcome to Health Hive</h2>
        <p>Your one-stop platform for lab tests & medicine delivery</p>
        <a href="register.php" class="btn btn-success btn-lg mt-2"><i class="bi bi-person-plus-fill"></i> Join Now</a>
      </div>
    </div>
  </div>
</div>

<!-- Features Section -->
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4">
            <h1 class="display-5 fw-bold text-primary">Why Choose Health Hive?</h1>
            <p class="lead">Seamlessly connect with certified labs and pharmacies from the comfort of your home.</p>
            <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item">✅ Book lab test appointments</li>
                <li class="list-group-item">✅ Buy medicine from trusted pharmacies</li>
                <li class="list-group-item">✅ Register as a lab or pharmacy and get approved by the admin</li>
                <li class="list-group-item">✅ Secure login and transaction system</li>
                <li class="list-group-item">✅ User complaints and support</li>

                   </ul>
            <a href="medicine.php" class="btn btn-outline-primary me-2"><i class="bi bi-capsule-pill"></i> Browse Medicines</a>
            <a href="lab_test.php" class="btn btn-outline-secondary"><i class="bi bi-flask"></i> Book Lab Test</a>
        </div>
        <div class="col-md-6">
            <img src="assets/images/1.jpg" class="img-fluid rounded shadow" alt="Health Services">
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
