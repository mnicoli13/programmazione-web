<?php
/**
 * File di inizializzazione del sistema
 * Questo file deve essere incluso all'inizio di ogni pagina
 */

require_once __DIR__ . '/../lib/log.php';

// Avvia la sessione se non già avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Includi file di configurazione
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

// Definisci le pagine pubbliche (che non richiedono autenticazione)
$publicPages = [
    'login.php',
    'api/auth/login.php',
    'api/auth/register.php',
    'api/auth/check_username.php',
    'api/auth/check_email.php',
    'api/auth/is_protected.php'
];

// Ottieni il nome della pagina corrente 
$currentPage = basename($_SERVER['PHP_SELF']);

// Funzione per controllare se l'utente è autenticato
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Funzione per verificare se una pagina esiste
function pageExists($pageName) {
    // Controlla se è una pagina normale
    if (file_exists($pageName)) {
        return true;
    }
    
    // Controlla se è una pagina nella cartella pages
    if (file_exists('pages/' . $pageName)) {
        return true;
    }
    
    return false;
}

// Gestione autenticazione e reindirizzamenti
if (!isAuthenticated()) {
    // L'utente non è autenticato
    
    // Se la pagina corrente non è pubblica, reindirizza a login.php
    if (!in_array($currentPage, $publicPages)) {
        // Salva la pagina corrente per reindirizzare dopo il login

        if ($currentPage !== 'login.php') {
            $_SESSION['redirect_after_login'] = $currentPage;
            header('Location: /pages/login.php');
            exit;
        }
    }
} else {
    // L'utente è autenticato
    
    // Se è sulla pagina index.php, reindirizza a homepage.php
    if ($currentPage === 'index.php') {
        header('Location: /pages/homepage.php');
        exit;
    }
    
    // Recupera i dati dell'utente dalla sessione
    $user = [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email']
    ];
}

// Controlla se c'è un reindirizzamento dopo il login
if (isAuthenticated() && isset($_SESSION['redirect_after_login'])) {
    $redirectTo = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);
    
    if (pageExists($redirectTo)) {
        header("Location: $redirectTo");
        exit;
    }
}
?> 