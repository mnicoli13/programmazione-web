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

// Check if all required fields are provided
$requiredFields = ['username', 'email', 'password', 'confirm_password', 'terms'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    echo json_encode(['success' => false, 'message' => 'I seguenti campi sono obbligatori: ' . implode(', ', $missingFields)]);
    exit;
}

// Get and sanitize user input
$conn = getConnection();
$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

// Validate username
if (strlen($username) < 4) {
    echo json_encode(['success' => false, 'message' => 'Il nome utente deve contenere almeno 4 caratteri']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Il nome utente può contenere solo lettere, numeri e underscore']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Indirizzo email non valido']);
    exit;
}

// Validate password
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'La password deve contenere almeno 8 caratteri']);
    exit;
}

// Check if passwords match
if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Le password non corrispondono']);
    exit;
}

// Check if username already exists
$query = "SELECT COUNT(*) as count FROM Utenti WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore nel controllo del nome utente']);
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Nome utente già in uso']);
    exit;
}

// Check if email already exists
$query = "SELECT COUNT(*) as count FROM Utenti WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore nel controllo dell\'email']);
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata']);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Get current datetime
$createdAt = date('Y-m-d H:i:s');

// Insert new user into database
$query = "INSERT INTO Utenti (username, email, password, created_at) VALUES ('$username', '$email', '$hashedPassword', '$createdAt')";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione: ' . mysqli_error($conn)]);
    exit;
}

// Return success response
echo json_encode(['success' => true, 'message' => 'Registrazione completata con successo']);
?> 