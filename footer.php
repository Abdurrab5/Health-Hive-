<footer class="bg-dark text-light pt-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Health Hive</h5>
                <p>Your trusted partner for lab tests and medicine purchases.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light">Home</a></li>
                  
        <?php if ($role === 'admin'): ?>
            
  
 
    <li><a class="text-light" href="<?= $baseURL ?>/admin/dashboard_admin.php">Dashboard</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/admin/registration_request.php">Registration Requests</a></li>
    <li><a class=" text-light" href="<?= $baseURL ?>/admin/users.php">Users</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/admin/account.php">Account</a></li>
 
 

          <?php elseif ($role === 'lab'): ?>
            
     <li><a class="text-light" href="<?= $baseURL ?>/lab/dashboard_lab.php">Dashboard</a></li>
    <li><a class=" text-light " href="<?= $baseURL ?>/lab/profile_update.php">Profile</a></li>
    <li><a class="dtext-light" href="<?= $baseURL ?>/lab/account.php">Account</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/lab/add_test.php">Add Test</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/lab/view_tests.php">view Test</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/lab/manage_lab_requests.php">Test Request</a></li>
    <li><a class=" text-light" href="<?= $baseURL ?>/lab/upload_report.php">Upload Reports</a></li>
    
    <?php elseif ($role === 'pharmacy'): ?>
 
    <li><a class="text-light" href="<?= $baseURL ?>/pharmacy/dashboard_pharmacy.php">Dashboard</a></li>

    <li><a class="text-light" href="<?= $baseURL ?>/pharmacy/manage_medicine.php">Manage Medicine</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/pharmacy/profile_update.php">Profile</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/pharmacy/account.php">Account</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/pharmacy/orders.php">Orders</a></li>
    </ul>
</li>     <?php elseif ($role === 'user'): ?>
            <li><a class="text-light" href="<?= $baseURL ?>/user/dashboard_user.php">Dashboard</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/user/profile_update.php">Profile</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/user/account.php">Account</a></li>
 
    <li><a class="text-light" href="<?= $baseURL ?>/user/view_lab_tests.php">View Test Results</a></li>
    <li><a class="text-light" href="<?= $baseURL ?>/user/medicine_orders.php">View Medicine</a></li>
      <?php endif; ?>
        <?php if ($role !== 'guest'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/logout.php">Logout </a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul> </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p>Email: support@healthhive.com</p>
                <p>Phone: +92 123 4567890</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <p class="mb-0">&copy; <?= date('Y') ?> Health Hive. All rights reserved.</p>
        </div>
    </div>
</footer>
