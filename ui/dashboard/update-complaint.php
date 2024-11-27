<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}
$firstName = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'Student';

// Include database configuration and Complaint model
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

$message = '';
$messageType = '';

// Check if there is an 'id' in the URL for updating a complaint
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $complaintId = $_GET['id'];

    // Fetch the complaint details by its ID
    $currentComplaint = $complaint->getComplaintById($complaintId);
    if (!$currentComplaint) {
        echo "<p class='text-danger'>Complaint not found.</p>";
        exit;
    }
} else {
    echo "<p class='text-danger'>Complaint ID not provided.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if (empty($type) || empty($description)) {
        $message = "All fields are required.";
        $messageType = "danger";
    } else {
        $studentId = $_SESSION['student_id'];

        try {
            // Call the updateComplaint method
            $result = $complaint->updateComplaint($complaintId, $studentId, $type, $description);

            if ($result) {
                $message = "Complaint updated successfully.";
                $messageType = "success";
                echo "<script>
                    alert('$message');
                    window.location.href = 'userdashboard.php';
                </script>";
                exit;
            } else {
                $message = "Failed to update complaint. Please try again.";
                $messageType = "danger";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Complaint Management System</title>
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
          <div class="update-complaint">
              <h3>Update Complaint</h3>

              <?php if (!empty($message)): ?>
                  <div class="alert alert-<?= htmlspecialchars($messageType) ?>">
                      <?= htmlspecialchars($message) ?>
                  </div>
              <?php endif; ?>

              <form action="update-complaint.php?id=<?= $complaintId ?>" method="POST">
                  <div class="mb-3">
                      <label for="type" class="form-label">Complaint Type</label>
                      <select class="form-control" id="type" name="type" required>
                          <option value="">Select Type</option>
                          <option value="Academic" <?= $currentComplaint['type'] === 'Academic' ? 'selected' : '' ?>>Academic</option>
                          <option value="Administrative" <?= $currentComplaint['type'] === 'Administrative' ? 'selected' : '' ?>>Administrative</option>
                          <option value="Facility" <?= $currentComplaint['type'] === 'Facility' ? 'selected' : '' ?>>Facility</option>
                          <option value="Other" <?= $currentComplaint['type'] === 'Other' ? 'selected' : '' ?>>Other</option>
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($currentComplaint['description']) ?></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Update Complaint</button>
              </form>
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