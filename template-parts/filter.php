<div class="card shadow-sm mb-4">
  <div class="card-header bg-white">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtri</h5>
      <button class="btn btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
        <i class="bi bi-chevron-up"></i>
      </button>
    </div>
  </div>
  <div class="collapse show" id="filterCollapse">
    <div class="card-body">
      <form id="filter-form" class="row g-3">
        <?php
        // Genera campi di filtro in base alla pagina attuale
        if (isset($filterFields) && is_array($filterFields)) {
            foreach ($filterFields as $field) {
                echo '<div class="col-md-3">';
                echo '<div class="form-group">';
                
                // Label personalizzata per ciascun campo
                $labelText = '';
                $placeholder = '';
                $inputType = 'text';
                $iconClass = 'bi-search';
                
                switch ($field) {
                    case 'telaio':
                        $labelText = 'Numero Telaio';
                        $placeholder = 'Cerca per telaio...';
                        $iconClass = 'bi-upc-scan';
                        break;
                    case 'marca':
                        $labelText = 'Marca';
                        $placeholder = 'Cerca per marca...';
                        $iconClass = 'bi-building';
                        break;
                    case 'modello':
                        $labelText = 'Modello';
                        $placeholder = 'Cerca per modello...';
                        $iconClass = 'bi-car-front';
                        break;
                    case 'dataProd':
                        $labelText = 'Data Produzione';
                        $placeholder = 'Seleziona data...';
                        $inputType = 'date';
                        $iconClass = 'bi-calendar-date';
                        break;
                    case 'numero':
                        $labelText = 'Numero';
                        $placeholder = 'Cerca per numero...';
                        $iconClass = 'bi-tag';
                        break;
                    case 'dataEm':
                        $labelText = 'Data Emissione';
                        $placeholder = 'Seleziona data...';
                        $inputType = 'date';
                        $iconClass = 'bi-calendar-date';
                        break;
                    case 'stato':
                        $labelText = 'Stato Targa';
                        $placeholder = 'Seleziona stato...';
                        $inputType = 'select';
                        $iconClass = 'bi-flag';
                        break;
                    case 'targa':
                        $labelText = 'Targa';
                        $placeholder = 'Cerca per targa...';
                        $iconClass = 'bi-tag';
                        break;
                    case 'veicolo':
                        $labelText = 'Veicolo';
                        $placeholder = 'Cerca per veicolo...';
                        $iconClass = 'bi-car-front';
                        break;
                    case 'dataRev':
                        $labelText = 'Data Revisione';
                        $placeholder = 'Seleziona data...';
                        $inputType = 'date';
                        $iconClass = 'bi-calendar-check';
                        break;
                    case 'dataRes':
                        $labelText = 'Data Restituzione';
                        $placeholder = 'Seleziona data...';
                        $inputType = 'date';
                        $iconClass = 'bi-calendar-x';
                        break;
                    case 'esito':
                        $labelText = 'Esito Revisione';
                        $placeholder = 'Seleziona esito...';
                        $inputType = 'select';
                        $iconClass = 'bi-clipboard-check';
                        break;
                    case 'motivazione':
                        $labelText = 'Motivazione';
                        $placeholder = 'Cerca per motivazione...';
                        $iconClass = 'bi-chat-text';
                        break;
                    default:
                        $labelText = ucfirst($field);
                        $placeholder = 'Cerca per ' . $field . '...';
                        break;
                }
                
                echo '<label for="filter-' . $field . '" class="form-label">' . $labelText . '</label>';
                echo '<div class="input-group">';
                echo '<span class="input-group-text bg-light"><i class="bi ' . $iconClass . '"></i></span>';
                
                if ($inputType === 'select') {
                    echo '<select class="form-select" id="filter-' . $field . '" name="' . $field . '">';
                    echo '<option value="">' . $placeholder . '</option>';
                    
                    // Opzioni per i campi select
                    if ($field === 'stato') {
                        echo '<option value="Attiva">Attiva</option>';
                        echo '<option value="Restituita">Restituita</option>';
                        echo '<option value="Non assegnata">Non assegnata</option>';
                    } elseif ($field === 'esito') {
                        echo '<option value="Superata">Superata</option>';
                        echo '<option value="Non superata">Non superata</option>';
                    }
                    
                    echo '</select>';
                } else {
                    echo '<input type="' . $inputType . '" class="form-control" id="filter-' . $field . '" name="' . $field . '" placeholder="' . $placeholder . '">';
                }
                
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
        
        <div class="col-md-12 text-end">
          <button type="submit" class="btn btn-primary me-2" id="apply-filter">
            <i class="bi bi-check2"></i> Applica Filtri
          </button>
          <button type="button" class="btn btn-outline-secondary" id="reset-filter">
            <i class="bi bi-arrow-counterclockwise"></i> Reimposta
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="filter-status" class="d-none mb-3 fadeIn">
  <div class="d-flex align-items-center">
    <div class="me-auto">
      <span id="filter-status-text" class="badge bg-info text-white"></span>
    </div>
    <div>
      <button id="filter-clear-btn" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-x-circle"></i> Rimuovi filtri
      </button>
    </div>
  </div>
</div>
