<?php

// Include database connection
require_once __DIR__ . '/../../config/database.php';

try {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Return JSON response
    header('Content-Type: application/json');

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Metodo non valido']);
        exit;
    }

    // Check if username and password are provided
    if (!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nome utente e password sono obbligatori']);
        exit;
    }

    $conn = getConnection();

    // Get and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Check if username is an email
    $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

    // Query to check user credentials
    if ($isEmail) {
        $query = "SELECT id, username, email, password FROM Utenti WHERE email = '$username'";
    } else {
        $query = "SELECT id, username, email, password FROM Utenti WHERE username = '$username'";
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore database']);
        exit;
    }

    if (mysqli_num_rows($result) !== 1) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Nome utente o password non validi']);
        exit;
    }

    $user = mysqli_fetch_assoc($result);

    // Verify password
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Nome utente o password non validi']);
        exit;
    }

    // Update last login time
    $userId = $user['id'];
    $currentTime = date('Y-m-d H:i:s');
    $updateQuery = "UPDATE Utenti SET last_login = '$currentTime' WHERE id = $userId";
    mysqli_query($conn, $updateQuery);

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['authenticated'] = true;

    // Set session duration based on remember me
    if ($remember) {
        // Set session to expire in 30 days
        ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
        session_set_cookie_params(60 * 60 * 24 * 30);
    }

    // Return user data (excluding password)
    unset($user['password']);

    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Login effettuato con successo',
        'user' => $user
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore nel login: ' . $e->getMessage()
    ]);
}