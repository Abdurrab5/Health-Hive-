<?php
require_once __DIR__ . '/../header.php';

$id = $_POST['id'];
$date = $_POST['appointment_date'];
$time = $_POST['appointment_time'];

$stmt = $conn->prepare("UPDATE lab_test_requests SET status='approved', appointment_date=?, appointment_time=? WHERE id=?");
$stmt->bind_param("ssi", $date, $time, $id);
$stmt->execute();

header("Location: manage_lab_requests.php");

