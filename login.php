<?php
$page_title = "Login";
require_once 'header.php';
 
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if ($user["status"] !== "approved") {
            $msg = "Account not approved yet.";
        } elseif (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["name"] = $user["name"];

            // Redirect by role
            switch ($user["role"]) {
                case "user": header("Location: user/dashboard_user.php"); break;
                case "lab": header("Location: lab/dashboard_lab.php"); break;
                case "pharmacy": header("Location: pharmacy/dashboard_pharmacy.php"); break;
                case "admin": header("Location: admin/dashboard_admin.php"); break;
            }
            exit();
        } else {
            $msg = "Incorrect password.";
        }
    } else {
        $msg = "User not found.";
    }
}
?>

 

<div class="container">
    <h2 class="mb-4">Login</h2>
    <?php if ($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>
    <form method="POST">
        <input type="email" name="email" required class="form-control mb-2" placeholder="Email">
        <input type="password" name="password" required class="form-control mb-2" placeholder="Password">
        <button class="btn btn-success">Login</button>
    </form>
</div>
</body>
</html>
