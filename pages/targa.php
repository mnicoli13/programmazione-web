<?php

require_once '../config/constants.php';
// Set page title
$pageTitle = 'Gestione Targhe';
$tableName = TABLE_TARGA;
$filterFields = ['numero', 'dataEm', 'stato'];
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title"><i class="bi bi-tag"></i> <?php echo $pageTitle; ?></h1>
    </div>
    <p class="intro-text">
        In questa sezione puoi visualizzare tutte le targhe registrate nel sistema con le relative date di emissione e il loro stato attuale (Attiva, Restituita o Non assegnata). Usa i filtri per trovare targhe specifiche.
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
    <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Caricamento...</span>
        </div>
        <p class="mt-2 text-muted">Caricamento dei dati in corso...</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-info-circle text-primary"></i> Informazioni sulle targhe</h5>
        <p class="card-text">Le targhe vengono assegnate ai veicoli e possono essere attive o restituite. Puoi visualizzare i dettagli delle assegnazioni nelle sezioni "Targhe Attive" e "Targhe Restituite".</p>
        <div class="mt-3">
            <a href="?page=targa_attiva" class="btn btn-outline-primary me-2">
                <i class="bi bi-check-circle"></i> Vedi Targhe Attive
            </a>
            <a href="?page=targa_restituita" class="btn btn-outline-primary">
                <i class="bi bi-arrow-return-left"></i> Vedi Targhe Restituite
            </a>
        </div>
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
        
        // Aggiunta di un select per il filtro dello stato delle targhe
        const statoFilter = $('select[name="stato"]');
        if (statoFilter.length > 0) {
            // Rimuoviamo eventuali opzioni precedenti
            statoFilter.empty();
            
            // Aggiungiamo le opzioni
            statoFilter.append('<option value="">Tutti gli stati</option>');
            statoFilter.append('<option value="Attiva">Attiva</option>');
            statoFilter.append('<option value="Restituita">Restituita</option>');
            statoFilter.append('<option value="Non assegnata">Non assegnata</option>');
        }
    });
</script> 

<?php include("../template-parts/footer.php") ?>