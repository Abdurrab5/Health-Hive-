<?php 
require_once __DIR__ . '/../header.php';
$lab_id = $_SESSION['user_id'];

$sql = "SELECT ltr.*, lt.test_name, u.name AS user_name 
        FROM lab_test_requests ltr
        JOIN lab_tests lt ON ltr.lab_test_id = lt.id
        JOIN users u ON ltr.user_id = u.id
        WHERE ltr.lab_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lab_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="mb-4">Lab Test Requests</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Test</th>
                    <th>Status</th>
                    <th>Appointment</th>
                    <th class="text-center">Actions</th>
                    <th class="text-center">Upload Report</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()) { 
                // Check if report exists
                $report_check = $conn->prepare("SELECT id FROM lab_test_reports WHERE test_request_id = ?");
                $report_check->bind_param("i", $row['id']);
                $report_check->execute();
                $report_result = $report_check->get_result();
                $report_uploaded = $report_result->num_rows > 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['test_name']) ?></td>
                    <td>
                        <span class="badge 
                            <?= 
                                $row['status'] == 'pending' ? 'bg-warning text-dark' :
                                ($row['status'] == 'approved' ? 'bg-info text-dark' :
                                ($row['status'] == 'rejected' ? 'bg-danger' : 'bg-secondary'))
                            ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?= $row['appointment_date'] ? 
                            "<strong>" . $row['appointment_date'] . "</strong> at <strong>" . $row['appointment_time'] . "</strong>" : 
                            '<span class="text-muted">Not Scheduled</span>' ?>
                    </td>
                    <td class="text-center ">
                        <?php if ($row['status'] == 'pending') { ?>
                            <form action="approve_lab_request.php" method="post" class="d-inline-flex gap-2 flex-wrap align-items-center">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="date" name="appointment_date" class="form-control form-control-sm" required>
                                <input type="time" name="appointment_time" class="form-control form-control-sm" required>
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <a href="reject_lab_request.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mt-2">Reject</a>
                        <?php } else {
                            echo '<span class="text-muted">No Actions</span>';
                        } ?>
                    </td>
                    <td class="text-center">
                        <?php if ($row['status'] == 'approved' && !$report_uploaded) { ?>
                            <form method="POST" enctype="multipart/form-data" action="upload_report.php" class="d-flex flex-column gap-1 align-items-center">
                                <input type="hidden" name="test_request_id" value="<?= $row['id'] ?>">
                                <input type="file" name="report" accept="application/pdf" class="form-control form-control-sm" required>
                                <button type="submit" name="upload" class="btn btn-primary btn-sm">Upload</button>
                            </form>
                        <?php } elseif ($report_uploaded) {
                            echo "<span class='text-success fw-bold'>Uploaded</span>";
                        } else {
                            echo '<span class="text-muted">Unavailable</span>';
                        } ?>
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