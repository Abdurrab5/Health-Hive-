<?php
require_once __DIR__ . '/../header.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete user from DB
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['msg'] = "User deleted permanently.";
    } else {
        $_SESSION['msg'] = "Failed to delete user.";
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM users  WHERE id=$id");
while($row = $result->fetch_assoc()):
    if($row['role']=="user"){
        header("Location: users.php");
    }else{
        header("Location: registration_request.php");
    }
exit();
