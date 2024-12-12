<?php
// Start session only if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo "Unauthorized access";
    exit;
}

// Map each section to a specific PHP file within the same folder
$section = $_GET['section'] ?? 'dashboard';  // Default to 'dashboard' if no section is specified
$allowedSections = ['dashboard', 'manage-complaint', 'account-settings', 'manage-students'];  // List of allowed sections

// Validate the requested section
if (in_array($section, $allowedSections)) {
    // Set the file path relative to the current folder where the sections are stored
    $filePath = __DIR__ . "/{$section}.php";
    
    // Check if the requested section file exists
    if (file_exists($filePath)) {
        include $filePath; // Dynamically include the requested section
    } else {
        // If file does not exist, return a 404 error
        http_response_code(404);
        echo "<p class='text-danger'>Error: The section file for '{$section}' was not found.</p>";
    }
} else {
    // If an invalid section is requested, return a 400 error
    http_response_code(400);
    echo "<p class='text-danger'>Error: Invalid section requested.</p>";
}
