<?php
$page_title = "Manage Medicine";
require_once __DIR__ . '/../header.php';
$user_id = $_SESSION['user_id'];
$account_type = $_SESSION['role'];
$pharmacy_id = $_SESSION['user_id'] ?? 1; // change accordingly

// Fetch medicines
$query = "SELECT * FROM medicines WHERE pharmacy_id = ? AND status = 1 ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
    <button class="btn btn-sm btn-<?= $row['is_available'] ? 'success' : 'secondary' ?>" onclick="toggleAvailability(<?= $row['id'] ?>)">
        <?= $row['is_available'] ? 'Available' : 'Unavailable' ?>
    </button>
</td>

                    <td>
                        <?php if ($row['image']) { ?>
                            <img src="<?= $row['image'] ?>" alt="Image" width="50" height="50">
                        <?php } else { echo 'N/A'; } ?>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="update_medicine.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary mb-1">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteMedicine(<?= $row['id'] ?>)">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    function toggleAvailability(id) {
    $.ajax({
        url: 'toggle_availability.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            // Just show the response text (message) in an alert box
            alert(response); // Ensure that `response` is plain text.
            location.reload(); // Reload the page after toggling the availability.
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors gracefully
            alert("An error occurred. Please try again.");
        }
    });
}

    $(document).ready(function () {
        $('#medicineTable').DataTable();
    });

    function deleteMedicine(id) {
        if (confirm('Are you sure you want to delete this medicine?')) {
            $.ajax({
                url: 'delete_medicine.php',
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    alert(response);
                    location.reload();
                }
            });
        }
       

    }
</script>
<?php
require_once __DIR__ . '/../footer.php';

?>