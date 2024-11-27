<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}

$firstName = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'Student';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

// Fetch the complaint note based on the complaint ID
$complaintId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$complaintId) {
    echo "<p class='text-danger'>Error: Complaint ID is required.</p>";
    exit;
}

$note = $complaint->getComplaintNote($complaintId);

if (!$note) {
    echo "<p class='text-warning'>No note available for this complaint.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - Complaint Management System</title>
    <link rel="icon" href="../../assets/images/osa.png">
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/user.css">
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center img-logo">
            <img src="../../assets/images/osa.png" alt="Office of Student Affairs Logo" class="navbar-brand img-fluid" width="50">
            <img src="../../assets/images/LOGOPNG.png" alt="University Logo" class="navbar-brand img-fluid" width="50">
            <h2 class="text-white mb-0 ms-3">Student Dashboard</h2>
        </div>
        <a href="../../index.php" class="btn btn-outline-danger logout">Logout</a>
    </header>

    <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
        <aside class="sidebar col-md-3 col-lg-2">
            <div class="profile">
                <img src="../../assets/images/ustp.png" alt="Student Profile Picture" class="profile-img mb-1">
                <h4 class="studentname">Welcome <?= $firstName ?></h4>
            </div>
            <nav class="nav flex-column">
                <a href="#" data-section="dashboard" class="nav-link">Dashboard</a>
                <a href="#" data-section="submit-complaint" class="nav-link">Submit Complaint</a>
                <a href="#" data-section="complaint-history" class="nav-link active">Complaint History</a>
                <a href="#" data-section="account-settings" class="nav-link">Account Settings</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main id="main-content" class="p-3 flex-grow-1">
          <div class="view-complaint">
              <h3>View Complaint Note</h3>
              <div class="mb-3">
                  <label for="note" class="form-label">Admin Note:</label>
                  <textarea class="form-control" id="note" name="note" rows="10" readonly><?= htmlspecialchars($note['note']) ?></textarea>
              </div>
              <a href="userdashboard.php" class="btn btn-secondary">Go Back</a>
          </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-center py-3">
        <p>&copy; 2024 OSA Online Complaints. All rights reserved.</p>
    </footer>
    
    <!-- Scripts -->
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
