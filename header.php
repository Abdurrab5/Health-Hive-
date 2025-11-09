<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
$role = $_SESSION['role'] ?? 'guest';
$name = $_SESSION['name'] ?? 'Guest';

$currentDir = dirname($_SERVER['PHP_SELF']);
$baseURL = '/healthhive';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?? "Health Hive" ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" >
    <link rel="stylesheet" href="assets/css/style.css" rel="stylesheet">
   
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
     
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="<?= $baseURL ?>/index.php">Health Hive</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">

    <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/index.php">Home</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/medicine.php">Medicine</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/lab_test.php">Medical Test</a></li>
    
    <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/about.php">About</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/contact.php">Contact</a></li>
       
      
        <?php if ($role === 'admin'): ?>
            <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Admin Panel
  </a>
  <ul class="dropdown-menu" aria-labelledby="adminDropdown">
    <li><a class="dropdown-item" href="<?= $baseURL ?>/admin/dashboard_admin.php">Dashboard</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/admin/registration_request.php">Registration Requests</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/admin/users.php">Users</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/admin/account.php">Account</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/admin/complaints.php">Users complaints</a></li>
   
  </ul>
</li>

          <?php elseif ($role === 'lab'): ?>
            <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="labDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
   Lab Panel
  </a>
  <ul class="dropdown-menu" aria-labelledby="labDropdown">
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/dashboard_lab.php">Dashboard</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/profile_update.php">Profile</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/account.php">Account</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/add_test.php">Add Test</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/view_tests.php">view Test</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/manage_lab_requests.php">Test Request</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/lab/upload_report.php">Upload Reports</a></li>
    </ul>
</li>   <?php elseif ($role === 'pharmacy'): ?>
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="pharmacyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
   Pharmacy Panel
  </a>
  <ul class="dropdown-menu" aria-labelledby="pharmacyDropdown">
    <li><a class="dropdown-item" href="<?= $baseURL ?>/pharmacy/dashboard_pharmacy.php">Dashboard</a></li>

    <li><a class="dropdown-item" href="<?= $baseURL ?>/pharmacy/manage_medicine.php">Manage Medicine</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/pharmacy/profile_update.php">Profile</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/pharmacy/account.php">Account</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/pharmacy/orders.php">Orders</a></li>
 </ul>
</li>     <?php elseif ($role === 'user'): ?>
          <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
   User Panel
  </a>
  <ul class="dropdown-menu" aria-labelledby="userDropdown">
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/dashboard_user.php">Dashboard</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/profile_update.php">Profile</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/account.php">Account</a></li>
 
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/view_lab_tests.php">View Test Results</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/medicine_orders.php">View Medicine</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/complaints.php">Add complaints</a></li>
    <li><a class="dropdown-item" href="<?= $baseURL ?>/user/my_complaints.php">View Complaint</a></li>
   
  </ul>
</li>  <?php endif; ?>
        <?php if ($role !== 'guest'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/logout.php">Logout </a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $baseURL ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
