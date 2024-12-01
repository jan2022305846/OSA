<?php
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Session.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/includes/Database.php';
require_once 'C:/xampp/htdocs/OSA FINAL OOP/classes/queries/Complaint.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p class='text-danger'>Error: Required data not available.</p>";
    exit;
}

$database = new Database();
$db = $database->getConnection();
$complaint = new Complaint($db);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $studentId = $_SESSION['student_id'];

    if (empty($type) || empty($description)) {
        $message = "All fields are required.";
        $messageType = "danger";
    } else {
        try {
            // Handle file uploads
            $uploadDir = '../../records/';
            $documentPath = null;
            $evidencePaths = [];

            // Upload document
            if (!empty($_FILES['document']['name'])) {
                $documentName = uniqid() . '-' . basename($_FILES['document']['name']);
                $documentPath = $uploadDir . $documentName;

                if (!move_uploaded_file($_FILES['document']['tmp_name'], $documentPath)) {
                    throw new Exception("Error uploading the document.");
                }
            }

            // Upload evidence files
            if (!empty($_FILES['evidence']['name'][0])) {
                foreach ($_FILES['evidence']['tmp_name'] as $key => $tmpName) {
                    $evidenceName = uniqid() . '-' . basename($_FILES['evidence']['name'][$key]);
                    $evidencePath = $uploadDir . $evidenceName;

                    if (!move_uploaded_file($tmpName, $evidencePath)) {
                        throw new Exception("Error uploading evidence file: " . $_FILES['evidence']['name'][$key]);
                    }

                    $evidencePaths[] = $evidencePath;
                }
            }

            // Submit complaint
            $result = $complaint->submitComplaint($studentId, $type, $description, $documentPath, $evidencePaths);
            if ($result) {
                header("Location: userdashboard.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Include your CSS -->
</head>
<body>
<div class="submit-complaint-form">
    <h3>Submit a Complaint</h3>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($messageType); ?>"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="submit-complaint.php" method="POST" enctype="multipart/form-data">
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
            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
        </div>
        <div class="mb-3">
            <label for="document" class="form-label">Document</label>
            <input type="file" class="form-control" id="document" name="document" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
        </div>
        <div class="mb-3">
            <label for="evidence" class="form-label">Evidence (up to 5 files)</label>
            <input type="file" class="form-control" id="evidence" name="evidence[]" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Submit Complaint</button>
    </form>
</div>
</body>
</html>
