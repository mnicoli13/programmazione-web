<?php
// Include database connection
require_once '../../config/database.php';
require_once '../../config/constants.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get the vehicle ID
$telaio = isset($_GET['telaio']) ? $_GET['telaio'] : '';

if (empty($telaio)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'ID veicolo mancante'
    ]);
    exit;
}

try {
    $conn = getConnection();
    
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    // Prepare statement
    $sql = "SELECT telaio, marca, modello, dataProd FROM " . TABLE_VEICOLO . " WHERE telaio = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameter
    mysqli_stmt_bind_param($stmt, 's', $telaio);
    
    // Execute query
    mysqli_stmt_execute($stmt);
    
    // Get results
    $result = mysqli_stmt_get_result($stmt);
    $veicolo = mysqli_fetch_assoc($result);
    
    // Close statement
    mysqli_stmt_close($stmt);
    
    if (!$veicolo) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Veicolo non trovato'
        ]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'data' => $veicolo
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 