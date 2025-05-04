# Piano di Progetto - Sistema di Gestione Veicoli

## 1. Panoramica del Progetto

Il progetto consiste in un'applicazione web per la gestione di veicoli, targhe e revisioni con le seguenti caratteristiche:

- Tecnologie: HTML, CSS, JS, PHP, jQuery, AJAX
- Framework CSS: Bootstrap 5 con tema bianco
- Database: my_mnicoli64 (già creato)
- Struttura dell'interfaccia: Header-Navigazione-FiltroRicerca-Contenuto-Footer
- Visualizzazione tabellare dei dati con filtri avanzati
- Funzionalità CRUD complete per la tabella "veicolo"

## 2. Struttura del Database

Il database contiene le seguenti tabelle:

```
Revisione (-numero, -+targa, dataRev, esito, motivazione*)
Targa (-numero, dataEm)
TargaAttiva (-+targa, +veicolo)
TargaRestituita (-+targa, +veicolo, dataRes)
Veicolo (-telaio, marca, modello, dataProd)
```

Con i seguenti vincoli:

- Revisione: (esito='positivo' AND motivazione is NULL) OR (esito='negativo' AND motivazione is NOT NULL)
- TargaAttiva: senza duplicati su veicolo

## 3. Architettura del Progetto

```
index.php
├── config/
│   ├── database.php
│   └── constants.php
├── template-parts/
│   ├── header.php
│   ├── footer.php
│   ├── navigation.php
│   └── filter.php
├── pages/
│   ├── veicolo.php
│   ├── targa.php
│   ├── revisione.php
│   ├── targa_attiva.php
│   └── targa_restituita.php
└── assets/
    ├── css/
    │   └── style.css
    └── js/
        ├── main.js
        ├── crud.js
        └── filter.js
```

## 4. Struttura dei File

### 4.1 File di Configurazione

- **config/database.php**: Configurazione della connessione al database
- **config/constants.php**: Costanti e configurazioni globali

### 4.2 Template Parts

- **template-parts/header.php**: Header del sito con logo e titolo
- **template-parts/footer.php**: Footer del sito con informazioni di copyright
- **template-parts/navigation.php**: Menu di navigazione tra le diverse pagine
- **template-parts/filter.php**: Componente di filtro riutilizzabile per tutte le tabelle

### 4.3 Pagine

- **index.php**: Pagina principale che include i template e gestisce il routing
- **pages/veicolo.php**: Visualizzazione e CRUD per la tabella Veicolo
- **pages/targa.php**: Visualizzazione per la tabella Targa
- **pages/revisione.php**: Visualizzazione per la tabella Revisione
- **pages/targa_attiva.php**: Visualizzazione per la tabella TargaAttiva
- **pages/targa_restituita.php**: Visualizzazione per la tabella TargaRestituita

### 4.4 Assets

- **assets/css/style.css**: Stili CSS personalizzati
- **assets/js/main.js**: Funzioni JavaScript generali
- **assets/js/crud.js**: Funzioni per le operazioni CRUD
- **assets/js/filter.js**: Funzioni per il filtraggio delle tabelle

## 5. Flusso di Dati

1. L'utente interagisce con l'interfaccia
2. L'interfaccia invia una richiesta AJAX
3. PHP elabora la richiesta
4. PHP esegue query al database
5. Il database restituisce i risultati
6. PHP formatta i risultati in JSON
7. AJAX riceve la risposta e aggiorna l'interfaccia
8. L'utente visualizza i risultati aggiornati

## 6. Implementazione Dettagliata

### 6.1 Connessione al Database

Creeremo un file di configurazione per la connessione al database utilizzando PDO per una maggiore sicurezza e flessibilità.

```php
<?php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_mnicoli64');
define('DB_USER', 'mnicoli64');
define('DB_PASS', 'fdY29KnAaN72');

function getConnection() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8");
        return $conn;
    } catch(PDOException $e) {
        echo "Errore di connessione: " . $e->getMessage();
        return null;
    }
}
?>
```

### 6.2 Struttura dell'Interfaccia

L'interfaccia seguirà la struttura richiesta:

- **Header**: Logo e titolo dell'applicazione
- **Navigazione**: Menu per navigare tra le diverse tabelle
- **FiltroRicerca**: Filtri avanzati per ogni tabella
- **Contenuto**: Visualizzazione tabellare dei dati
- **Footer**: Informazioni di copyright e link utili

### 6.3 Visualizzazione Tabellare

Ogni tabella sarà visualizzata in formato tabellare con:

- Intestazioni di colonna cliccabili per ordinamento
- Paginazione per gestire grandi quantità di dati
- Link per i dati collegabili ad altre tabelle

### 6.4 Sistema di Filtri

Implementeremo un sistema di filtri avanzati:

- Filtri per intervalli di date per i campi data
- Filtri testuali per gli altri campi
- Possibilità di combinare più filtri
- Reset dei filtri

### 6.5 Operazioni CRUD per Veicolo

Per la tabella Veicolo implementeremo:

- **Create**: Form per l'inserimento di nuovi veicoli
- **Read**: Visualizzazione dei dati dei veicoli
- **Update**: Form per la modifica dei dati dei veicoli
- **Delete**: Funzionalità per l'eliminazione dei veicoli con conferma

### 6.6 Gestione delle Relazioni

Implementeremo la gestione delle relazioni tra le tabelle:

- Link tra Veicolo e TargaAttiva/TargaRestituita
- Link tra Targa e Revisione

## 7. Tecnologie e Librerie

### 7.1 Frontend

- **HTML5**: Struttura delle pagine
- **CSS3/Bootstrap 5**: Stile e layout responsive
- **JavaScript/jQuery**: Interattività lato client
- **AJAX**: Comunicazione asincrona con il server

### 7.2 Backend

- **PHP**: Logica lato server e interazione con il database
- **PDO**: Connessione sicura al database

## 8. Piano di Implementazione

### Fase 1: Setup Iniziale

1. Configurazione della connessione al database
2. Creazione della struttura di base dei file
3. Implementazione di header, footer e navigazione

### Fase 2: Implementazione delle Funzionalità di Base

1. Creazione delle query per recuperare i dati dalle tabelle
2. Implementazione della visualizzazione tabellare
3. Creazione del sistema di filtri

### Fase 3: Implementazione CRUD per Veicolo

1. Creazione del form per l'inserimento di nuovi veicoli
2. Implementazione della funzionalità di modifica
3. Implementazione della funzionalità di eliminazione

### Fase 4: Ottimizzazione e Test

1. Ottimizzazione delle query e delle prestazioni
2. Test di tutte le funzionalità
3. Correzione di eventuali bug

## 9. Considerazioni sulla Sicurezza

1. Utilizzo di prepared statements per prevenire SQL injection
2. Validazione e sanitizzazione degli input
3. Gestione degli errori e dei messaggi di errore

## 10. Considerazioni sull'Usabilità

1. Design responsive per adattarsi a diverse dimensioni di schermo
2. Feedback visivo per le azioni dell'utente
3. Messaggi di conferma per le operazioni critiche
