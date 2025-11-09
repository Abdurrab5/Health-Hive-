<?php
require_once __DIR__ . '/header.php';

// Fetch unique categories and brands for filter dropdowns
$categories = $conn->query("SELECT DISTINCT category FROM medicines WHERE status = '1'");
$brands = $conn->query("SELECT DISTINCT brand FROM medicines WHERE status = '1'");

// Handle search and filters
$where = "WHERE status = '1'";
$params = [];
$bindTypes = '';

if (!empty($_GET['search'])) {
    $where .= " AND name LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $bindTypes .= 's';
}
if (!empty($_GET['category'])) {
    $where .= " AND category = ?";
    $params[] = $_GET['category'];
    $bindTypes .= 's';
}
if (!empty($_GET['brand'])) {
    $where .= " AND brand = ?";
    $params[] = $_GET['brand'];
    $bindTypes .= 's';
}
if (!empty($_GET['min_price'])) {
    $where .= " AND price >= ?";
    $params[] = $_GET['min_price'];
    $bindTypes .= 'd';
}
if (!empty($_GET['max_price'])) {
    $where .= " AND price <= ?";
    $params[] = $_GET['max_price'];
    $bindTypes .= 'd';
}

$sql = "SELECT * FROM medicines $where";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($bindTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buy Medicines Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 text-center">Browse & Buy Medicines</h2>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['category'] ?>" <?= (($_GET['category'] ?? '') === $cat['category']) ? 'selected' : '' ?>><?= $cat['category'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="brand" class="form-select">
                <option value="">All Brands</option>
                <?php while ($b = $brands->fetch_assoc()): ?>
                    <option value="<?= $b['brand'] ?>" <?= (($_GET['brand'] ?? '') === $b['brand']) ? 'selected' : '' ?>><?= $b['brand'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="min_price" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="max_price" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['name'] ?></h5>
                            <p class="card-text small">
                                <strong>Category:</strong> <?= $row['category'] ?><br>
                                <strong>Brand:</strong> <?= $row['brand'] ?><br>
                                <strong>Price:</strong> Rs. <?= $row['price'] ?><br>
                            </p>
                            <form method="POST" action="user/confirm_order.php">
                                <input type="hidden" name="medicine_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="pharmacy_id" value="<?= $row['pharmacy_id'] ?>">
                                <div class="mb-2">
                                    <label>Qty:</label>
                                    <input type="number" name="quantity" value="1" min="1" class="form-control" required>
                                </div>
                                <?php if ($role === 'user'): ?>
                                    <button type="submit" class="btn btn-success w-100">Buy Now</button>
                                <?php elseif ($role === 'guest' || $role === 'GUEST'): ?>
                                    <a class="btn btn-primary w-100" href="<?= $baseURL ?>/login.php">Login To Buy</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No medicines found with the selected filters.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
</body>
</html>
