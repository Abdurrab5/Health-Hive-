<?php
require_once __DIR__ . '/../header.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch medicine orders for the logged-in user
$sql = "SELECT mo.*, m.name AS medicine_name, p.name AS pharmacy_name 
        FROM medicine_orders mo
        JOIN medicines m ON mo.medicine_id = m.id
        JOIN users p ON mo.pharmacy_id = p.id
        WHERE mo.user_id = ?
        ORDER BY mo.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Medicine Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">My Medicine Orders</h2>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Order ID</th>
                        <th>Medicine</th>
                        <th>Pharmacy</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Delivery Address</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                            <td><?= htmlspecialchars($row['pharmacy_name']) ?></td>
                            <td class="text-center"><?= $row['quantity'] ?></td>
                            <td>$<?= number_format($row['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?: '<span class="text-muted">N/A</span>' ?></td>
                            <td>
                                <span class="badge 
                                    <?= $row['payment_status'] === 'paid' ? 'bg-success' : 
                                        ($row['payment_status'] === 'pending' ? 'bg-warning text-dark' : 
                                        'bg-secondary') ?>">
                                    <?= ucfirst($row['payment_status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                            <td>
                                <span class="badge 
                                    <?= $row['status'] === 'delivered' ? 'bg-success' :
                                        ($row['status'] === 'cancelled' ? 'bg-danger' :
                                        ($row['status'] === 'processing' ? 'bg-info text-dark' :
                                        'bg-secondary')) ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                            <td><?= $row['transaction_id'] ? htmlspecialchars($row['transaction_id']) : '<span class="text-muted">N/A</span>' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No medicine orders found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
require_once __DIR__ . '/../footer.php';

?>