<?php
$page_title = "Account";
require_once __DIR__ . '/../header.php';

$user_id = $_SESSION['user_id'];
$account_type = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_account'])) {
        $account_title = $_POST['account_title'];
        $account_number = $_POST['account_number'];
        $card_number = $_POST['card_number'];
        $cvv = $_POST['cvv'];

        $stmt = $conn->prepare("INSERT INTO accounts (user_id, account_title, account_number, card_number, cvv, account_type) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $account_title, $account_number, $card_number, $cvv, $account_type);
        $stmt->execute();
        echo "<div class='alert alert-success'>Account details added successfully!</div>";
    }

    if (isset($_POST['add_balance'])) {
        $balance = $_POST['balance'];
        $stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $balance, $user_id);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_type, description) 
                                VALUES (?, ?, ?, 'credit', 'Added balance')");
        $stmt->bind_param("iid", $user_id, $user_id, $balance);
        $stmt->execute();
        echo "<div class='alert alert-success'>Balance added successfully!</div>";
    }
}

$stmt = $conn->prepare("SELECT * FROM accounts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();
?>
 

<div class="container py-5">
    <h2 class="mb-4 text-center">Account Management</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Account Details</div>
        <div class="card-body">
            <?php if ($account): ?>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Account Title:</strong> <?= htmlspecialchars($account['account_title']) ?></li>
                    <li class="list-group-item"><strong>Account Number:</strong> <?= htmlspecialchars($account['account_number']) ?></li>
                    <li class="list-group-item"><strong>Card Number:</strong> <?= htmlspecialchars($account['card_number']) ?></li>
                    <li class="list-group-item"><strong>Balance:</strong> RS<?= number_format($account['balance'], 2) ?></li>
                </ul>
            <?php else: ?>
                <p class="text-muted">No account details found. Please add your account information below.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Account Form -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Add Account Information</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="account_title" class="form-label">Account Title</label>
                    <input type="text" name="account_title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="text" name="account_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" name="card_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="csv" class="form-label">CVV (Card Verification Value)</label>
                    <input type="text" name="cvv" class="form-control" required>
                </div>

                <button type="submit" name="add_account" class="btn btn-success">Add Account</button>
            </form>
        </div>
    </div>

    <!-- Add Balance Form -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Add Balance</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="balance" class="form-label">Amount to Add</label>
                    <input type="number" name="balance" class="form-control" min="1" required>
                </div>
                <button type="submit" name="add_balance" class="btn btn-info text-white">Add Balance</button>
            </form>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Transaction History</div>
        <div class="card-body">
            <?php
            $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0): ?>
                <div class="list-group">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="list-group-item">
                            <strong><?= $row['transaction_date'] ?></strong><br>
                            Amount: RS<?= number_format($row['amount'], 2) ?><br>
                            Type: <?= ucfirst($row['transaction_type']) ?><br>
                            Description: <?= htmlspecialchars($row['description']) ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No transaction history available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>
