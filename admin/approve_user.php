<?php
$page_title = "Approve";
require_once __DIR__ . '/../header.php';

// Validate the `id` parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$id = intval($_GET['id']); // Sanitize ID

// Approve the user using prepared statement
$stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Now fetch the role of the user
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['role'] === "user") {
        header("Location: users.php");
    } else {
        header("Location: registration_request.php");
    }
    exit;
} else {
    echo "User not found.";
}
