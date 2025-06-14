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
    <div class="table-responsive">
        <table id="myTable"
            data-table-name="Targa"
            class="table table-striped table-hover">
            <thead>
                <tr>
                <!-- Qui dichiari tutte le colonne che hai in `columns` -->
                <th class="sortable" data-column="numero" data-order="asc">
                    Numero <i class="bi bi-arrow-down-up text-muted small"></i>
                </th>
                <th class="sortable" data-column="targa" data-order="asc">
                    Targa <i class="bi bi-arrow-down-up text-muted small"></i>
                </th>
                <th class="sortable" data-column="dataRev" data-order="asc">
                    Data Revisione <i class="bi bi-arrow-down-up text-muted small"></i>
                </th>
                <th class="sortable" data-column="esito" data-order="asc">
                    Esito <i class="bi bi-arrow-down-up text-muted small"></i>
                </th>
                <th class="sortable" data-column="motivazione" data-order="asc">
                    Motivazione <i class="bi bi-arrow-down-up text-muted small"></i>
                </th>
            </thead>
            <tbody>
                <!-- sarà riempito dinamicamente -->
            </tbody>
        </table>
    </div>

    <!-- optional: empty state -->
    <div id="empty-state" style="display:none;">
        Nessun risultato
    </div>
    
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

<?php include("../template-parts/footer.php") ?>