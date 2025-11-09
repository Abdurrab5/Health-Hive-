<?php
$page_title = "Deactivate";
require_once __DIR__ . '/../header.php';
 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}

$id = intval($_GET['id']);

$id = $_GET['id'];
 

// Inactive the user
$stmt = $conn->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Get user role to redirect accordingly
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