<?php
// Include database connection
require_once '../config/database.php';
require_once '../config/constants.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get vehicle ID
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

// Validate ID
if (empty($id)) {
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
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    // Check if vehicle is referenced in TargaAttiva
    $checkActiveSql = "SELECT COUNT(*) as count FROM " . TABLE_TARGA_ATTIVA . " WHERE veicolo = ?";
    $checkActiveStmt = mysqli_prepare($conn, $checkActiveSql);
    mysqli_stmt_bind_param($checkActiveStmt, 's', $id);
    mysqli_stmt_execute($checkActiveStmt);
    $checkActiveResult = mysqli_stmt_get_result($checkActiveStmt);
    $activeRow = mysqli_fetch_assoc($checkActiveResult);
    mysqli_stmt_close($checkActiveStmt);
    
    if ($activeRow['count'] > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Impossibile eliminare il veicolo: è associato a targhe attive'
        ]);
        mysqli_rollback($conn);
        exit;
    }
    
    // Delete from TargaRestituita first (if any)
    $deleteReturnedSql = "DELETE FROM " . TABLE_TARGA_RESTITUITA . " WHERE veicolo = ?";
    $deleteReturnedStmt = mysqli_prepare($conn, $deleteReturnedSql);
    mysqli_stmt_bind_param($deleteReturnedStmt, 's', $id);
    mysqli_stmt_execute($deleteReturnedStmt);
    mysqli_stmt_close($deleteReturnedStmt);
    
    // Then delete the vehicle
    $sql = "DELETE FROM " . TABLE_VEICOLO . " WHERE telaio = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    
    // Check if vehicle was actually deleted
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    if ($affected_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Veicolo non trovato'
        ]);
        mysqli_rollback($conn);
        exit;
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Veicolo eliminato con successo'
    ]);
    
} catch (Exception $e) {
    // Rollback transaction in case of error
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 