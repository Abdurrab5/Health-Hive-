<?php
$page_title = "Registration";
require_once 'header.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $address = $_POST["address"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $license_number = $_POST["license_number"] ?? '';
    $license_expiry_date = $_POST["license_expiry_date"] ?? '';
    $store_name = $_POST["store_name"] ?? '';

    // Handle logo upload (only for lab and pharmacy)
    $logo_path = '';
    if (($role === 'lab' || $role === 'pharmacy') && isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '_' . basename($_FILES['logo']['name']);
        $target_file = $upload_dir . $filename;
        
        // Validate logo file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['logo']['type'], $allowed_types)) {
            $msg = "Invalid file type. Only JPEG, PNG, or GIF files are allowed.";
            exit;
        }
        
        // Validate logo file size (max 2MB)
        if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
            $msg = "File size exceeds the 2MB limit.";
            exit;
        }
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_path = $target_file;
        }
    }

    if ($role === 'lab' || $role === 'pharmacy') {
        $sql = "INSERT INTO users (name, email, password, role, address,phone, license_number, license_expiry_date, store_name, logo_path, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssissss", $name, $email, $password, $role, $address,$phone, $license_number, $license_expiry_date, $store_name, $logo_path);
    } else {
        $sql = "INSERT INTO users (name, email, password, role, address, phone, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $password, $role, $address, $phone);
  }

    if ($stmt->execute()) {
        $msg = "Registration successful! Wait for admin approval.";
        // Send email to user (Optional)
       // mail($email, "Registration Confirmation", "Dear $name, your registration is under review. We will notify you once it's approved.", "From: no-reply@yourdomain.com");
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<div class="container">
    <h2 class="mb-4">Register</h2>
    <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" required class="form-control mb-2" placeholder="Full Name">
        <input type="email" name="email" required class="form-control mb-2" placeholder="Email">
        <input type="password" name="password" required class="form-control mb-2" placeholder="Password">

        <select name="role" class="form-control mb-2" required id="roleSelect">
            <option value="">Select Role</option>
            <option value="user">User</option>
            <option value="lab">Lab</option>
            <option value="pharmacy">Pharmacy</option>
        </select>

        <input type="text" name="address" class="form-control mb-2" placeholder="Address (optional)">
        <input type="number" name="phone" class="form-control mb-2" placeholder="Phone Number (optional)">

        <!-- Fields for lab/pharmacy -->
        <div id="extraFields" style="display: none;">
            <input type="text" name="store_name" class="form-control mb-2" placeholder="Lab/Pharmacy Name">
            <input type="text" name="license_number" class="form-control mb-2" placeholder="License Number">
            <input type="date" name="license_expiry_date" class="form-control mb-2" placeholder="License Expiry Date">
            <input type="file" name="logo" class="form-control mb-2" accept="image/*">
        </div>

        <button class="btn btn-primary">Register</button>
    </form>
</div>

<script>
    const roleSelect = document.getElementById('roleSelect');
    const extraFields = document.getElementById('extraFields');

    roleSelect.addEventListener('change', function () {
        extraFields.style.display = (this.value === 'lab' || this.value === 'pharmacy') ? 'block' : 'none';
    });
</script>
