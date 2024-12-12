<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/User.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}

$database = new Database();
// Initialize database connection
$db = $database->getConnection();

// Fetch user data to prefill the form
$userQueries = new UserQueries($db);
$userData = $userQueries->getUserByStudentId($_SESSION['student_id']);

// Fetch departments for the dropdown
$departments = $userQueries->getDepartments();

// Safely access the user data
$email = isset($userData['email']) ? $userData['email'] : '';
$first_name = isset($userData['first_name']) ? $userData['first_name'] : '';
$last_name = isset($userData['last_name']) ? $userData['last_name'] : '';
$section = isset($userData['section']) ? $userData['section'] : '';
$c_num = isset($userData['c_num']) ? $userData['c_num'] : '';
$current_department = isset($userData['department_id']) ? $userData['department_id'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $section = trim($_POST['section']);
    $c_num = trim($_POST['c_num']);
    $department_id = $_POST['department'];
    $password = trim($_POST['password']); // Optional, can be empty

    try {
        // Update student information
        $isUpdated = $userQueries->updateSettings($_SESSION['student_id'], $first_name, $last_name, $email, $section, $c_num, $department_id, $password);

        if ($isUpdated) {
            $_SESSION['success'] = 'Account settings updated successfully.';
            echo "<script>
                alert('Account successfully updated.');
                window.location.href = 'admin-dashboard.php';
                </script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

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
                <a href="#" data-section="manage-complaint" class="nav-link  active">Manage Students</a>
                <a href="#" data-section="manage-complaint" class="nav-link">Manage Complaints</a>
                <a href="#" data-section="account-settings" class="nav-link">Account Settings</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main id="main-content" class="p-3 flex-grow-1">
        <div class="account-settings-form">
          <h3>Account Settings</h3>

          <?php if (!empty($_SESSION['success'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
              <?php unset($_SESSION['success']); ?>
          <?php endif; ?>

          <form action="useracc-settings.php" method="POST">
              <div class="mb-3">
                  <label for="first_name" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
              </div>

              <div class="mb-3">
                  <label for="last_name" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
              </div>

              <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
              </div>

              <div class="mb-3">
                  <label for="section" class="form-label">Section</label>
                  <input type="text" class="form-control" id="section" name="section" value="<?= htmlspecialchars($section) ?>" required>
              </div>

              <div class="mb-3">
                  <label for="c_num" class="form-label">Contact Number</label>
                  <input type="text" class="form-control" id="c_num" name="c_num" value="<?= htmlspecialchars($c_num) ?>" required>
              </div>

              <div class="mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select class="form-control" id="department" name="department" required>
                      <option value="">Select Department</option>
                      <?php foreach ($departments as $department): ?>
                          <option value="<?= $department['dept_id'] ?>" <?= $department['dept_id'] == $current_department ? 'selected' : '' ?>>
                              <?= htmlspecialchars($department['name']) ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="password" class="form-label">New Password</label>
                  <input type="password" class="form-control" id="password" name="password">
                  <small class="text-muted">Leave blank to keep current password</small>
              </div>

              <button type="submit" class="btn btn-primary">Update Settings</button>
          </form>
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
