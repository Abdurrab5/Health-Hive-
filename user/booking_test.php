<?php
require_once __DIR__ . '/../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$lab_test_id = $_POST['lab_test_id'] ?? null;
$lab_id = $_POST['lab_id'] ?? null;

// Fetch lab test details
$stmt = $conn->prepare("SELECT * FROM lab_tests WHERE id = ?");
$stmt->bind_param("i", $lab_test_id);
$stmt->execute();
$test = $stmt->get_result()->fetch_assoc();

// Check for existing user account info if payment method is card
$account = $conn->query("SELECT * FROM accounts WHERE user_id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_date'])) {
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $payment_method = $_POST['payment_method'];
    $payment_status = ($payment_method === 'cash') ? 'unpaid' : 'paid';

    // If card, ensure account info exists
    if ($payment_method === 'card') {
        if (!$account) {
            $_SESSION['error'] = 'Please update your card info in your profile.';
            header("Location: ../update_account.php");
            exit;
        }
    
        if ($account['balance'] < $test['price']) {
            echo "<div class='alert alert-danger'>Insufficient balance. Please recharge your card.</div>";
            exit;
        }
    
        // Deduct the price from user balance
        $new_balance = $account['balance'] - $test['price'];
        $update_stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE user_id = ?");
        $update_stmt->bind_param("di", $new_balance, $user_id);
        $update_stmt->execute();
    
        // Log the transaction
        $amount = $test['price'];
        $transaction_type = 'debit';
        $description = 'Lab Test Payment: ' . $test['test_name'];
        $recipient_id = $lab_id; // assuming payment goes to lab
    
        $trans_stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) VALUES (?, ?, ?, NOW(), ?, ?)");
        $trans_stmt->bind_param("iidss", $user_id, $recipient_id, $amount, $transaction_type, $description);
        $trans_stmt->execute();
    
        $payment_status = 'paid';
    }
    
    

    $stmt = $conn->prepare("INSERT INTO lab_test_requests (user_id, lab_test_id, lab_id, appointment_date, appointment_time, status, payment_status, payment_method, created_at) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, NOW())");
    $stmt->bind_param("iiissss", $user_id, $lab_test_id, $lab_id, $appointment_date, $appointment_time, $payment_status, $payment_method);
    $stmt->execute();

    if ($stmt->execute()) {
        header("Location: view_lab_tests.php?success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<div class="container mt-4">
    <h4>Book Lab Test: <?= htmlspecialchars($test['test_name']) ?></h4>
    <form method="post">
        <input type="hidden" name="lab_test_id" value="<?= $lab_test_id ?>">
        <input type="hidden" name="lab_id" value="<?= $lab_id ?>">

        <div class="mb-3">
            <label for="appointment_date" class="form-label">Appointment Date</label>
            <input type="date" name="appointment_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="appointment_time" class="form-label">Appointment Time</label>
            <input type="time" name="appointment_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required onchange="toggleCardWarning(this.value)">
                <option value="cash">Cash</option>
                <option value="card">Card</option>
            </select>
            <?php if (!$account) { ?>
                <small id="cardWarning" class="text-danger d-none">No card info found. Please <a href="account.php">update account info</a>.</small>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-primary">Confirm Booking</button>
    </form>
</div>

<script>
function toggleCardWarning(value) {
    const warning = document.getElementById('cardWarning');
    if (value === 'card') {
        warning.classList.remove('d-none');
    } else {
        warning.classList.add('d-none');
    }
}
</script>
<?php
require_once __DIR__ . '/../footer.php';

?>