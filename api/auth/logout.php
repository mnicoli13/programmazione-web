<?php

try {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Return JSON response
    header('Content-Type: application/json');

    // Unset all session variables
    $_SESSION = array();

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Logout effettuato con successo'
    ]);
} catch (Exception $e) {
    http_response_code(500);

    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Errore nel logout: ' . $e->getMessage()
    ]);
}
?> 