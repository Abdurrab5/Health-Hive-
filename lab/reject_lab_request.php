<?php
require_once __DIR__ . '/../header.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE lab_test_requests SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' for integer

    if ($stmt->execute()) {
        header("Location: manage_lab_requests.php?success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>Invalid request.</div>";
}
