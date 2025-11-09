<?php
$page_title = "Admin Comission";
require_once __DIR__ . '/../header.php';
 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["commission"])) {
    $commission = intval($_POST["commission"]);

    $stmt = $conn->prepare("UPDATE admin_settings SET value = ?, created_at = NOW() WHERE name = 'commission_percent'");
    $stmt->bind_param("s", $commission);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Commission updated to $commission%";
    } else {
        $_SESSION['msg'] = "Failed to update commission.";
    }
}

header("Location: dashboard_admin.php");
exit();
$commission_result = $conn->query("SELECT value FROM admin_settings WHERE name='commission_percent'");
$commission = $commission_result->fetch_assoc()['value'] ?? 10;
?>
 <h4 class="mt-4">Platform Commission (%)</h4>
<form method="post" action="update_commission.php" class="mb-4 w-50">
    <div class="input-group">
        <input type="number" name="commission" value="<?= htmlspecialchars($commission) ?>" class="form-control" min="0" max="100" required>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</form>


<?php
require_once __DIR__ . '/../footer.php';

?>