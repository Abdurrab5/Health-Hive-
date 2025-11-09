<?php
require_once __DIR__ . '/../header.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$medicine_id = $_POST['medicine_id'];
$pharmacy_id = $_POST['pharmacy_id'];
$quantity = (int)$_POST['quantity'];

// Get medicine details
$stmt = $conn->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->bind_param("i", $medicine_id);
$stmt->execute();
$medicine = $stmt->get_result()->fetch_assoc();

$price = $medicine['price'];
$total_amount = $price * $quantity;

// Get user's accounts for card payment
$accounts = $conn->query("SELECT * FROM accounts WHERE user_id = $user_id AND account_type = 'user'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Confirm Your Order</h3>
    <form action="process_order.php" method="POST">
        <input type="hidden" name="medicine_id" value="<?= $medicine_id ?>">
        <input type="hidden" name="pharmacy_id" value="<?= $pharmacy_id ?>">
        <input type="hidden" name="quantity" value="<?= $quantity ?>">
        <input type="hidden" name="total_amount" value="<?= $total_amount ?>">

        <div class="mb-3">
            <label><strong>Medicine:</strong></label> <?= $medicine['name'] ?><br>
            <label><strong>Price per unit:</strong></label> Rs. <?= $price ?><br>
            <label><strong>Total:</strong></label> Rs. <?= $total_amount ?>
        </div>

        <div class="mb-3">
            <label>Delivery Address</label>
            <textarea name="delivery_address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" id="payment_method" required>
                <option value="cash">Cash</option>
                <option value="cod">Cash on Delivery</option>
                <option value="card">Card</option>
            </select>
        </div>

        <div class="mb-3" id="card_section" style="display:none;">
            <label>Select Card</label>
            <select name="card_number" class="form-control">
                <?php while ($acc = $accounts->fetch_assoc()): ?>
                    <option value="<?= $acc['card_number'] ?>">
                        <?= $acc['account_title'] ?> (<?= $acc['card_number'] ?> - Balance: Rs.<?= $acc['balance'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function () {
        document.getElementById('card_section').style.display = (this.value === 'card') ? 'block' : 'none';
    });
</script>




<?php
require_once __DIR__ . '/../footer.php';

?>