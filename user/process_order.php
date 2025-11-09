<?php
require_once __DIR__ . '/../header.php';

$user_id = $_SESSION['user_id'];
$medicine_id = $_POST['medicine_id'];
$pharmacy_id = $_POST['pharmacy_id'];
$quantity = (int)$_POST['quantity'];
$total_amount = (float)$_POST['total_amount'];
$delivery_address = $_POST['delivery_address'];
$payment_method = $_POST['payment_method'];
$card_number = $_POST['card_number'] ?? null;

$status = 'pending';
$order_date = date('Y-m-d H:i:s');
$payment_status = ($payment_method === 'card') ? 'completed' : 'pending';

$transaction_id = null;

if ($payment_method === 'card') {
    // Get user, pharmacy, admin accounts
    $user_acc = $conn->query("SELECT * FROM accounts WHERE user_id = $user_id AND card_number = '$card_number'")->fetch_assoc();
    $pharmacy_acc = $conn->query("SELECT * FROM accounts WHERE user_id = $pharmacy_id AND account_type = 'pharmacy'")->fetch_assoc();
    $admin_acc = $conn->query("SELECT * FROM accounts WHERE user_id = 1 AND account_type = 'admin'")->fetch_assoc();

    if (!$user_acc || !$pharmacy_acc || !$admin_acc) {
        die("Account information missing.");
    }

    if ($user_acc['balance'] >= $total_amount) {
        $admin_cut = round($total_amount * 0.10, 2);
        $pharmacy_cut = round($total_amount - $admin_cut, 2);

        // Update balances
        $conn->query("UPDATE accounts SET balance = balance - $total_amount WHERE id = {$user_acc['id']}");
        $conn->query("UPDATE accounts SET balance = balance + $pharmacy_cut WHERE id = {$pharmacy_acc['id']}");
        $conn->query("UPDATE accounts SET balance = balance + $admin_cut WHERE id = {$admin_acc['id']}");

        // Record main transaction
        $txn_stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) VALUES (?, ?, ?, NOW(), ?, ?)");
        $txn_type = 'card';
        $note = 'Medicine Order Payment';
        $txn_stmt->bind_param("iisss", $user_id, $pharmacy_id, $total_amount, $txn_type, $note);
        $txn_stmt->execute();
        $transaction_id = $conn->insert_id;

        // Optional: log admin commission as separate transaction
// Optional: log admin commission as separate transaction
$admin_note = "Admin 10% Commission for Order";
$txn2 = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) VALUES (?, ?, ?, NOW(), ?, ?)");
$txn2->bind_param("iisss", $user_id, $admin_acc['user_id'], $admin_cut, $txn_type, $admin_note);
$txn2->execute();

    } else {
        die("Insufficient balance.");
    }
}

// Insert order into medicine_orders

$stmt = $conn->prepare("INSERT INTO medicine_orders (
     pharmacy_id, user_id, medicine_id, quantity, total_amount, 
    status, order_date, delivery_address, payment_method, 
    payment_status, transaction_id, created_at
) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param(
    "iiiidsssssi",
    $pharmacy_id,
    $user_id,
    $medicine_id,
    $quantity,
    $total_amount,
    $status,
    $order_date,
    $delivery_address,
    $payment_method,
    $payment_status,
    $transaction_id
);
$stmt->execute();

header("Location: medicine_orders.php?success=1");
exit;
