<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include required files
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/User.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Initialize database and models
$database = new Database();
$db = $database->getConnection();
$userQueries = new UserQueries($db);
$complaint = new Complaint($db);

// Handle delete request
if (isset($_GET['delete_id'])) {
  $deleteId = intval($_GET['delete_id']);
  try {
      $userQueries->deleteStudent($deleteId);
      $message = "Student deleted successfully.";
      $messageType = "success";
      echo "<script>
      alert('Student successfully deleted.');
      window.location.href = 'admin-dashboard.php';
      </script>";
      exit;
  } catch (Exception $e) {
      $message = "Failed to delete student: " . $e->getMessage();
      $messageType = "danger";
  }
}

// Fetch all departments
$departments = $complaint->getDepartments();

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$students = $userQueries->searchStudents($search, $department);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Manage Students</h3>
        <form method="GET" action="filter-students.php">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search by Student ID or Name" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-4">
                    <select id="department-filter" name="department" class="form-control">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= htmlspecialchars($department['dept_id']) ?>" <?= $department['dept_id'] == $department ? 'selected' : '' ?>><?= htmlspecialchars($department['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Full Name</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="students-table">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                            <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td><?= htmlspecialchars($student['department_name']) ?></td>
                            <td><?= htmlspecialchars($student['section']) ?></td>
                            <td>
                                <a href="useracc-settings.php?id=<?= $student['student_id'] ?>" class="btn btn-sm btn-primary">Update</a>
                                <a href="filter-students.php?delete_id=<?= $student['student_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>