<?php
// edit_test.php
require_once __DIR__ . '/../header.php';
$lab_id = $_SESSION['user_id'];

// Validate and fetch test
if (!isset($_GET['id'])) {
    header('Location: view_tests.php');
    exit;
}
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM lab_tests WHERE id = ? AND lab_id = ?");
$stmt->bind_param("ii", $id, $lab_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Test not found.</div>";
    exit;
}
$test = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_name   = $_POST['test_name'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $image_path  = $test['image']; // keep old by default

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir  = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filename   = uniqid() . '_' . basename($_FILES['image']['name']);
        $savePath   = $uploadDir . $filename;
        $publicPath = 'uploads/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $savePath)) {
            $image_path = $publicPath;
        } else {
            echo "<div class='alert alert-danger'>Image upload failed.</div>";
        }
    }

    // Update record
    $update = $conn->prepare(
        "UPDATE lab_tests SET test_name=?, description=?, price=?, image=? WHERE id=? AND lab_id=?"
    );
    $update->bind_param("ssdsii", $test_name, $description, $price, $image_path, $id, $lab_id);
    $update->execute();

    header('Location: view_tests.php?updated=1');
    exit;
}
?>

<div class="container mt-4">
    <h3>Edit Lab Test</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Test Name</label>
            <input type="text" name="test_name" class="form-control" value="<?= htmlspecialchars($test['test_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($test['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($test['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if ($test['image']): ?>
                <img src="<?= htmlspecialchars($test['image']) ?>" alt="Test Image" width="120" class="rounded mb-2">
            <?php else: ?>
                <p class="text-muted">No image uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload New Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Test</button>
        <a href="view_tests.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>