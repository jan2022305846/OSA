<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

// Fetch complaint history for the logged-in student
$studentId = $_SESSION['student_id'];
try {
    $complaints = $complaint->getComplaintHistory($studentId);
} catch (Exception $e) {
    echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    $complaints = [];
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_complaint_id'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch.');
    }
    try {
        $complaintId = $_POST['delete_complaint_id'];
        $isDeleted = $complaint->deleteComplaint($complaintId);

        if ($isDeleted) {
            $_SESSION['success'] = 'Complaint deleted successfully.';
            echo "<script>
                alert('Complaint successfully deleted.');
                window.location.href = 'userdashboard.php';
                </script>";
        } else {
            $_SESSION['error'] = 'Failed to delete complaint.';
        }
        exit;
    } catch (Exception $e) {
        echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Helper function for status badge colors
function getStatusColor($status) {
    $colors = [
        'Pending' => 'warning',
        'In Progress' => 'primary',
        'Resolved' => 'success',
    ];
    return isset($colors[$status]) ? $colors[$status] : 'secondary';
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="complaint-history">
    <h3>Complaint History</h3>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($complaints)): ?>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?= htmlspecialchars($complaint['id']) ?></td>
                        <td><?= htmlspecialchars($complaint['type']) ?></td>
                        <td><?= htmlspecialchars($complaint['description']) ?></td>
                        <td>
                            <span class="badge bg-<?= getStatusColor($complaint['status']) ?>">
                                <?= htmlspecialchars($complaint['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($complaint['status'] === 'Pending'): ?>
                                <a href="update-complaint.php?id=<?= $complaint['id'] ?>" class="btn btn-warning btn-sm">Update</a>
                                <form action="complaint-history.php" method="POST" class="d-inline">
                                    <input type="hidden" name="delete_complaint_id" value="<?= $complaint['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this complaint?')">Delete</button>
                                </form>
                            <?php elseif (in_array($complaint['status'], ['In Progress', 'Resolved'])): ?>
                                <a href="view-complaint.php?id=<?= $complaint['id'] ?>" class="btn btn-primary btn-sm">View</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No complaints found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>