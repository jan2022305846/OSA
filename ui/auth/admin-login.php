<?php
session_start();
require_once('../../classes/Auth.php');
require_once('../../classes/includes/Database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConnection();
    $auth = new Auth($db);

    $admin_id = filter_input(INPUT_POST, 'admin_id', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate admin ID format
    if (!ctype_digit($admin_id)) {
        $_SESSION['error'] = 'Invalid Admin ID format';
        header('Location: admin-login.php');
        exit;
    }

    try {
        if ($auth->adminLogin($admin_id, $password)) {
            $_SESSION['success'] = 'Login successful!';
            header('Location: ../admin/admin-dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid Admin ID or Password';
            header('Location: admin-login.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
        header('Location: admin-login.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSA Online Complaints - Login</title>
    <link rel="icon" href="../../assets/images/osa.png">
    <link rel="stylesheet" href="../../assets/css/indexheader.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <div class="d-flex align-items-center img-logo">
                <img src="../../assets/images/osa.png" alt="OSA" class="navbar-brand img-fluid" width="50">
                <img src="../../assets/images/LOGOPNG.png" alt="Logo" class="navbar-brand img-fluid" width="50">
                <h2 class="text-white mb-0 ms-3">OSA Online Complaints</h2>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="../../index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="../about-us.php" class="nav-link">About Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form Section -->
    <div class="login-container">
        <div class="d-flex align-items-center justify-content-around img-logo gap-3">
            <h1>Log In</h1>
            <img src="../../assets/images/osa.png" alt="OSA" class="navbar-brand img-fluid" width="50">
            </div>


            <form action="" method="post">
                <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" name="admin_id" placeholder="EX: 2024654321">
                <label for="floatingInput">Admin ID</label>
                </div>

                <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
                </div>

                <button class="btn btn-primary rounded-pill" type="submit">Login</button>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

        </form>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-center py-3">
        <p>&copy; 2024 OSA Online Complaints. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
