<?php
$page_title = "Order Management";
require_once __DIR__ . '/../header.php';

$pharmacy_id = $_SESSION['user_id'];

$sql = "SELECT mo.*, m.name AS medicine_name, u.name AS user_name 
        FROM medicine_orders mo
        JOIN medicines m ON mo.medicine_id = m.id
        JOIN users u ON mo.user_id = u.id
        WHERE mo.pharmacy_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="mb-4">Order Management</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Medicine</th>
                <th>Customer</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>Rs. <?= number_format($row['total_amount'], 2) ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $row['status'] == 'pending' ? 'warning' : 
                        ($row['status'] == 'completed' ? 'primary' : 
                        ($row['status'] == 'failed' ? 'danger' : 
                        ($row['status'] == 'cancelled' ? 'secondary' : 'info')))
                    ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td class="text-center">
                    <?php if ($row['status'] == 'pending') { ?>
                        
                        <a href="update_order_status.php?id=<?= $row['id'] ?>&status=completed" class="btn btn-success btn-sm">Mark Completed</a>
                     <a href="update_order_status.php?id=<?= $row['id'] ?>&status=cancelled" class="btn btn-danger btn-sm">Cancel</a>
                    <?php } elseif ($row['status'] == 'completed') { ?>
                        <span class="text-success">Completed</span>
                    <?php } elseif ($row['status'] == 'failed' || $row['status'] == 'cancelled') { ?>
                        <span class="text-muted">No Action</span>
                    <?php } else { ?>
                        <span class="text-muted">Unknown Status</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>