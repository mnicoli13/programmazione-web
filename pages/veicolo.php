<?php
require_once '../config/constants.php';
// Set page title
$pageTitle = 'Gestione Veicoli';
$tableName = TABLE_VEICOLO;
$filterFields = ['telaio', 'marca', 'modello', 'dataProd'];
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-car-front"></i> <?php echo $pageTitle; ?></h1>
        <button id="add-veicolo-btn" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Aggiungi Veicolo
        </button>
    </div>
    <p class="intro-text">
        In questa sezione puoi visualizzare, aggiungere, modificare ed eliminare veicoli. Usa i filtri per cercare veicoli specifici.
    </p>
</div>

<!-- Notification area -->
<div id="notification-area"></div>

<!-- Vehicle form container -->
<div id="veicolo-form-container" class="card shadow-sm mb-4" style="display: none;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 id="veicolo-form-title" class="mb-0"><i class="bi bi-pencil-square"></i> Aggiungi Veicolo</h5>
        <button type="button" class="btn-close" aria-label="Close" id="close-form-btn"></button>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle me-2"></i> Compila tutti i campi obbligatori contrassegnati con * per procedere.
        </div>
        
        <form id="veicolo-form" class="row g-3">
            <input type="hidden" id="veicolo-form-action" name="action" value="add">
            
            <div class="col-md-6">
                <label for="telaio" class="form-label">Numero di Telaio *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" class="form-control" id="telaio" name="telaio" required>
                </div>
                <div class="form-help">Inserisci il numero di telaio univoco del veicolo</div>
            </div>
            
            <div class="col-md-6">
                <label for="marca" class="form-label">Marca *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <input type="text" class="form-control" id="marca" name="marca" required>
                </div>
                <div class="form-help">Inserisci la casa produttrice del veicolo</div>
            </div>
            
            <div class="col-md-6">
                <label for="modello" class="form-label">Modello *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                    <input type="text" class="form-control" id="modello" name="modello" required>
                </div>
                <div class="form-help">Inserisci il modello specifico del veicolo</div>
            </div>
            
            <div class="col-md-6">
                <label for="dataProd" class="form-label">Data Produzione *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="date" class="form-control" id="dataProd" name="dataProd" required>
                </div>
                <div class="form-help">Seleziona la data di produzione del veicolo</div>
            </div>
            
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-save"></i> Salva
                </button>
                <button type="button" id="cancel-form-btn" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annulla
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include the filter component -->
<?php include("../template-parts/filter.php"); ?>

<!-- Empty state for no results -->
<div id="empty-state" class="empty-state" style="display: none;">
    <i class="bi bi-search"></i>
    <h4>Nessun veicolo trovato</h4>
    <p>Prova a modificare i filtri o aggiungi un nuovo veicolo utilizzando il pulsante in alto.</p>
    <button class="btn btn-primary" id="add-veicolo-empty-btn">
        <i class="bi bi-plus-circle"></i> Aggiungi Veicolo
    </button>
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
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add Vehicle from empty state
        $('#add-veicolo-empty-btn').on('click', function() {
            $('#add-veicolo-btn').click();
        });
        
        // Close form on X click
        $('#close-form-btn').on('click', function() {
            $('#veicolo-form-container').slideUp();
        });
    });
</script>

<?php include("../template-parts/footer.php") ?>