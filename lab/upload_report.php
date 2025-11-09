<?php
require_once __DIR__ . '/../header.php';

if (isset($_POST['upload'])) {
    $request_id = (int) $_POST['test_request_id'];
    $file = $_FILES['report'];

    if ($file['type'] !== 'application/pdf') {
        echo "<div class='alert alert-danger'>Only PDF files allowed.</div>";
        exit;
    }

    $file_name = 'report_' . $request_id . '_' . time() . '.pdf';
    $upload_dir = 'uploads/reports/';
    $file_path = $upload_dir . $file_name;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $stmt = $conn->prepare("INSERT INTO lab_test_reports (test_request_id, report_file_path) VALUES (?, ?)");
        $stmt->bind_param("is", $request_id, $file_path);
        $stmt->execute();

        $conn->query("UPDATE lab_test_requests SET status='completed' WHERE id=$request_id");

        echo "<div class='alert alert-success'>Report uploaded successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Upload failed.</div>";
    }
}
?>
