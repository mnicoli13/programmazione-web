<?php
// Include database connection
require_once __DIR__ . '/../../config/database.php';

try {
        // Return JSON response
    header('Content-Type: application/json');

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); 
        echo json_encode(['error' => 'Metodo non valido']);
        exit;
    }

    // Check if username was sent
    if (!isset($_POST['username']) || empty($_POST['username'])) {
        http_response_code(400); // Richiesta errata
        echo json_encode(['error' => 'Nome utente mancante', 'available' => false]);
        exit;
    }
    $conn = getConnection();
    
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    $username = trim($_POST['username']);

    // Check if username exists in database
    $sql = "SELECT COUNT(*) as count FROM Utenti WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    http_response_code(200);
    echo json_encode(['available' => ($row['count'] == 0)]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'db_error', 'message' => $e->getMessage(), 'available' => false]);
    exit;
}
?> 