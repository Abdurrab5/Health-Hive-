<?php
 
$page_title = "Registration Request";
require_once __DIR__ . '/../header.php';
 

 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE role IN ('user')");

?>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-info"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif; ?>

 
<h4>All Users</h4>
<div class="table-responsive">
<table id="usersTable" class="table table-bordered table-hover table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>Name</th>
            <th>Email</th>
           <th>Phone</th>
            <th>Address</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
             <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
             <td><?= ucfirst($row['role']) ?></td>
            <td>
                <span class="badge bg-<?= 
                    $row['status'] === 'approved' ? 'success' : 
                    ($row['status'] === 'pending' ? 'warning' : 
                    ($row['status'] === 'inactive' ? 'secondary' : 'danger')) ?>">
                    <?= ucfirst($row['status']) ?>
                </span>
            </td>
            <td><?= date('d-M-Y H:i', strtotime($row['created_at'])) ?></td>
            <td>
                <?php if ($row['status'] === 'pending'): ?>
                    <a href="approve_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mb-1">Approve</a>
                <?php endif; ?>

                <?php if ($row['status'] !== 'inactive'): ?>
                    <a href="deactivate_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Deactivate</a>
                <?php else: ?>
                    <a href="activate_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary mb-1">Activate</a>
                <?php endif; ?>

                <?php if ($row['status'] !== 'approved'): ?>
                    <a href="delete_user.php?id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-danger mb-1" 
                       onclick="return confirm('Are you sure you want to delete this user permanently?')">
                       Delete
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>
  $(document).ready(function () {
    $('#usersTable').DataTable({
      "pageLength": 10,
      "order": [[9, "desc"]],
      "language": {
        "search": "Search:"
      }
    });
  });
</script>

<?php
require_once __DIR__ . '/../footer.php';

?>