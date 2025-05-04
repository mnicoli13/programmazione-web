<?php
// Include database connection
require_once '../../config/database.php';
require_once '../../config/constants.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get vehicle ID
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

// Validate ID
if (empty($id)) {
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
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    // Ottieni i numeri di targa attualmente associati al veicolo
    $getTargheSql = "SELECT ta.targa FROM " . TABLE_TARGA_ATTIVA . " ta WHERE ta.veicolo = ?";
    $getTargheStmt = mysqli_prepare($conn, $getTargheSql);
    mysqli_stmt_bind_param($getTargheStmt, 's', $id);
    mysqli_stmt_execute($getTargheStmt);
    $targhesResult = mysqli_stmt_get_result($getTargheStmt);
    
    $targhe = [];
    while ($row = mysqli_fetch_assoc($targhesResult)) {
        $targhe[] = $row['targa'];
    }
    mysqli_stmt_close($getTargheStmt);
    
    // Elimina prima tutte le associazioni nella tabella TargaAttiva
    $deleteActiveSql = "DELETE FROM " . TABLE_TARGA_ATTIVA . " WHERE veicolo = ?";
    $deleteActiveStmt = mysqli_prepare($conn, $deleteActiveSql);
    mysqli_stmt_bind_param($deleteActiveStmt, 's', $id);
    mysqli_stmt_execute($deleteActiveStmt);
    $activeRowsDeleted = mysqli_stmt_affected_rows($deleteActiveStmt);
    mysqli_stmt_close($deleteActiveStmt);
    
    // Elimina anche le associazioni nella tabella TargaRestituita
    $deleteReturnedSql = "DELETE FROM " . TABLE_TARGA_RESTITUITA . " WHERE veicolo = ?";
    $deleteReturnedStmt = mysqli_prepare($conn, $deleteReturnedSql);
    mysqli_stmt_bind_param($deleteReturnedStmt, 's', $id);
    mysqli_stmt_execute($deleteReturnedStmt);
    $returnedRowsDeleted = mysqli_stmt_affected_rows($deleteReturnedStmt);
    mysqli_stmt_close($deleteReturnedStmt);
    
    // Elimina le targhe dalla tabella Targa
    $targheDeleted = 0;
    if (!empty($targhe)) {
        foreach ($targhe as $targa) {
            $deleteTargaSql = "DELETE FROM " . TABLE_TARGA . " WHERE numero = ?";
            $deleteTargaStmt = mysqli_prepare($conn, $deleteTargaSql);
            mysqli_stmt_bind_param($deleteTargaStmt, 's', $targa);
            mysqli_stmt_execute($deleteTargaStmt);
            $targheDeleted += mysqli_stmt_affected_rows($deleteTargaStmt);
            mysqli_stmt_close($deleteTargaStmt);
        }
    }
    
    // Infine elimina il veicolo
    $sql = "DELETE FROM " . TABLE_VEICOLO . " WHERE telaio = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    
    // Check if vehicle was actually deleted
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    if ($affected_rows === 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Veicolo non trovato'
        ]);
        mysqli_rollback($conn);
        exit;
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Prepara messaggio di successo con dettaglio delle eliminazioni
    $message = 'Veicolo eliminato con successo';
    if ($activeRowsDeleted > 0 || $returnedRowsDeleted > 0 || $targheDeleted > 0) {
        $message .= ' insieme a ';
        $dettagli = [];
        
        if ($targheDeleted > 0) {
            $dettagli[] = "$targheDeleted targhe";
        }
        
        if ($activeRowsDeleted > 0) {
            $dettagli[] = "$activeRowsDeleted associazioni a targhe attive";
        }
        
        if ($returnedRowsDeleted > 0) {
            $dettagli[] = "$returnedRowsDeleted associazioni a targhe restituite";
        }
        
        $message .= implode(', ', $dettagli);
    }
    
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => $message
    ]);
    
} catch (Exception $e) {
    // Rollback transaction in case of error
    if (isset($conn)) {
        mysqli_rollback($conn);
    }

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 