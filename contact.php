<?php
$page_title = "Contact Us - Health Hive";
require_once 'header.php';
?>

<!-- Contact Hero Section -->
<div class="container-fluid bg-primary text-white py-5 text-center">
    <h1 class="display-5">Get in Touch with Health Hive</h1>
    <p class="lead">We’re here to assist you — whether you're a user, lab, or pharmacy!</p>
</div>

<!-- Contact Info Section -->
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-6">
            <h3>Contact Information</h3>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-envelope-fill text-danger me-2"></i> <strong>Email:</strong> support@healthhive.local</li>
                <li class="mb-2"><i class="bi bi-telephone-fill text-success me-2"></i> <strong>Phone:</strong> +92-300-1234567</li>
                <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i> <strong>Address:</strong> Office #1, Health Plaza, Lahore, Pakistan</li>
            </ul>

            <h4 class="mt-4">Follow Us</h4>
            <div>
                <a href="#" class="text-decoration-none me-3"><i class="bi bi-facebook fs-3 text-primary"></i></a>
                <a href="#" class="text-decoration-none me-3"><i class="bi bi-twitter fs-3 text-info"></i></a>
                <a href="#" class="text-decoration-none me-3"><i class="bi bi-instagram fs-3 text-danger"></i></a>
                <a href="#" class="text-decoration-none me-3"><i class="bi bi-linkedin fs-3 text-primary"></i></a>
                <a href="mailto:support@healthhive.local" class="text-decoration-none"><i class="bi bi-google fs-3 text-danger"></i></a>
            </div>
        </div>

        <!-- Google Map -->
        <div class="col-md-6">
            <h3>Find Us on Map</h3>
            <div class="ratio ratio-4x3 rounded shadow">
                <iframe src="https://maps.google.com/maps?q=Health%20Plaza,%20Lahore&t=&z=13&ie=UTF8&iwloc=&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Optional Contact Form (If needed) -->
<div class="container py-5">
    <h3 class="text-center mb-4">Send Us a Message</h3>
    <form class="row g-3" method="POST" action="send_message.php">
        <div class="col-md-6">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-12">
            <label for="message" class="form-label">Your Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>
