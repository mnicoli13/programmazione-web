<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_mnicoli64');
define('DB_USER', 'mnicoli64');
define('DB_PASS', 'fdY29KnAaN72');

function getConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        echo "Errore di connessione: " . mysqli_connect_error();
        return null;
    }
    
    mysqli_set_charset($conn, "utf8");
    return $conn;
}
?>
