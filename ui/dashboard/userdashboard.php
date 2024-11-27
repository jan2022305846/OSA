<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../auth/log-in.php');
    exit;
}

$firstName = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : 'Student';

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
                <a href="#" data-section="dashboard" class="nav-link active">Dashboard</a>
                <a href="#" data-section="submit-complaint" class="nav-link">Submit Complaint</a>
                <a href="#" data-section="complaint-history" class="nav-link">Complaint History</a>
                <a href="#" data-section="account-settings" class="nav-link">Account Settings</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main id="main-content" class="p-3 flex-grow-1">
            <h3>Dashboard Overview</h3>
            <p>Welcome to your dashboard! Here you can see an overview of your activities.</p>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-center py-3">
        <p>&copy; 2024 OSA Online Complaints. All rights reserved.</p>
    </footer>
    
    <!-- Scripts -->
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/user.js" defer></script>
</body>
</html>
