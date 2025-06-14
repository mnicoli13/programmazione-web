// Document ready function for filter functionality
$(document).ready(function () {
  // Inizializza tooltips per i filtri
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Submit filter form
  $("#filter-form").on("submit", function (e) {
    e.preventDefault();
    const filterData = $(this).serialize();

    // Show loading state
    $("#table-container").html(
      '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Caricamento...</span></div><p class="mt-2 text-muted">Caricamento dei dati in corso...</p></div>'
    );

    loadTableData(filterData);

    // Aggiorna status filtri
    updateFilterStatus();

    // Show feedback that filters are applied
    showNotification(
      '<i class="bi bi-funnel"></i> Filtri applicati con successo',
      "info"
    );
  });

  // Reset filter form
  $("#reset-filter").on("click", function () {
    $("#filter-form")[0].reset();
    loadTableData("");

    // Nascondi status filtri
    $("#filter-status").addClass("d-none");

    // Show feedback that filters are reset
    showNotification(
      '<i class="bi bi-arrow-counterclockwise"></i> Filtri reimpostati',
      "info"
    );
  });

  // Clear filters
  $("#filter-clear-btn").on("click", function () {
    $("#reset-filter").click();
  });

  // Toggle filter collapse - aggiorna icona
  $('[data-bs-toggle="collapse"]').on("click", function () {
    const icon = $(this).find("i");
    icon.toggleClass("bi-chevron-up bi-chevron-down");
  });

  // Gestione URL parametri per filtri con deep linking
  const urlParams = new URLSearchParams(window.location.search);
  let hasFilters = false;

  // Se ci sono parametri di filtro, li applichiamo
  $("#filter-form")
    .find("input, select")
    .each(function () {
      const fieldName = $(this).attr("name");
      if (urlParams.has(fieldName)) {
        const fieldValue = urlParams.get(fieldName);
        $(this).val(fieldValue);
        hasFilters = true;
      }
    });

  if (hasFilters) {
    // Submit form se abbiamo trovato parametri
    $("#filter-form").trigger("submit");
  } else {
    // Carica dati senza filtri
    loadTableData("");
  }
});

// Function to update filter status indicators
function updateFilterStatus() {
  const activeFilters = [];

  $("#filter-form")
    .find("input, select")
    .each(function () {
      const fieldValue = $(this).val();
      if (fieldValue) {
        const fieldName = $(this).attr("name");
        const fieldLabel = $("label[for='filter-" + fieldName + "']").text();
        activeFilters.push(fieldLabel + ": " + fieldValue);
      }
    });

  if (activeFilters.length > 0) {
    $("#filter-status-text").html(
      '<i class="bi bi-funnel-fill me-1"></i> Filtri attivi: ' +
        activeFilters.join(", ")
    );
    $("#filter-status").removeClass("d-none");
  } else {
    $("#filter-status").addClass("d-none");
  }
}

// Function to show notifications
function showNotification(message, type = "info") {
  let notificationHtml =
    '<div class="alert alert-' +
    type +
    ' alert-dismissible fade show" role="alert">';
  notificationHtml += message;
  notificationHtml +=
    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
  notificationHtml += "</div>";

  $("#notification-area").html(notificationHtml);

  // Auto-hide after 5 seconds
  setTimeout(function () {
    $(".alert").alert("close");
  }, 5000);
}

// Function to load table data with filters
function loadTableData(filterData) {
  const tableName = $("#table-container").data("table-name");

  console.log("filterData: ", filterData);
  // Show loading state
  $("#table-container").html(
    '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Caricamento...</span></div><p class="mt-2 text-muted">Caricamento dei dati in corso...</p></div>'
  );

  // Hide empty state if visible
  $("#empty-state").hide();

  $.ajax({
    url: "../api/crud/get_data.php",
    type: "GET",
    data: filterData + "&table=" + tableName,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        if (response.data.length === 0) {
          // Show empty state
          $("#empty-state").show();
          $("#table-container").hide();
        } else {
          $("#empty-state").hide();
          $("#table-container").show();

          // ─── ESTRAGGO sort e order da filterData ─────────────────────────────
          const params = new URLSearchParams(filterData);
          const sortField = params.get("sort") || null;
          const sortOrder = params.get("order") || null;
          // ──────────────────────────────────────────────────────────────────────

          renderTable(
            response.data,
            response.columns,
            tableName,
            sortField,
            sortOrder
          );
        }
      } else {
        $("#table-container").html(
          '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Errore nel caricamento dei dati: ' +
            response.message +
            "</div>"
        );
      }
    },
    error: function (xhr, status, error) {
      $("#table-container").html(
        '<div class="alert alert-danger"><i class="bi bi-wifi-off"></i> Errore di comunicazione con il server: ' +
          error +
          "</div>"
      );
    },
  });
}

