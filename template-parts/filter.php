<div class="filter-bar mb-4">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control" id="search-input" placeholder="Cerca per telaio, marca, modello...">
      </div>
    </div>
    
    <div class="col-md-3">
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-funnel"></i></span>
        <select class="form-select" id="filter-marca">
          <option value="">Tutte le marche</option>
          <option value="Fiat">Fiat</option>
          <option value="Alfa Romeo">Alfa Romeo</option>
          <option value="Mercedes">Mercedes</option>
          <option value="BMW">BMW</option>
          <option value="Audi">Audi</option>
          <option value="Toyota">Toyota</option>
        </select>
      </div>
    </div>
    
    <div class="col-md-3">
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-calendar-range"></i></span>
        <select class="form-select" id="filter-anno">
          <option value="">Tutti gli anni</option>
          <?php
            $currentYear = date('Y');
            for ($i = $currentYear; $i >= 2000; $i--) {
              echo "<option value=\"$i\">$i</option>";
            }
          ?>
        </select>
      </div>
    </div>
    
    <div class="col-md-2 d-grid">
      <div class="btn-group w-100">
        <button class="btn btn-primary" type="button" id="apply-filter-btn">
          <i class="bi bi-check2"></i> Applica
        </button>
        <button class="btn btn-outline-secondary" type="button" id="reset-filter-btn">
          <i class="bi bi-x"></i> Reset
        </button>
      </div>
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
