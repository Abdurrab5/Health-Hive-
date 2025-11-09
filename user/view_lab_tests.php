<?php
require_once __DIR__ . '/../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT r.*, lt.test_name, lt.description, lt.image, u.store_name AS lab_name, 
        (SELECT report_file_path FROM lab_test_reports WHERE test_request_id = r.id LIMIT 1) AS result_file
        FROM lab_test_requests r 
        JOIN lab_tests lt ON r.lab_test_id = lt.id 
        JOIN users u ON r.lab_id = u.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
    <h2 class="mb-4 text-center">My Lab Test Requests</h2>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Test</th>
                    <th>Lab</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= htmlspecialchars($row['test_name']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($row['description']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($row['lab_name']) ?></td>
                    <td>
                        <?= $row['appointment_date'] 
                            ? "<span class='fw-semibold'>" . $row['appointment_date'] . "</span><br><span class='text-muted'>" . $row['appointment_time'] . "</span>" 
                            : "<span class='text-muted'>Not Scheduled</span>" ?>
                    </td>
                    <td class="text-center">
                        <span class="badge 
                            <?= $row['status'] === 'pending' ? 'bg-warning text-dark' : 
                                ($row['status'] === 'approved' ? 'bg-info text-dark' :
                                ($row['status'] === 'completed' ? 'bg-success' : 
                                ($row['status'] === 'rejected' ? 'bg-danger' : 'bg-secondary'))) ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?= $row['payment_method'] 
                            ? "<strong>" . htmlspecialchars($row['payment_method']) . "</strong> <span class='text-muted'>(" . $row['payment_status'] . ")</span>"
                            : "<span class='text-muted'>N/A</span>" ?>
                    </td>
                    <td class="text-center">
                        <?php if ($row['status'] === 'completed' && !empty($row['result_file'])): ?>
                            <a href="../<?= $row['result_file'] ?>" class="btn btn-success btn-sm" download>
                                <i class="bi bi-download"></i> Download
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Not Available</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>