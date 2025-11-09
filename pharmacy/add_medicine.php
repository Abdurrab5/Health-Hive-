<?php
$page_title = "Add Medicine";
require_once __DIR__ . '/../header.php';


$user_id = $_SESSION['user_id'];
$account_type = $_SESSION['role'];
$pharmacy_id = $_SESSION['user_id'] ?? 1; // change accordingly

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

    // Handle image upload
    $image = '';
     
  /*  if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $image = $target_dir . $filename;
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
   } */
    if (!empty($_FILES['image']['name'])) {
    $upload_folder = "../uploads/";
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }

    $filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
    $save_path = $upload_folder . $filename; // This is the physical location
    $db_path = "uploads/" . $filename;       // This is what will be saved in the DB

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $save_path)) {
        // Store $db_path in your database
        $image = $db_path;
    }
}


    $stmt = $conn->prepare("INSERT INTO medicines (pharmacy_id, name, brand, generic, category, price, discount, stock, description, image, is_available) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssddisss", $pharmacy_id, $name, $brand, $generic, $category, $price, $discount, $stock, $description, $image, $is_available);

    if ($stmt->execute()) {
        header("Location: manage_medicine.php?success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Add New Medicine</h2>
    <form method="POST" enctype="multipart/form-data">
        
        <div class="form-group mb-2">
            <label>Medicine Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Generic</label>
            <input type="text" name="generic" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Category</label>
            <input type="text" name="category" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Discount (%)</label>
            <input type="number" step="0.01" name="discount" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="image" class="form-control-file">
        </div>
        <div class="form-check mb-2">
            <input type="checkbox" name="is_available" class="form-check-input" id="availableCheck">
            <label for="availableCheck" class="form-check-label">Available</label>
        </div>
        <button type="submit" class="btn btn-primary">Add Medicine</button>
    </form>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>