<?php
require_once '../config/constants.php';
// Set page title
$pageTitle = 'Gestione Veicoli';
$tableName = TABLE_VEICOLO;

// Definisci i campi di filtro specifici per questa pagina
$filterFields = [
    'telaio',  // Filtro per numero di telaio
    'marca',   // Filtro per marca del veicolo
    'modello', // Filtro per modello del veicolo 
    'dataProd' // Filtro per data di produzione
];

// Descrizione della pagina e dei filtri disponibili
$pageDescription = "In questa sezione puoi visualizzare, aggiungere, modificare ed eliminare veicoli. Puoi utilizzare i filtri per cercare veicoli specifici per telaio, marca, modello o data di produzione.";
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-car-front"></i> <?php echo $pageTitle; ?></h1>
        <button id="add-veicolo-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVeicoloModal">
            <i class="bi bi-plus-circle"></i> Aggiungi Veicolo
        </button>
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
    <h4>Nessun veicolo trovato</h4>
    <p>Prova a modificare i filtri o aggiungi un nuovo veicolo utilizzando il pulsante in alto.</p>
    <button class="btn btn-primary" id="add-veicolo-empty-btn" data-bs-toggle="modal" data-bs-target="#addVeicoloModal">
        <i class="bi bi-plus-circle"></i> Aggiungi Veicolo
    </button>
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

<!-- Include modali -->
<?php include("../template-parts/modals/add_veicolo_modal.php"); ?>
<?php include("../template-parts/modals/edit_veicolo_modal.php"); ?>
<?php include("../template-parts/modals/delete_veicolo_modal.php"); ?>

<?php include("../template-parts/footer.php") ?>