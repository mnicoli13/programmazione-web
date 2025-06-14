<?php
require_once '../config/constants.php';

// Set page title
$pageTitle = 'Gestione Targhe Attive';
$tableName = TABLE_TARGA_ATTIVA;

// Definisci i campi di filtro specifici per questa pagina
$filterFields = [
    'targa',    // Filtro per numero di targa
    'veicolo',  // Filtro per veicolo (telaio) associato
    'dataEm'    // Filtro per data di emissione della targa
];

// Descrizione della pagina e dei filtri disponibili
$pageDescription = "In questa sezione puoi visualizzare tutte le targhe attualmente assegnate a un veicolo. Puoi filtrare le targhe attive per numero di targa, veicolo associato o data di emissione.";
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-check-circle"></i> <?php echo $pageTitle; ?></h1>
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
    <h4>Nessuna targa attiva trovata</h4>
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
        <h5 class="card-title"><i class="bi bi-info-circle text-primary"></i> Informazioni sulle targhe attive</h5>
        <p class="card-text">Le targhe attive sono quelle attualmente assegnate a un veicolo. Per visualizzare le targhe restituite, visitare la sezione "Targhe Restituite".</p>
        <div class="mt-3">
            <a href="targa.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-tag"></i> Vedi Tutte le Targhe
            </a>
            <a href="targa_restituita.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-return-left"></i> Vedi Targhe Restituite
            </a>
        </div>
    </div>
</div>

<?php include("../template-parts/footer.php") ?>