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
    <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Caricamento...</span>
        </div>
        <p class="mt-2 text-muted">Caricamento dei dati in corso...</p>
    </div>
</div>

<!-- Include modali -->
<?php include("../template-parts/modals/add_veicolo_modal.php"); ?>
<?php include("../template-parts/modals/edit_veicolo_modal.php"); ?>
<?php include("../template-parts/modals/delete_veicolo_modal.php"); ?>

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
            $('#addVeicoloModal').modal('show');
        });
        
        // Listener per il pulsante salva del modale di aggiunta
        $('#save-add-veicolo').on('click', function() {
            const form = $('#add-veicolo-form');
            
            // Validazione del form
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }
            
            // Raccolta dati
            const formData = new FormData(form[0]);
            
            // Invio dati
            $.ajax({
                url: '../api/crud/add_veicolo.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Chiudi il modale
                        $('#addVeicoloModal').modal('hide');
                        
                        // Resetta il form
                        form[0].reset();
                        
                        // Mostra notifica
                        showNotification('<i class="bi bi-check-circle"></i> ' + response.message, 'success');
                        
                        // Ricarica i dati
                        loadTableData('');
                    } else {
                        showNotification('<i class="bi bi-exclamation-triangle"></i> ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showNotification('<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server', 'danger');
                }
            });
        });
        
        // Handler per il pulsante modifica nella tabella
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            
            // Carica i dati del veicolo
            $.ajax({
                url: '../api/crud/get_veicolo.php',
                type: 'GET',
                data: { telaio: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Popolamento form
                        $('#edit-original-telaio').val(response.data.telaio);
                        $('#edit-telaio').val(response.data.telaio);
                        $('#edit-marca').val(response.data.marca);
                        $('#edit-modello').val(response.data.modello);
                        $('#edit-dataProd').val(response.data.dataProd);
                        
                        // Mostra modale
                        $('#editVeicoloModal').modal('show');
                    } else {
                        showNotification('<i class="bi bi-exclamation-triangle"></i> ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showNotification('<i class="bi bi-exclamation-triangle"></i> Errore durante il caricamento dei dati del veicolo', 'danger');
                }
            });
        });
        
        // Listener per il pulsante salva del modale di modifica
        $('#save-edit-veicolo').on('click', function() {
            const form = $('#edit-veicolo-form');
            
            // Validazione del form
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }
            
            // Raccolta dati
            const formData = new FormData(form[0]);
            
            // Invio dati
            $.ajax({
                url: '../api/crud/update_veicolo.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Chiudi il modale
                        $('#editVeicoloModal').modal('hide');
                        
                        // Mostra notifica
                        showNotification('<i class="bi bi-check-circle"></i> ' + response.message, 'success');
                        
                        // Ricarica i dati
                        loadTableData('');
                    } else {
                        showNotification('<i class="bi bi-exclamation-triangle"></i> ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showNotification('<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server', 'danger');
                }
            });
        });
        
        // Handler per il pulsante elimina nella tabella
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            $('#delete-telaio').val(id);
            $('#delete-telaio-display').text(id);
            $('#deleteVeicoloModal').modal('show');
        });
        
        // Listener per il pulsante conferma del modale di eliminazione
        $('#confirm-delete-veicolo').on('click', function() {
            const form = $('#delete-veicolo-form');
            const formData = new FormData(form[0]);
            
            $.ajax({
                url: '../api/crud/delete_veicolo.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Chiudi il modale
                        $('#deleteVeicoloModal').modal('hide');
                        
                        // Mostra notifica
                        showNotification('<i class="bi bi-check-circle"></i> ' + response.message, 'success');
                        
                        // Ricarica i dati
                        loadTableData('');
                    } else {
                        showNotification('<i class="bi bi-exclamation-triangle"></i> ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showNotification('<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server', 'danger');
                }
            });
        });
        
        // Funzione per mostrare notifiche
        function showNotification(message, type = "info") {
            let notificationHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">';
            notificationHtml += message;
            notificationHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            notificationHtml += '</div>';
            
            $("#notification-area").html(notificationHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        }
    });
</script>

<?php include("../template-parts/footer.php") ?>