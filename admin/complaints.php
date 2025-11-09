<?php
require_once __DIR__ . '/../header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT c.*, u.name AS user_name FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'])) {
    $response = trim($_POST['response']);
    $status = $_POST['status'];
    $complaint_id = $_POST['complaint_id'];

    $stmt = $conn->prepare("UPDATE complaints SET response = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $response, $status, $complaint_id);
    $stmt->execute();
}
?>

<div class="container py-5">
    <h3 class="mb-4">Manage Complaints</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Response</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><span class="badge bg-<?= $row['status'] === 'resolved' ? 'success' : ($row['status'] === 'in progress' ? 'warning text-dark' : 'secondary') ?>"><?= ucfirst($row['status']) ?></span></td>
                        <td><?= nl2br(htmlspecialchars($row['response'] ?? '')) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-select form-select-sm mb-2">
                                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="in progress" <?= $row['status'] === 'in progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="resolved" <?= $row['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>
                                <textarea name="response" rows="2" class="form-control mb-2" placeholder="Admin response..."><?= htmlspecialchars($row['response']) ?></textarea>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