// Function to render table with data, including sort state
function renderTable(
  data,
  columns,
  tableName,
  sortField = null,
  sortOrder = "asc"
) {
  // Build table header
  let tableHtml =
    '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr>';

  columns.forEach(function (column) {
    // Check if this is the sorted column
    const isSorted = column.name === sortField;
    const order = isSorted ? sortOrder : "asc";
    // Choose the right icon
    const iconClass = isSorted
      ? sortOrder === "asc"
        ? "bi-arrow-up"
        : "bi-arrow-down"
      : "bi-arrow-down-up text-muted small";

    tableHtml +=
      `<th class="sortable" data-column="${column.name}" data-order="${order}">` +
      `${column.label} <i class="bi ${iconClass}"></i></th>`;
  });

  // Add action column if needed
  if (tableName === "Veicolo") {
    tableHtml += '<th class="text-center">Azioni</th>';
  }

  tableHtml += "</tr></thead><tbody>";

  // Build rows
  data.forEach(function (row) {
    tableHtml += "<tr>";
    columns.forEach(function (column) {
      let cell = row[column.name] || "";

      if (column.type === "date") {
        cell = cell ? formatDate(cell) : "";
      } else if (column.type === "status") {
        let badgeClass = "",
          icon = "";
        switch (cell) {
          case "Attiva":
            badgeClass = "bg-success";
            icon = "bi-check-circle-fill";
            break;
          case "Restituita":
            badgeClass = "bg-warning text-dark";
            icon = "bi-arrow-return-left";
            break;
          default:
            badgeClass = "bg-secondary";
            icon = "bi-dash-circle";
        }
        cell = `<span class="badge ${badgeClass}"><i class="bi ${icon} me-1"></i>${cell}</span>`;
      } else if (column.isLink) {
        cell = `<a href="#" class="table-link" data-target="${
          column.linkTarget
        }" data-value="${
          row[column.name]
        }" title="Clicca per dettagli">${cell}</a>`;
      }

      tableHtml += `<td>${cell}</td>`;
    });

    // Action buttons
    if (tableName === "Veicolo") {
      tableHtml +=
        '<td class="text-center">' +
        `<button class="btn btn-sm btn-primary me-1 edit-btn" data-id="${row.telaio}" title="Modifica"><i class="bi bi-pencil"></i></button>` +
        `<button class="btn btn-sm btn-danger delete-btn" data-id="${row.telaio}" title="Elimina"><i class="bi bi-trash"></i></button>` +
        "</td>";
    }

    tableHtml += "</tr>";
  });

  tableHtml += "</tbody></table></div>";

  // Result count
  tableHtml =
    `<div class="mb-3 text-muted small"><i class="bi bi-info-circle"></i> Trovati ${data.length} risultati</div>` +
    tableHtml;

  // Inject HTML
  $("#table-container").html(tableHtml);

  // Re-bind sorting handler
  $(".sortable")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      const $th = $(this);
      const column = $th.data("column");
      const current = $th.data("order") || "asc";
      const next = current === "asc" ? "desc" : "asc";

      // Reset all headers
      $(".sortable").removeData("order").find("i").remove();

      // Set new state and icon
      $th
        .data("order", next)
        .append(
          ` <i class="bi bi-arrow-${next === "asc" ? "up" : "down"}"></i>`
        );

      // Reload data with new sort parameters
      const filterData = $("#filter-form").serialize();
      const params = `${filterData}&sort=${encodeURIComponent(
        column
      )}&order=${next}`;
      loadTableData(params);
    });

  // Re-bind detail links
  $(".table-link")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      const target = $(this).data("target");
      const value = $(this).data("value");
      window.location.href = `../pages/${target}.php?id=${value}`;
    });
}

// Format date function
function formatDate(dateString) {
  const options = { day: "2-digit", month: "2-digit", year: "numeric" };
  const date = new Date(dateString);
  return date.toLocaleDateString("it-IT", options);
}
