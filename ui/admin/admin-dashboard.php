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
                <a href="#" data-section="dashboard" class="nav-link active">Dashboard</a>
                <a href="#" data-section="manage-complaint" class="nav-link">Manage Complaints</a>
                <a href="#" data-section="account-settings" class="nav-link">Account Settings</a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main id="main-content" class="p-3 flex-grow-1">
            <?php
                $section = $_GET['section'] ?? 'dashboard';
                echo "<script>console.log('Section loaded: $section');</script>";
                switch ($section) {
                    case 'manage-complaint':
                        include 'manage-complaint.php';
                        break;
                    case 'account-settings':
                        include 'account-settings.php';
                        break;
                    default:
                        echo '<h3>Dashboard Overview</h3><p>Welcome to your dashboard! Here you can see an overview of your activities.</p>';
                }
            ?>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Student Affairs Complaint System. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="../../assets/js/admin.js"></script>
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
