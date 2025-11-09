<?php
 $page_title = "Admin Register";
require_once __DIR__ . '/../header.php';
 


$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure hash
    $role     = "admin";
    $status   = "approved";

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $msg = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $role, $status);

        if ($stmt->execute()) {
            $msg = "Admin registered successfully!";
        } else {
            $msg = "Error: " . $stmt->error;
        }
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Register Admin - Health Hive</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
<div class="container">
    <h2>Admin Registration</h2>
    <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
    <form method="POST">
        <input type="text" name="name" required class="form-control mb-2" placeholder="Full Name">
        <input type="email" name="email" required class="form-control mb-2" placeholder="Email">
        <input type="password" name="password" required class="form-control mb-2" placeholder="Password">
        <button class="btn btn-primary">Register</button>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>
