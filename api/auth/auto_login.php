<?php
// Include database connection
require_once __DIR__ . '/../../config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Return JSON response
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodo non valido']);
    exit;
}

// Check if user ID is provided
if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID utente mancante']);
    exit;
}

// Get and sanitize user ID
$userId = mysqli_real_escape_string($conn, $_POST['user_id']);

// Query to get user data
$query = "SELECT id, username, email FROM Utenti WHERE id = $userId";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore database']);
    exit;
}

if (mysqli_num_rows($result) != 1) {
    echo json_encode(['success' => false, 'message' => 'Utente non trovato']);
    exit;
}

$user = mysqli_fetch_assoc($result);

// Update last login time
$currentTime = date('Y-m-d H:i:s');
$updateQuery = "UPDATE Utenti SET last_login = '$currentTime' WHERE id = $userId";
mysqli_query($conn, $updateQuery);

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];
$_SESSION['authenticated'] = true;

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Auto-login effettuato con successo',
    'user' => $user
]);
?> 