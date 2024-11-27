<?php
// Start session only if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION['student_id'])) {
    http_response_code(401);
    echo "Unauthorized access";
    exit;
}
