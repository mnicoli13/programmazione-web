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

// Get targa from URL if present
$targaFromUrl = isset($_GET['targa']) ? $_GET['targa'] : null;
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
                    <th class="sortable" data-column="dataEm" data-order="asc">
                        Data Emissione <i class="bi bi-arrow-down-up text-muted small"></i>
                    </th>
                    <th class="sortable" data-column="stato" data-order="asc">
                        Stato <i class="bi bi-arrow-down-up text-muted small"></i>
                    </th>
                </tr>
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

<?php if ($targaFromUrl): ?>
<script>
    // Wait for document ready
    $(document).ready(function() {
        // Set the filter value
        $('#filter-numero').val('<?php echo htmlspecialchars($targaFromUrl); ?>');
        
        // Trigger the filter form submission
        $('#filter-form').submit();
    });
</script>
<?php endif; ?>

<?php include("../template-parts/footer.php") ?>