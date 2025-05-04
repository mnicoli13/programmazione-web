<?php
// Include database connection
require_once __DIR__ . '/../../config/database.php';

// Return JSON response
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Metodo non valido']);
    exit;
}

// Check if email was sent
if (!isset($_POST['email']) || empty($_POST['email'])) {
    echo json_encode(['error' => 'Email mancante', 'available' => false]);
    exit;
}

$conn = getConnection();

$email = mysqli_real_escape_string($conn, $_POST['email']);

// Check if email exists in database
$query = "SELECT COUNT(*) as count FROM Utenti WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Errore database', 'available' => false]);
    exit;
}

$row = mysqli_fetch_assoc($result);

// Return availability
echo json_encode(['available' => ($row['count'] == 0)]);
?> 