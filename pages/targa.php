<?php

require_once '../config/constants.php';
// Set page title
$pageTitle = 'Gestione Targhe';
$tableName = TABLE_TARGA;

// Definisci i campi di filtro specifici per questa pagina
$filterFields = [
    'numero',  // Filtro per numero di targa
    'dataEm',  // Filtro per data emissione
    'stato'    // Filtro per stato della targa (Attiva, Restituita, Non assegnata)
];

// Descrizione della pagina e dei filtri disponibili
$pageDescription = "In questa sezione puoi visualizzare tutte le targhe registrate nel sistema con le relative date di emissione e il loro stato attuale (Attiva, Restituita o Non assegnata). Puoi filtrare le targhe per numero, data di emissione o stato.";
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-tag"></i> <?php echo $pageTitle; ?></h1>
    </div>
    <p class="intro-text">
        <?php echo $pageDescription; ?>
    </p>
</div>

<!-- Notification area -->
<div id="notification-area"></div>

<!-- Include the filter component -->
<?php include("../template-parts/filter.php"); ?>

<!-- Empty state for no results -->
<div id="empty-state" class="empty-state" style="display: none;">
    <i class="bi bi-search"></i>
    <h4>Nessuna targa trovata</h4>
    <p>Prova a modificare i filtri di ricerca per trovare i dati desiderati.</p>
</div>

<!-- Table container -->
<div id="table-container" data-table-name="<?php echo $tableName ?>" class="mb-4">
    <!-- Table will be loaded here via AJAX -->
    <div class="table-loader">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Caricamento...</span>
            </div>
            <p class="mt-2 text-muted">Caricamento dei dati in corso...</p>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-info-circle text-primary"></i> Informazioni sulle targhe</h5>
        <p class="card-text">Le targhe vengono assegnate ai veicoli e possono essere attive o restituite. Puoi visualizzare i dettagli delle assegnazioni nelle sezioni "Targhe Attive" e "Targhe Restituite".</p>
        <div class="mt-3">
            <a href="targa_attiva.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-check-circle"></i> Vedi Targhe Attive
            </a>
            <a href="targa_restituita.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-return-left"></i> Vedi Targhe Restituite
            </a>
        </div>
    </div>
</div>

<?php include("../template-parts/footer.php") ?>