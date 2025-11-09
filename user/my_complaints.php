<?php
require_once __DIR__ . '/../header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
    <h3 class="mb-4 text-center">My Complaints</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Admin Response</th>
                        <th>Submitted At</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['subject']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                            <td><span class="badge bg-<?= $row['status'] === 'resolved' ? 'success' : ($row['status'] === 'in progress' ? 'warning text-dark' : 'secondary') ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td><?= $row['response'] ? nl2br(htmlspecialchars($row['response'])) : '<span class="text-muted">Awaiting Response</span>' ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['updated_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have not submitted any complaints yet.</div>
    <?php endif; ?>
</div>
