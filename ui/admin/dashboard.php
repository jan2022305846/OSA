<?php
// Include database configuration and Complaint model
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

// Fetch complaint stats
$stats = $complaint->getAllComplaints();
?>

<section class="container my-4">
    <div class="row g-4">
        <?php foreach ($stats as $stat): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="../../assets/images/<?= strtolower($stat['department']) ?>_logo.jpg" 
                                    alt="<?= $stat['department'] ?> logo" 
                                    class="rounded-circle" 
                                    width="80" 
                                    height="80">
                        </div>
                        <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($stat['department']) ?></h5>
                        <div class="mt-3">
                            <h6 class="text-muted">Pending Complaints</h6>
                            <p class="fs-4 fw-bold text-danger"><?= htmlspecialchars($stat['pending_complaints']) ?></p>
                        </div>
                        <div class="mt-2">
                            <h6 class="text-muted">In Progress Complaints</h6>
                            <p class="fs-4 fw-bold text-warning"><?= htmlspecialchars($stat['inprogress_complaints']) ?></p>
                        </div>
                        <div class="mt-2">
                            <h6 class="text-muted">Resolved Complaints</h6>
                            <p class="fs-4 fw-bold text-success"><?= htmlspecialchars($stat['resolved_complaints']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
