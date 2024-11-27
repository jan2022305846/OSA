<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}

// Include database configuration and Complaint model
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

// Fetch complaint stats
$stats = $complaint->getComplaintStats($_SESSION['student_id']);

// Define the statuses to always display
$statuses = [
    'Pending' => 0,
    'In progress' => 0,
    'Resolved' => 0
];

// Merge the fetched stats with the predefined statuses
$stats = array_merge($statuses, $stats);

// Normalize case to prevent mismatches
$stats = array_change_key_case($stats, CASE_LOWER);
$statuses = array_change_key_case($statuses, CASE_LOWER);
?>
<section class="row my-4 g-4">
    <?php foreach ($statuses as $status => $defaultCount): ?>
        <div class="col-md-4">
            <div class="stat-box text-center">
                <h5 class="mb-3"><?= ucfirst(str_replace('_', ' ', $status)) ?> Complaints</h5>
                <p class="display-6 mb-0"><?= htmlspecialchars($stats[$status] ?? 0) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</section>
