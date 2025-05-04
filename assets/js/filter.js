// Document ready function for filter functionality
$(document).ready(function () {
  // Submit filter form
  $("#filter-form").on("submit", function (e) {
    e.preventDefault();
    const filterData = $(this).serialize();
    loadTableData(filterData);

    // Show feedback that filters are applied
    showNotification('<i class="bi bi-funnel"></i> Filtri applicati', "info");
  });

  // Reset filter form
  $("#reset-filter").on("click", function () {
    $("#filter-form")[0].reset();
    loadTableData("");

    // Show feedback that filters are reset
    showNotification(
      '<i class="bi bi-arrow-counterclockwise"></i> Filtri reimpostati',
      "info"
    );
  });

  // Handle URL parameters for filtering
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("filter")) {
    const filterValue = urlParams.get("filter");
    const filterTarget = urlParams.get("target") || "telaio"; // Default to telaio if not specified

    // Set the filter field value
    $("#filter-" + filterTarget).val(filterValue);

    // Submit the filter form
    $("#filter-form").trigger("submit");
  } else {
    // Load initial data without filters
    loadTableData("");
  }

  // Toggle filter collapse
  $('[data-bs-toggle="collapse"]').on("click", function () {
    const icon = $(this).find("i");
    icon.toggleClass("bi-chevron-up bi-chevron-down");
  });
});

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
}
