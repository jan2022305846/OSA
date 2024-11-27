<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
// Check if student is logged in
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

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if (empty($type) || empty($description)) {
        $message = "All fields are required.";
        $messageType = "danger";
    } else {
        // Attempt to submit complaint
        $studentId = $_SESSION['student_id'];

        try {
            $result = $complaint->submitComplaint($studentId, $type, $description);
            if ($result) {
                $message = "Complaint submitted successfully.";
                $messageType = "success";
                echo "<script>
                alert('Complaint successfully submitted.');
                window.location.href = 'userdashboard.php';
                </script>";
                exit;
            } else {
                $message = "Failed to submit complaint. Please try again.";
                $messageType = "danger";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = "danger";
        }
    }
}
?>

<div class="submit-complaint-form">
    <h3>Submit a Complaint</h3>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="submit-complaint.php" method="POST">
        <div class="mb-3">
            <label for="type" class="form-label">Complaint Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="">Select Type</option>
                <option value="Academic">Academic</option>
                <option value="Administrative">Administrative</option>
                <option value="Facility">Facility</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Complaint</button>
    </form>
</div>
