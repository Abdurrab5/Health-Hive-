<?php


function processLabPayment($conn, $request_id, $payment_method) {
  
    
    
     // Fetch request, user, lab, test price
    $sql = "SELECT ltr.*, lt.price, lt.lab_id 
            FROM lab_test_requests ltr 
            JOIN lab_tests lt ON ltr.lab_test_id = lt.id 
            WHERE ltr.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $user_id = $result['user_id'];
    $lab_id = $result['lab_id'];
    $price = $result['price'];

    // Split amounts
    $admin_cut = round($price * 0.10, 2);
    $lab_cut = round($price - $admin_cut, 2);

    // 1. Update test request payment status
    $stmt = $conn->prepare("UPDATE lab_test_requests SET payment_status='paid', payment_method=? WHERE id=?");
    $stmt->bind_param("si", $payment_method, $request_id);
    $stmt->execute();

    // 2. Add balance to lab
    $conn->query("UPDATE accounts SET balance = balance + $lab_cut WHERE user_id = $lab_id");

    // 3. Add balance to admin
    $conn->query("UPDATE accounts SET balance = balance + $admin_cut WHERE user_id = 1"); // admin user_id = 1

    // 4. Record admin earning
//$stmt = $conn->prepare("INSERT INTO admin_earnings (source, source_id, amount) VALUES ('lab_test', ?, ?)");
   // $stmt->bind_param("id", $request_id, $admin_cut);
//$stmt->execute();

    // 5. Log transactions
    // Lab
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) 
                            VALUES (?, ?, ?, NOW(), 'credit', 'Lab test payment received')");
    $stmt->bind_param("iid", $user_id, $lab_id, $lab_cut);
    $stmt->execute();

    // Admin
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) 
                            VALUES (?, ?, ?, NOW(), 'commission', 'Admin commission from lab test')");
    $stmt->bind_param("iid", $user_id, $admin_id = 1, $admin_cut);
    $stmt->execute();
}
function reverseLabPayment($conn, $request_id) {
    // Fetch payment and amounts
    $sql = "SELECT ltr.*, lt.price, lt.lab_id 
            FROM lab_test_requests ltr 
            JOIN lab_tests lt ON ltr.lab_test_id = lt.id 
            WHERE ltr.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $user_id = $result['user_id'];
    $lab_id = $result['lab_id'];
    $price = $result['price'];
    $admin_cut = round($price * 0.10, 2);
    $lab_cut = round($price - $admin_cut, 2);

    // 1. Mark test as unpaid again
    $conn->query("UPDATE lab_test_requests SET payment_status='unpaid' WHERE id = $request_id");

    // 2. Deduct lab amount
    $conn->query("UPDATE accounts SET balance = balance - $lab_cut WHERE user_id = $lab_id");

    // 3. Deduct admin amount
    $conn->query("UPDATE accounts SET balance = balance - $admin_cut WHERE user_id = 1");

    // 4. Record refund transactions
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) 
                            VALUES (?, ?, ?, NOW(), 'debit', 'Lab test refund - Lab')");
    $stmt->bind_param("iid", $lab_id, $user_id, $lab_cut);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, recipient_id, amount, transaction_date, transaction_type, description) 
                            VALUES (?, ?, ?, NOW(), 'debit', 'Lab test refund - Admin')");
    $stmt->bind_param("iid", $admin_id = 1, $user_id, $admin_cut);
    $stmt->execute();
}
