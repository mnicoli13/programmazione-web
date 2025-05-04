<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Return JSON response
header('Content-Type: application/json');

// Check if path is provided
if (!isset($_POST['path']) || empty($_POST['path'])) {
    echo json_encode(['error' => 'Percorso mancante']);
    exit;
}

// Get the path
$path = $_POST['path'];

// List of public pages (not requiring authentication)
$publicPages = [
    '/login.php',
    '/register.php',
    '/api/auth/login.php',
    '/api/auth/register.php',
    '/api/auth/check_username.php',
    '/api/auth/check_email.php',
    '/api/auth/is_protected.php'
];

// Check if user is authenticated
$authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Check if the page is protected
$isProtected = !in_array($path, $publicPages);

// Return response
echo json_encode([
    'protected' => $isProtected,
    'authenticated' => $authenticated
]);
?> 