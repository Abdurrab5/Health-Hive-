<?php
 
require_once __DIR__ . '/../header.php';

$id = $_GET['id'];
$status = $_GET['status'];

// Allow only valid status transitions
$valid_statuses = ['pending', 'completed', 'failed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    die("Invalid status.");
}

$sql = "UPDATE medicine_orders SET status = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: orders.php?msg=Status Updated");
exit;
