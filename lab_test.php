<?php
require_once __DIR__ . '/header.php';

// Get all available lab tests
$sql = "SELECT lt.*, u.store_name AS lab_name 
        FROM lab_tests lt 
        JOIN users u ON lt.lab_id = u.id 
        WHERE u.role = 'lab'";
$result = $conn->query($sql);
?>

<div class="container py-5">
  <h3 class="mb-4 text-center">Browse Lab Tests</h3>
  <div class="row g-4">
    <?php while($row = $result->fetch_assoc()) { ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <!-- Optional placeholder image -->
          
                            
                 
                    <img src="<?= $row['image'] ?>" 
               class="card-img-top" 
               alt="<?= htmlspecialchars($row['test_name']) ?>" 
               style="height:180px; object-fit:cover;">

          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($row['test_name']) ?></h5>
            <p class="card-text text-truncate"><?= htmlspecialchars($row['description']) ?></p>
            <p class="mt-auto mb-2">
              <span class="badge bg-info"><?= htmlspecialchars($row['lab_name']) ?></span>
              <span class="fw-bold float-end">Rs. <?= number_format($row['price'],2) ?></span>
            </p>
            <form method="post" action="user/booking_test.php">
              <input type="hidden" name="lab_test_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="lab_id" value="<?= $row['lab_id'] ?>">
              <?php if ($role === 'admin'): ?>
           

          <?php elseif ($role === 'lab'): ?>
        
   <?php elseif ($role === 'pharmacy'): ?>

    <?php elseif ($role === 'user'): ?>
         
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-calendar-plus-fill me-1"></i> Book Test
              </button>
              <?php endif; ?>
        <?php if($role == 'guest' OR $role == 'GUEST'): ?>
 <a class="btn btn-primary w-100" href="<?= $baseURL ?>/login.php">Login To Get</a> 
          <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<?php require_once 'footer.php'; ?>
