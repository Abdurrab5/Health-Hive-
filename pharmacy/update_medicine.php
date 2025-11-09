<?php
$page_title = "Update Medicine";
require_once __DIR__ . '/../header.php';

$pharmacy_id = $_SESSION['user_id'] ?? 0;

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$id = $_GET['id'];

// Fetch existing medicine details
$stmt = $conn->prepare("SELECT * FROM medicines WHERE id = ? AND pharmacy_id = ?");
$stmt->bind_param("ii", $id, $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();
$medicine = $result->fetch_assoc();

if (!$medicine) {
    echo "Medicine not found or access denied.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $generic = $_POST['generic'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // Image handling
    $image = $_POST['existing_image']; // fallback
    if (!empty($_FILES['image']['name'])) {
        // 1) Determine the physical folder on disk
        $uploadDir = __DIR__ . '/../uploads/';             // e.g. /var/www/healthhive/uploads/
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        // 2) Build a unique filename
        $filename   = uniqid() . '_' . basename($_FILES['image']['name']);
        $savePath   = $uploadDir . $filename;              // Physical path
        $publicPath = 'uploads/' . $filename;              // What goes in the DB
    
        // 3) Move the uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $savePath)) {
            $image = $publicPath;  // Only the public-facing path
        } else {
            // Handle error
            echo "<div class=\"alert alert-danger\">Failed to upload image.</div>";
            exit;
        }
    }
    

    $stmt = $conn->prepare("UPDATE medicines SET name=?, brand=?, generic=?, category=?, price=?, discount=?, stock=?, description=?, image=?, is_available=? WHERE id=? AND pharmacy_id=?");
    $stmt->bind_param("ssssddissiii", $name, $brand, $generic, $category, $price, $discount, $stock, $description, $image, $is_available, $id, $pharmacy_id);

    if ($stmt->execute()) {
        header("Location: manage_medicine.php?msg=updated");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2>Update Medicine</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <input type="hidden" name="id" value="<?= $medicine['id'] ?>">
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($medicine['image']) ?>">

        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($medicine['name']) ?>">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control" required value="<?= htmlspecialchars($medicine['brand']) ?>">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Generic</label>
                <input type="text" name="generic" class="form-control" value="<?= htmlspecialchars($medicine['generic']) ?>">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" required value="<?= htmlspecialchars($medicine['category']) ?>">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label class="form-label">Price</label>
                <input type="number" name="price" step="0.01" class="form-control" required value="<?= $medicine['price'] ?>">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">Discount</label>
                <input type="number" name="discount" step="0.01" class="form-control" value="<?= $medicine['discount'] ?>">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" required value="<?= $medicine['stock'] ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($medicine['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Image</label><br>
            <?php if ($medicine['image']) { ?>
                <img src="<?= $medicine['image'] ?>" width="100" alt="Current Image"><br>
            <?php } ?>
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available"
                <?= $medicine['is_available'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_available">
                Available
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Update Medicine</button>
        <a href="manage_medicine.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>