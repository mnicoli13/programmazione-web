<?php
// Include database connection
require_once '../../config/database.php';
require_once '../../config/constants.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get the requested table
$table = isset($_GET['table']) ? $_GET['table'] : '';

// Get sorting parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Validate order direction
$order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';

// Handle different tables
try {
    $conn = getConnection();
    
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    $data = [];
    $columns = [];
    
    switch ($table) {
        case TABLE_VEICOLO:
            // Get filter parameters
            $telaio = isset($_GET['telaio']) ? $_GET['telaio'] : '';
            $marca = isset($_GET['marca']) ? $_GET['marca'] : '';
            $modello = isset($_GET['modello']) ? $_GET['modello'] : '';
            $dataProd = isset($_GET['dataProd']) ? $_GET['dataProd'] : '';
            
            // Build query
            $sql = "SELECT telaio, marca, modello, dataProd FROM " . TABLE_VEICOLO . " WHERE 1=1";
            
            // Add filters
            $params = [];
            if (!empty($telaio)) {
                $sql .= " AND telaio LIKE ?";
                $params[] = '%' . $telaio . '%';
            }
            if (!empty($marca)) {
                $sql .= " AND marca LIKE ?";
                $params[] = '%' . $marca . '%';
            }
            if (!empty($modello)) {
                $sql .= " AND modello LIKE ?";
                $params[] = '%' . $modello . '%';
            }
            if (!empty($dataProd)) {
                $sql .= " AND dataProd = ?";
                $params[] = $dataProd;
            }
            
            // Add sorting
            if ($sort) {
                $sql .= " ORDER BY " . $sort . " " . $order;
            }
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters if any
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // Assumiamo che tutti i parametri siano stringhe
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            
            // Get results
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Define columns for the frontend
            $columns = [
                ['name' => 'telaio', 'label' => 'Telaio', 'isLink' => true, 'linkTarget' => 'veicolo'],
                ['name' => 'marca', 'label' => 'Marca'],
                ['name' => 'modello', 'label' => 'Modello'],
                ['name' => 'dataProd', 'label' => 'Data Produzione', 'type' => 'date']
            ];
            break;
            
        case TABLE_TARGA:
            // Get filter parameters
            $numero = isset($_GET['numero']) ? $_GET['numero'] : '';
            $dataEm = isset($_GET['dataEm']) ? $_GET['dataEm'] : '';
            $stato = isset($_GET['stato']) ? $_GET['stato'] : '';
            
            // Build query
            $sql = "SELECT t.numero, t.dataEm, 
                    CASE 
                        WHEN ta.targa IS NOT NULL THEN 'Attiva' 
                        WHEN tr.targa IS NOT NULL THEN 'Restituita' 
                        ELSE 'Non assegnata' 
                    END AS stato 
                    FROM " . TABLE_TARGA . " t 
                    LEFT JOIN " . TABLE_TARGA_ATTIVA . " ta ON t.numero = ta.targa 
                    LEFT JOIN " . TABLE_TARGA_RESTITUITA . " tr ON t.numero = tr.targa 
                    WHERE 1=1";
            
            // Add filters
            $params = [];
            if (!empty($numero)) {
                $sql .= " AND t.numero LIKE ?";
                $params[] = '%' . $numero . '%';
            }
            if (!empty($dataEm)) {
                $sql .= " AND t.dataEm = ?";
                $params[] = $dataEm;
            }
            if (!empty($stato)) {
                $sql .= " AND (CASE 
                              WHEN ta.targa IS NOT NULL THEN 'Attiva' 
                              WHEN tr.targa IS NOT NULL THEN 'Restituita' 
                              ELSE 'Non assegnata' 
                          END) = ?";
                $params[] = $stato;
            }
            
            // Add sorting
            if ($sort) {
                $sql .= " ORDER BY " . $sort . " " . $order;
            }
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters if any
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            
            // Get results
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Define columns for the frontend
            $columns = [
                ['name' => 'numero', 'label' => 'Numero', 'isLink' => true, 'linkTarget' => 'targa'],
                ['name' => 'dataEm', 'label' => 'Data Emissione', 'type' => 'date'],
                ['name' => 'stato', 'label' => 'Stato', 'type' => 'status']
            ];
            break;
            
        case TABLE_REVISIONE:
            // Get filter parameters
            $numero = isset($_GET['numero']) ? $_GET['numero'] : '';
            $targa = isset($_GET['targa']) ? $_GET['targa'] : '';
            $dataRev = isset($_GET['dataRev']) ? $_GET['dataRev'] : '';
            $esito = isset($_GET['esito']) ? $_GET['esito'] : '';
            $motivazione = isset($_GET['motivazione']) ? $_GET['motivazione'] : '';
            
            // Build query
            $sql = "SELECT numero, targa, dataRev, esito, motivazione FROM " . TABLE_REVISIONE . " WHERE 1=1";
            
            // Add filters
            $params = [];
            if (!empty($numero)) {
                $sql .= " AND numero LIKE ?";
                $params[] = '%' . $numero . '%';
            }
            if (!empty($targa)) {
                $sql .= " AND targa LIKE ?";
                $params[] = '%' . $targa . '%';
            }
            if (!empty($dataRev)) {
                $sql .= " AND dataRev = ?";
                $params[] = $dataRev;
            }
            if (!empty($esito)) {
                $sql .= " AND esito = ?";
                $params[] = $esito;
            }
            if (!empty($motivazione)) {
                $sql .= " AND motivazione LIKE ?";
                $params[] = '%' . $motivazione . '%';
            }
            
            // Add sorting
            if ($sort) {
                $sql .= " ORDER BY " . $sort . " " . $order;
            }
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters if any
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            
            // Get results
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Define columns for the frontend
            $columns = [
                ['name' => 'numero', 'label' => 'Numero'],
                ['name' => 'targa', 'label' => 'Targa', 'isLink' => true, 'linkTarget' => 'targa'],
                ['name' => 'dataRev', 'label' => 'Data Revisione', 'type' => 'date'],
                ['name' => 'esito', 'label' => 'Esito'],
                ['name' => 'motivazione', 'label' => 'Motivazione']
            ];
            break;
            
        case TABLE_TARGA_ATTIVA:
            // Get filter parameters
            $targa = isset($_GET['targa']) ? $_GET['targa'] : '';
            $veicolo = isset($_GET['veicolo']) ? $_GET['veicolo'] : '';
            
            // Build query
            $sql = "SELECT ta.targa, ta.veicolo, t.dataEm, v.marca, v.modello 
                    FROM " . TABLE_TARGA_ATTIVA . " ta
                    JOIN " . TABLE_TARGA . " t ON ta.targa = t.numero
                    JOIN " . TABLE_VEICOLO . " v ON ta.veicolo = v.telaio
                    WHERE 1=1";
            
            // Add filters
            $params = [];
            if (!empty($targa)) {
                $sql .= " AND ta.targa LIKE ?";
                $params[] = '%' . $targa . '%';
            }
            if (!empty($veicolo)) {
                $sql .= " AND ta.veicolo LIKE ?";
                $params[] = '%' . $veicolo . '%';
            }
            
            // Add sorting
            if ($sort) {
                $sql .= " ORDER BY " . $sort . " " . $order;
            }
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters if any
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            
            // Get results
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Define columns for the frontend
            $columns = [
                ['name' => 'targa', 'label' => 'Targa', 'isLink' => true, 'linkTarget' => 'targa'],
                ['name' => 'veicolo', 'label' => 'Telaio Veicolo', 'isLink' => true, 'linkTarget' => 'veicolo'],
                ['name' => 'marca', 'label' => 'Marca'],
                ['name' => 'modello', 'label' => 'Modello'],
                ['name' => 'dataEm', 'label' => 'Data Emissione', 'type' => 'date']
            ];
            break;
            
        case TABLE_TARGA_RESTITUITA:
            // Get filter parameters
            $targa = isset($_GET['targa']) ? $_GET['targa'] : '';
            $veicolo = isset($_GET['veicolo']) ? $_GET['veicolo'] : '';
            $dataRes = isset($_GET['dataRes']) ? $_GET['dataRes'] : '';
            
            // Build query
            $sql = "SELECT tr.targa, tr.veicolo, tr.dataRes, t.dataEm, v.marca, v.modello 
                    FROM " . TABLE_TARGA_RESTITUITA . " tr
                    JOIN " . TABLE_TARGA . " t ON tr.targa = t.numero
                    JOIN " . TABLE_VEICOLO . " v ON tr.veicolo = v.telaio
                    WHERE 1=1";
            
            // Add filters
            $params = [];
            if (!empty($targa)) {
                $sql .= " AND tr.targa LIKE ?";
                $params[] = '%' . $targa . '%';
            }
            if (!empty($veicolo)) {
                $sql .= " AND tr.veicolo LIKE ?";
                $params[] = '%' . $veicolo . '%';
            }
            if (!empty($dataRes)) {
                $sql .= " AND tr.dataRes = ?";
                $params[] = $dataRes;
            }
            
            // Add sorting
            if ($sort) {
                $sql .= " ORDER BY " . $sort . " " . $order;
            }
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters if any
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            
            // Get results
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Define columns for the frontend
            $columns = [
                ['name' => 'targa', 'label' => 'Targa', 'isLink' => true, 'linkTarget' => 'targa'],
                ['name' => 'veicolo', 'label' => 'Telaio Veicolo', 'isLink' => true, 'linkTarget' => 'veicolo'],
                ['name' => 'marca', 'label' => 'Marca'],
                ['name' => 'modello', 'label' => 'Modello'],
                ['name' => 'dataEm', 'label' => 'Data Emissione', 'type' => 'date'],
                ['name' => 'dataRes', 'label' => 'Data Restituzione', 'type' => 'date']
            ];
            break;
            
        default:
            // Invalid table
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Tabella non valida'
            ]);
            exit;
    }
    
    // Return data and columns
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'data' => $data,
        'columns' => $columns
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 