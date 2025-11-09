<?php
// Include your database connection
$page_title = "Profile";
require_once __DIR__ . '/../header.php';

// Start session to get the logged-in user ID (or fetch from session)
 
$user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session

// Check if user is logged in
if (!$user_id) {
    echo "<div class='alert alert-danger'>You must be logged in to access this page.</div>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : null;
    $store_name = mysqli_real_escape_string($conn, $_POST['store_name']);
    $license_number = mysqli_real_escape_string($conn, $_POST['license_number']);
    $license_expiry_date = mysqli_real_escape_string($conn, $_POST['license_expiry_date']);
    
    // Handle file upload for logo (if provided)
    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = '../' . $target_dir . basename($_FILES['logo']['name']);
        $logo_path =$target_dir. basename($_FILES['logo']['name']);
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo_path =$target_dir. basename($_FILES['logo']['name']);
        } else {
            echo "<div class='alert alert-danger'>Error uploading logo.</div>";
            exit;
        }
    }

    // Prepare the SQL query for updating the profile
    // If no new logo is uploaded, retain the old logo
    if (empty($logo_path)) {
        $logo_path = $user['logo_path']; // Keep the existing logo
    }

    // If password is provided, hash it
    $update_query = "UPDATE users SET 
                    name = '$name', 
                    email = '$email', 
                    phone = '$phone', 
                    address = '$address', 
                    store_name = '$store_name', 
                    logo_path = '$logo_path', 
                    license_number = '$license_number', 
                    license_expiry_date = '$license_expiry_date'";

    if ($password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = '$password_hash'";
    }

    // Add WHERE condition to update only the logged-in user
    $update_query .= " WHERE id = $user_id";

    // Execute query and check if successful
    if (mysqli_query($conn, $update_query)) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating profile: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch the user's existing profile information
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit;
}
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Update Profile</h1>
    <!-- User Update Form -->
    <form action="profile_update.php" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <h2 class="h4">User Information</h2>
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" name="address" id="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Leave blank to keep current password">
                </div>

                <div class="mb-3">
                    <label for="store_name" class="form-label">Store Name:</label>
                    <input type="text" class="form-control" name="store_name" id="store_name" value="<?php echo htmlspecialchars($user['store_name']); ?>">
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Store Logo:</label>
                    <input type="file" class="form-control" name="logo" id="logo">
                    <!-- If logo already exists, display it -->
                    <?php if ($user['logo_path']): ?>
                        <div class="mt-2">
                            <img src="../<?php echo $user['logo_path']; ?>" alt="Store Logo" style="width: 150px;">
                        </div>
                    <?php endif; ?>
                </div>

                <h2 class="h4 mt-4">Pharmacy Information</h2>
                <div class="mb-3">
                    <label for="license_number" class="form-label">License Number:</label>
                    <input type="text" class="form-control" name="license_number" id="license_number" value="<?php echo htmlspecialchars($user['license_number']); ?>">
                </div>

                <div class="mb-3">
                    <label for="license_expiry_date" class="form-label">License Expiry Date:</label>
                    <input type="date" class="form-control" name="license_expiry_date" id="license_expiry_date" value="<?php echo htmlspecialchars($user['license_expiry_date']); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>