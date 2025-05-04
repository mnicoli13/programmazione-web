<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Return JSON response
header('Content-Type: application/json');

// Check if user is authenticated
$authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

if ($authenticated) {
    // Return user data
    echo json_encode([
        'authenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email']
        ]
    ]);
} else {
    // Return not authenticated
    echo json_encode([
        'authenticated' => false
    ]);
}
?> 