<?php
require_once '../config/constants.php';

// Set page title
$pageTitle = 'Gestione Revisioni';
$tableName = TABLE_REVISIONE;

// Definisci i campi di filtro specifici per questa pagina
$filterFields = [
    'numero',      // Filtro per numero di revisione
    'targa',       // Filtro per targa associata
    'dataRev',     // Filtro per data di revisione
    'esito',       // Filtro per esito della revisione (Superata, Non superata)
    'motivazione'  // Filtro per motivazione
];

// Descrizione della pagina e dei filtri disponibili
$pageDescription = "In questa sezione puoi visualizzare tutte le revisioni registrate nel sistema. Puoi filtrare le revisioni per numero, targa, data, esito o motivazione.";
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-clipboard-check"></i> <?php echo $pageTitle; ?></h1>
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
    <h4>Nessuna revisione trovata</h4>
    <p>Prova a modificare i filtri di ricerca per trovare i dati desiderati.</p>
</div>

<!-- Table container -->
<div id="table-container" data-table-name="<?php echo $tableName ?>" class="mb-4">
    <!-- Table will be loaded here via AJAX -->
    <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Caricamento...</span>
        </div>
        <p class="mt-2 text-muted">Caricamento dei dati in corso...</p>
    </div>
</div>

<script>
    // Document ready function
    $(document).ready(function() {
        // Bootstrap Icons
        $('head').append('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">');
    });
</script> 

<?php include("../template-parts/footer.php") ?>