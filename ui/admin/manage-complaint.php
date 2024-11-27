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
