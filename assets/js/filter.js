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
          renderTable(response.data, response.columns, tableName);
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

// Function to render table with data
function renderTable(data, columns, tableName) {
  let tableHtml =
    '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr>';

  // Table headers with sorting
  columns.forEach(function (column) {
    tableHtml +=
      '<th class="sortable" data-column="' +
      column.name +
      '">' +
      column.label +
      ' <i class="bi bi-arrow-down-up text-muted small"></i></th>';
  });

  // Add action column if needed
  if (tableName === "Veicolo") {
    tableHtml += '<th class="text-center">Azioni</th>';
  }

  tableHtml += "</tr></thead><tbody>";

  // Table rows
  data.forEach(function (row) {
    tableHtml += "<tr>";

    columns.forEach(function (column) {
      const value = row[column.name] || "";

      // Format value based on column type
      if (column.type === "date") {
        const formattedDate = value ? formatDate(value) : "";
        tableHtml += "<td>" + formattedDate + "</td>";
      } else if (column.type === "status") {
        // Gestione speciale per lo stato delle targhe
        let badgeClass = "";
        let iconClass = "";

        if (value === "Attiva") {
          badgeClass = "bg-success";
          iconClass = "bi-check-circle-fill";
        } else if (value === "Restituita") {
          badgeClass = "bg-warning text-dark";
          iconClass = "bi-arrow-return-left";
        } else {
          badgeClass = "bg-secondary";
          iconClass = "bi-dash-circle";
        }

        tableHtml +=
          '<td><span class="badge ' +
          badgeClass +
          '">' +
          '<i class="bi ' +
          iconClass +
          ' me-1"></i>' +
          value +
          "</span></td>";
      } else if (column.isLink) {
        tableHtml +=
          '<td><a href="#" class="table-link" data-target="' +
          column.linkTarget +
          '" data-value="' +
          value +
          '" title="Clicca per visualizzare dettagli">' +
          value +
          "</a></td>";
      } else {
        tableHtml += "<td>" + value + "</td>";
      }
    });

    // Add action buttons if needed
    if (tableName === "Veicolo") {
      tableHtml +=
        '<td class="text-center">' +
        '<button class="btn btn-sm btn-primary me-1 edit-btn" data-id="' +
        row.telaio +
        '" title="Modifica veicolo"><i class="bi bi-pencil"></i> Modifica</button>' +
        '<button class="btn btn-sm btn-danger delete-btn" data-id="' +
        row.telaio +
        '" title="Elimina veicolo"><i class="bi bi-trash"></i> Elimina</button>' +
        "</td>";
    }

    tableHtml += "</tr>";
  });

  tableHtml += "</tbody></table></div>";

  // Add result count
  tableHtml =
    '<div class="mb-3 text-muted small"><i class="bi bi-info-circle"></i> Trovati ' +
    data.length +
    " risultati</div>" +
    tableHtml;

  // Render table and pagination
  $("#table-container").html(tableHtml);

  // Add active indicator to newly sorted column
  $(".sortable").on("click", function () {
    const column = $(this).data("column");
    const currentOrder = $(this).data("order") || "asc";
    const newOrder = currentOrder === "asc" ? "desc" : "asc";

    // Remove all sorting indicators
    $(".sortable")
      .removeData("order")
      .find("i")
      .attr("class", "bi bi-arrow-down-up text-muted small");

    // Add sorting indicator to current column
    $(this).data("order", newOrder);
    const iconClass =
      newOrder === "asc"
        ? "bi bi-arrow-up text-primary small"
        : "bi bi-arrow-down text-primary small";
    $(this).find("i").attr("class", iconClass);

    // Get current filter values
    const filterData = $("#filter-form").serialize();

    // Load data with new sorting
    loadTableData(filterData + "&sort=" + column + "&order=" + newOrder);
  });

  // Handle detail links
  $(".table-link").on("click", function (e) {
    e.preventDefault();
    const target = $(this).data("target");
    const value = $(this).data("value");

    // Redirect to detail page
    window.location.href = "../pages/" + target + ".php?id=" + value;
  });
}

// Format date function
function formatDate(dateString) {
  const options = { day: "2-digit", month: "2-digit", year: "numeric" };
  const date = new Date(dateString);
  return date.toLocaleDateString("it-IT", options);
}
