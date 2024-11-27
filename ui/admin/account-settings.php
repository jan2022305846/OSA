<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Admin.php';


$database = new Database();
// Initialize database connection
$db = $database->getConnection();

// Fetch admin data to prefill the form
$adminQueries = new AdminQueries($db);
$adminData = $adminQueries->getAdminById($_SESSION['admin_id']);


// Safely access the admin data
$first_name = isset($adminData['first_name']) ? $adminData['first_name'] : '';
$last_name = isset($adminData['last_name']) ? $adminData['last_name'] : '';
$email = isset($adminData['email']) ? $adminData['email'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Optional, can be empty

    try {
        // Update student information
        $isUpdated = $adminQueries->updateSettings($_SESSION['admin_id'], $first_name, $last_name, $email, $password);

        if ($isUpdated) {
            $_SESSION['success'] = 'Account settings updated successfully.';
            echo "<script>
                alert('Account successfully updated.');
                window.location.href = 'userdashboard.php';
                </script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<div class="account-settings-form">
    <h3>Account Settings</h3>

    <form action="account-settings.php" method="POST">
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
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="text-muted">Leave blank to keep current password</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>
