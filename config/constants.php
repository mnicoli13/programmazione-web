<?php
// Site constants
define('SITE_TITLE', 'Sistema Gestione Veicoli');
define('SITE_DESCRIPTION', 'Sistema per la gestione di veicoli, targhe e revisioni');
define('COPYRIGHT', '&copy; ' . date('Y') . ' ' . SITE_TITLE . ' - Tutti i diritti riservati');

// Database table names
define('TABLE_VEICOLO', 'Veicolo');
define('TABLE_TARGA', 'Targa');
define('TABLE_REVISIONE', 'Revisione');
define('TABLE_Utenti', 'Utenti');

// Authentication constants
define('AUTH_SESSION_DURATION', 60 * 60 * 24); // 1 day in seconds
define('AUTH_REMEMBER_DURATION', 60 * 60 * 24 * 30); // 30 days in seconds
define('AUTH_TOKEN_SECRET', 'your-secret-key-change-this-in-production');

// Password requirements
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_REQUIRES_SPECIAL', true);
define('PASSWORD_REQUIRES_UPPERCASE', true);
define('PASSWORD_REQUIRES_LOWERCASE', true);
define('PASSWORD_REQUIRES_NUMBER', true);

// File paths
define('ROOT_PATH', dirname(__DIR__));
define('API_PATH', ROOT_PATH . '/api');
define('TEMPLATE_PATH', ROOT_PATH . '/template-parts');
define('PAGES_PATH', ROOT_PATH . '/pages');

// Table names
define('TABLE_TARGA_ATTIVA', 'TargaAttiva');
define('TABLE_TARGA_RESTITUITA', 'TargaRestituita');

// Page paths
define('PATH_ROOT', '/');
define('PATH_VEICOLO', 'pages/veicolo.php');
define('PATH_TARGA', 'pages/targa.php');
define('PATH_REVISIONE', 'pages/revisione.php');
define('PATH_TARGA_ATTIVA', 'pages/targa_attiva.php');
define('PATH_TARGA_RESTITUITA', 'pages/targa_restituita.php');
?> 