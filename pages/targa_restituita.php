<?php

require_once '../config/constants.php';
// Set page title
$pageTitle = 'Gestione Targhe Restituite';
$tableName = TABLE_TARGA_RESTITUITA;

// Definisci i campi di filtro specifici per questa pagina
$filterFields = [
    'targa',    // Filtro per numero di targa
    'veicolo',  // Filtro per veicolo (telaio) associato
    'dataEm',   // Filtro per data di emissione
    'dataRes'   // Filtro per data di restituzione
];

// Descrizione della pagina e dei filtri disponibili
$pageDescription = "In questa sezione puoi visualizzare tutte le targhe che sono state restituite e non sono più attive. Puoi filtrare le targhe restituite per numero di targa, veicolo associato, data di emissione o data di restituzione.";
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-arrow-return-left"></i> <?php echo $pageTitle; ?></h1>
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
    <h4>Nessuna targa restituita trovata</h4>
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

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-info-circle text-primary"></i> Informazioni sulle targhe restituite</h5>
        <p class="card-text">Le targhe restituite sono quelle che sono state precedentemente assegnate a un veicolo ma ora non sono più attive. Per visualizzare le targhe attualmente assegnate, visitare la sezione "Targhe Attive".</p>
        <div class="mt-3">
            <a href="targa.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-tag"></i> Vedi Tutte le Targhe
            </a>
            <a href="targa_attiva.php" class="btn btn-outline-primary">
                <i class="bi bi-check-circle"></i> Vedi Targhe Attive
            </a>
        </div>
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