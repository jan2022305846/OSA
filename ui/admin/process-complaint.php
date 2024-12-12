<?php

require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/User.php';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);
$user = new UserQueries($db);

// Fetch complaint details
$complaintId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$complaintId) {
    echo "<p class='text-danger'>Error: Complaint ID is required.</p>";
    exit;
}

$currentComplaint = $complaint->getComplaintById($complaintId);

if (!$currentComplaint) {
    echo "<p class='text-danger'>Error: Complaint not found.</p>";
    exit;
}

// Get student ID from the complaint
$studentId = $currentComplaint['student_id'];

// Get student details using Student class
$student = $user->getUserByStudentId($studentId);

if (!$student) {
    echo "<p class='text-danger'>Error: Student not found.</p>";
    exit;
}

// Fetch current complaint note, status, and statuses list
$currentComplaint = $complaint->getComplaintById($complaintId);
$note = $complaint->getComplaintNote($complaintId);
$statuses = $complaint->getStatuses();
$documents = $complaint->getDocuments($complaintId);
$evidenceFiles = $complaint->getEvidenceFiles($complaintId);

// Debugging output
if (!$currentComplaint || !$statuses) {
    echo "<p class='text-danger'>Error: Unable to fetch complaint or statuses.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newNote = $_POST['note'] ?? '';
    $newStatus = intval($_POST['status']);
    $adminId = $_SESSION['admin_id']; // Assuming admin_id is stored in the session

    // Update note and status in the database
    if ($complaint->processComplaint($complaintId, $newNote, $newStatus, $adminId)) {
        echo "<p class='text-success'>Complaint updated successfully.</p>";
    } else {
        echo "<p class='text-danger'>Failed to update complaint.</p>";
    }

    // Refresh current data
    $note = $complaint->getComplaintNote($complaintId);
    $currentComplaint['status_id'] = $newStatus;
    $documents = $complaint->getDocuments($complaintId);
    $evidenceFiles = $complaint->getEvidenceFiles($complaintId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Complaint Management System</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center img-logo">
            <img src="../../assets/images/osa.png" alt="OSA" class="navbar-brand img-fluid" width="50">
            <img src="../../assets/images/LOGOPNG.png" alt="Logo" class="navbar-brand img-fluid" width="50">
            <h2 class="text-white mb-0 ms-3">Admin Dashboard</h2>
        </div>
        <a href="../../index.php" class="btn btn-outline-danger logout">Logout</a>
    </header>

    <div class="d-flex flex-grow-1"> 
        <!-- Sidebar -->
        <aside class="sidebar col-md-3 col-lg-2">
            <div class="profile mb-3">
                <img src="../../assets/images/osa.png"  alt="Admin Profile Picture" class="profile-img mb-2">
                <h4 class="text-primary">Admin</h4>
            </div>
            <nav class="nav flex-column">
                <a href="#" data-section="dashboard" class="nav-link">Dashboard</a>
                <a href="#" data-section="manage-complaint" class="nav-link  active">Manage Complaints</a>
                <a href="#" data-section="account-settings" class="nav-link">Account Settings</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main id="main-content" class="p-3 flex-grow-1">
        <h3>Process Complaint</h3>
            <form method="post">
                <div class="row mb-3">
                    <div class="col">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" value="<?= htmlspecialchars($student['student_id']); ?>" readonly>
                    </div>
                    <div class="col">
                        <label for="student_name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="student_name" value="<?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Update Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status['status_id'] ?>" <?= $status['status_id'] == $currentComplaint['status_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['status_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Leave a Note:</label>
                    <textarea class="form-control" id="note" name="note" rows="5"><?= htmlspecialchars($note['note'] ?? '') ?></textarea>
                </div>

                <!-- Display documents -->
                <?php if (!empty($documents)): ?>
                    <div class="mb-3">
                        <label class="form-label">Documents:</label>
                        <ul>
                            <?php foreach ($documents as $doc): ?>
                                <li><a href="<?= htmlspecialchars($doc) ?>" target="_blank"><?= htmlspecialchars(basename($doc)) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Display evidence files -->
                <?php if (!empty($evidenceFiles)): ?>
                    <div class="mb-3">
                        <label class="form-label">Evidence Files:</label>
                        <ul>
                            <?php foreach ($evidenceFiles as $evidence): ?>
                                <li><a href="<?= htmlspecialchars($evidence) ?>" target="_blank"><?= htmlspecialchars(basename($evidence)) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="admin-dashboard.php" class="btn btn-secondary">Back</a>
            </form>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Student Affairs Complaint System. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
