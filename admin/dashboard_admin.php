<?php
$page_title = "Admin Dashboard";
require_once __DIR__ . '/../header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

 
// Get counts for each role/status combo
 
// Query counts for labs and pharmacies
$pendingLabsCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'lab' AND status = 'pending'")->fetch_row()[0];
$approvedLabsCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'lab' AND status = 'approved'")->fetch_row()[0];
$inactiveLabsCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'lab' AND status = 'inactive'")->fetch_row()[0];

$pendingPharmaciesCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'pharmacy' AND status = 'pending'")->fetch_row()[0];
$approvedPharmaciesCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'pharmacy' AND status = 'approved'")->fetch_row()[0];
$inactivePharmaciesCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'pharmacy' AND status = 'inactive'")->fetch_row()[0];
 

// Example: today's registrations (optional enhancement)
$today = date('Y-m-d');
$todayRegistrations = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = '$today'")
                           ->fetch_assoc()['count'];
?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-info"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <!-- Cards for Pending/Approved/Inactive Users -->
    <div class="row">
        <!-- Pending Labs Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-hourglass-start"></i> Pending Labs
                    </h5>
                    <h4 class="card-text"><?= $pendingLabsCount ?></h4>
                    <span class="badge bg-warning"><?= $pendingLabsCount ?></span>
                </div>
            </div>
        </div>
        
        <!-- Approved Labs Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-check-circle"></i> Approved Labs
                    </h5>
                    <h4 class="card-text"><?= $approvedLabsCount ?></h4>
                    <span class="badge bg-success"><?= $approvedLabsCount ?></span>
                </div>
            </div>
        </div>
        
        <!-- Inactive Labs Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-secondary">
                <div class="card-body">
                    <h5 class="card-title text-secondary">
                        <i class="fas fa-ban"></i> Inactive Labs
                    </h5>
                    <h4 class="card-text"><?= $inactiveLabsCount ?></h4>
                    <span class="badge bg-secondary"><?= $inactiveLabsCount ?></span>
                </div>
            </div>
        </div>

        <!-- Pending Pharmacies Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-hourglass-start"></i> Pending Pharmacies
                    </h5>
                    <h4 class="card-text"><?= $pendingPharmaciesCount ?></h4>
                    <span class="badge bg-warning"><?= $pendingPharmaciesCount ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Add More Cards as Needed -->
</div>
<?php
require_once __DIR__ . '/../footer.php';

?>