<?php
require_once __DIR__ . '/../header.php';
$lab_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM lab_tests WHERE lab_id = $lab_id");
?>

<div class="container mt-4">
    <h3>My Lab Tests</h3>
    <a href="add_test.php" class="btn btn-success mb-3">Add New Test</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td>
                            <?php if ($row['image']) { ?>
                                <img src="../<?= $row['image'] ?>" width="50" height="50">
                            <?php } else {
                                echo 'N/A';
                            } ?>
                        </td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>Rs.<?= $row['price'] ?></td>
                <td>
                    <a href="edit_test.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_test.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this test?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>