<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Initialize database and Complaint model
$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

// Fetch departments and statuses for the filters
$departments = $complaint->getDepartments();
$statuses = $complaint->getStatuses();

// Get filter values
$filterDept = $_GET['department'] ?? 'all';
$filterStatus = $_GET['status'] ?? 'all';

// Fetch complaints based on filters
$complaints = $complaint->getFilteredComplaints($filterDept, $filterStatus);

// Function to map status to badge color
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending': return 'warning';
        case 'in progress': return 'info';
        case 'resolved': return 'success';
        default: return 'secondary';
    }
}
?>
<!-- admin_dashboard.php -->
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
          <div class="manage-complaint">
              <h3>Manage Complaints</h3>
              <form id="filterForm" method="GET" action="filter-complaint.php" class="mb-3 d-flex gap-2">
                  <select name="department" class="form-select w-auto">
                      <option value="all" <?= $filterDept === 'all' ? 'selected' : '' ?>>All Departments</option>
                      <?php foreach ($departments as $dept): ?>
                          <option value="<?= $dept['dept_id'] ?>" <?= $filterDept == $dept['dept_id'] ? 'selected' : '' ?>>
                              <?= htmlspecialchars($dept['name']) ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
                  <select name="status" class="form-select w-auto">
                      <option value="all" <?= $filterStatus === 'all' ? 'selected' : '' ?>>All Statuses</option>
                      <?php foreach ($statuses as $status): ?>
                          <option value="<?= strtolower($status['status_name']) ?>" <?= strtolower($filterStatus) === strtolower($status['status_name']) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($status['status_name']) ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-primary">Filter</button>
              </form>

              <table class="table table-striped">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Type</th>
                          <th>Description</th>
                          <th>Department</th>
                          <th>Status</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($complaints)): ?>
                          <?php foreach ($complaints as $complaint): ?>
                              <tr>
                                  <td><?= htmlspecialchars($complaint['complaint_id']) ?></td>
                                  <td><?= htmlspecialchars($complaint['type']) ?></td>
                                  <td><?= htmlspecialchars($complaint['description']) ?></td>
                                  <td><?= htmlspecialchars($complaint['department']) ?></td>
                                  <td>
                                      <span class="badge bg-<?= getStatusColor($complaint['status_name']) ?>">
                                          <?= htmlspecialchars($complaint['status_name']) ?>
                                      </span>
                                  </td>
                                  <td>
                                      <a href="process-complaint.php?id=<?= $complaint['complaint_id'] ?>" class="btn btn-primary btn-sm">View</a>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td colspan="6" class="text-center">No complaints found.</td>
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
          </div>
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
