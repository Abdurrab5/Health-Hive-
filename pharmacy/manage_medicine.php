<?php
$page_title = "Manage Medicine";
require_once __DIR__ . '/../header.php';

$user_id = $_SESSION['user_id'];
$pharmacy_id = $_SESSION['user_id'] ?? 1;

// === Handle toggle_availability and delete actions via GET ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'toggle_availability') {
        $query = $conn->prepare("SELECT is_available FROM medicines WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        $medicine = $result->fetch_assoc();
    
        if ($medicine) {
            $new_status = $medicine['is_available'] ? 0 : 1;
            $stmt = $conn->prepare("UPDATE medicines SET is_available = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_status, $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = $new_status ? "Medicine is now Available" : "Medicine is now Unavailable";
            } else {
                $_SESSION['error'] = "Failed to update medicine status.";
            }
        }
        header("Location: manage_medicine.php");
        exit;
    }
    
    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM medicines WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Medicine deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete medicine.";
        }
        header("Location: manage_medicine.php");
        exit;
    }
    
}
 

// === Display medicines ===
$query = "SELECT * FROM medicines WHERE pharmacy_id = ? AND status = 1 ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pharmacy Medicine Inventory</h2>
        <a href="add_medicine.php" class="btn btn-success">+ Add Medicine</a>
    </div>

    <div class="table-responsive">
        <table id="medicineTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Available</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
    <a href="manage_medicine.php?action=toggle_availability&id=<?= $row['id'] ?>"
       class="btn btn-sm btn-<?= $row['is_available'] ? 'success' : 'secondary' ?>">
        <?= $row['is_available'] ? 'Available' : 'Unavailable' ?>
    </a>
</td>

                        <td>
                            <?php if ($row['image']) { ?>
                                <img src="../<?= $row['image'] ?>" width="50" height="50">
                            <?php } else {
                                echo 'N/A';
                            } ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
    <a href="update_medicine.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary mb-1">
        <i class="fa fa-edit"></i> Edit
    </a>
    <a href="manage_medicine.php?action=delete&id=<?= $row['id'] ?>" 
       class="btn btn-sm btn-danger mb-1" 
       onclick="return confirm('Are you sure you want to delete this medicine?');">
        <i class="fa fa-trash"></i> Delete
    </a>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>