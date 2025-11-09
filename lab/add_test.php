<?php
require_once __DIR__ . '/../header.php';
$lab_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name   = $_POST['test_name'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $image_path  = null;

    // Handle image upload
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

    // Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO lab_tests (lab_id, test_name,image, description, price)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssd", $lab_id, $test_name, $image_path, $description, $price);
    $stmt->execute();

    echo "<div class='alert alert-success'>Test added successfully.</div>";
}
?>

<div class="container mt-4">
    <h3>Add New Lab Test</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Test Name</label>
            <input type="text" name="test_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Test Image (optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Add Test</button>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>