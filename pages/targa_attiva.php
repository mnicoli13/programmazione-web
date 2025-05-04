<?php
require_once '../config/constants.php';

// Set page title
$pageTitle = 'Gestione Targhe Attive';
$tableName = TABLE_TARGA_ATTIVA;
// Define filter fields for this page
$filterFields = ['targa', 'veicolo'];
?>

<?php include("../template-parts/header.php") ?>

<?php include("../template-parts/navigation.php") ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<!-- Notification area -->
<div id="notification-area"></div>

<!-- Include the filter component -->
<?php include("../template-parts/filter.php"); ?>

<!-- Table container -->
<div id="table-container" data-table-name="<?php echo $tableName ?>">
    <!-- Table will be loaded here via AJAX -->
</div>

<script>
    // Document ready function
    $(document).ready(function() {
        // Bootstrap Icons
        $('head').append('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">');
    });
</script> 

<?php include("../template-parts/footer.php") ?>