<?php
// Include database connection
require_once '../config/database.php';
require_once '../config/constants.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get form data
$telaio = isset($_POST['telaio']) ? trim($_POST['telaio']) : '';
$marca = isset($_POST['marca']) ? trim($_POST['marca']) : '';
$modello = isset($_POST['modello']) ? trim($_POST['modello']) : '';
$dataProd = isset($_POST['dataProd']) ? trim($_POST['dataProd']) : '';

// Validate inputs
if (empty($telaio) || empty($marca) || empty($modello) || empty($dataProd)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Tutti i campi sono obbligatori'
    ]);
    exit;
}

try {
    $conn = getConnection();
    
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    // Check if vehicle already exists
    $checkSql = "SELECT COUNT(*) as count FROM " . TABLE_VEICOLO . " WHERE telaio = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, 's', $telaio);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $row = mysqli_fetch_assoc($checkResult);
    mysqli_stmt_close($checkStmt);
    
    if ($row['count'] > 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Un veicolo con questo telaio esiste già'
        ]);
        exit;
    }
    
    // Insert new vehicle
    $sql = "INSERT INTO " . TABLE_VEICOLO . " (telaio, marca, modello, dataProd) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, 'ssss', $telaio, $marca, $modello, $dataProd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Veicolo aggiunto con successo'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 