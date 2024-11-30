<?php
session_start();
require_once('../../classes/Auth.php');
require_once('../../classes/includes/Database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = (new Database())->getConnection();
        $auth = new Auth($db);

        // Sanitize user inputs
        $student_id = filter_input(INPUT_POST, 'idnum', FILTER_SANITIZE_STRING);
        $first_name = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $mobile_num = filter_input(INPUT_POST, 'mobilenum', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $program = filter_input(INPUT_POST, 'program', FILTER_SANITIZE_STRING);
        $section = filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING);

        // Register the user
        if ($auth->register($student_id, $first_name, $last_name, $mobile_num, $email, $password, $program, $section)) {
            $_SESSION['success'] = 'Registration successful!';
            $_SESSION['student_id'] = $student_id;
            $_SESSION['first_name'] = $first_name;
            header('Location: ../dashboard/userdashboard.php'); // Redirect to dashboard
            exit;
        } else {
            $_SESSION['error'] = 'Registration failed. Student ID already exists.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
    header('Location: sign-up.php'); // Redirect back to sign-up
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSA Online Complaints - Sign Up</title>
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
                <img src="../../assets/images/osa.png" width="50">
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

    <!-- Sign Up Form Section -->
    <div class="login-container ">
        <div class="d-flex align-items-center justify-content-around img-logo gap-3">
            <h1>Sign Up</h1>
            <img src="../../assets/images/osa.png" alt="OSA" class="navbar-brand img-fluid" width="50">
        </div>

        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="idnum" name="idnum" placeholder="Student ID" required>
                        <label for="idnum">Student ID</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
                        <label for="firstname">First Name</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
                        <label for="lastname">Last Name</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="mobilenum" name="mobilenum" placeholder="Mobile Number" required>
                        <label for="mobilenum">Mobile Number</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <select id="program" name="program" class="form-select" required>
                            <option value="" disabled selected>Select a Program</option>
                            <option value="BSMB">BSMB</option>
                            <option value="BTLE-IA">BTLE-IA</option>
                            <option value="BTLE-HE">BTLE-HE</option>
                            <option value="BSIT">BSIT</option>
                        </select>
                        <label for="program">Program</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="section" name="section" placeholder="Section" required>
                        <label for="section">Section</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary rounded-pill" type="submit">Sign Up</button>

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