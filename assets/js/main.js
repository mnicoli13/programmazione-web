// Document ready function
$(document).ready(function () {
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $(".back-to-top").fadeIn();
    } else {
      $(".back-to-top").fadeOut();
    }
  });

  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 300);
    return false;
  });

  // Create notification container if it doesn't exist
  if (!$("#notification-container").length) {
    $("body").append(
      '<div id="notification-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1051;"></div>'
    );
  }

  // Handle table sorting
  $(".sortable").on("click", function () {
    const column = $(this).data("column");
    const currentOrder = $(this).data("order") || "asc";
    const newOrder = currentOrder === "asc" ? "desc" : "asc";

    // Remove all sorting indicators
    $(".sortable").removeData("order").find("i").remove();

    // Add sorting indicator to current column
    $(this).data("order", newOrder);
    const icon = newOrder === "asc" ? "bi-arrow-up" : "bi-arrow-down";
    $(this).append(' <i class="bi ' + icon + '"></i>');

    // Get current filter values
    const filterData = $("#filter-form").serialize();

    // Load data with new sorting
    loadTableData(filterData + "&sort=" + column + "&order=" + newOrder);
  });

  // Dynamic link handling for related tables
  $(document).on("click", ".table-link", function (e) {
    e.preventDefault();
    const target = $(this).data("target");
    const value = $(this).data("value");

    window.location.href = "?page=" + target + "&filter=" + value;
  });
});

// Function to show notification
function showNotification(message, type = "success", duration = 3000) {
  // Define classes based on notification type
  let bgClass, iconClass;

  switch (type) {
    case "error":
      bgClass = "bg-danger";
      iconClass = "bi-exclamation-triangle-fill";
      break;
    case "warning":
      bgClass = "bg-warning";
      iconClass = "bi-exclamation-circle-fill";
      break;
    case "info":
      bgClass = "bg-info";
      iconClass = "bi-info-circle-fill";
      break;
    default:
      bgClass = "bg-success";
      iconClass = "bi-check-circle-fill";
  }

  // Generate unique ID for this toast
  const toastId = "toast-" + Date.now();

  // Create toast HTML
  const toast = `
    <div id="${toastId}" class="toast align-items-center ${bgClass} text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  `;

  // Add toast to container
  $("#notification-container").append(toast);

  // Initialize and show toast
  const toastElement = document.getElementById(toastId);
  const bsToast = new bootstrap.Toast(toastElement, {
    delay: duration,
    autohide: true,
  });

  bsToast.show();

  // Remove toast from DOM after it's hidden
  $(toastElement).on("hidden.bs.toast", function () {
    $(this).remove();
  });
}

// Function to confirm delete
function confirmDelete(id, entity) {
  if (confirm("Sei sicuro di voler eliminare questo " + entity + "?")) {
    deleteRecord(id, entity);
  }
}

// Function to format date for display
function formatDate(dateString) {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("it-IT");
}

// Function to escape HTML
function escapeHtml(str) {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

// Function to load table data
function loadTableData(search = "") {
  $("#table-container").html(
    '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Caricamento...</span></div><p class="mt-2">Caricamento dati in corso...</p></div>'
  );

  $.ajax({
    url: "../api/crud/get_veicoli.php",
    type: "GET",
    data: {
      search: search,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        if (response.data.length > 0) {
          renderTable(response.data);
        } else {
          $("#table-container").html(
            '<div class="alert alert-info" role="alert"><i class="bi bi-info-circle"></i> Nessun veicolo trovato.</div>'
          );
        }
      } else {
        $("#table-container").html(
          '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-triangle"></i> ' +
            response.message +
            "</div>"
        );
      }
    },
    error: function (xhr, status, error) {
      $("#table-container").html(
        '<div class="alert alert-danger" role="alert"><i class="bi bi-wifi-off"></i> Errore di comunicazione con il server</div>'
      );
    },
  });
}

// Function to render table with data
function renderTable(data) {
  let tableHtml = `
    <div class="table-responsive">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-muted">
          <small>${data.length} risultati trovati</small>
        </div>
        <div class="btn-group">
          <button class="btn btn-sm btn-outline-secondary" id="export-csv">
            <i class="bi bi-file-earmark-spreadsheet"></i> Esporta CSV
          </button>
          <button class="btn btn-sm btn-outline-secondary" id="print-table">
            <i class="bi bi-printer"></i> Stampa
          </button>
        </div>
      </div>
      <table class="table table-striped table-hover table-sm border">
        <thead class="bg-light">
          <tr>
            <th class="sortable" data-column="telaio">Telaio <i class="bi bi-arrow-down-up"></i></th>
            <th class="sortable" data-column="marca">Marca <i class="bi bi-arrow-down-up"></i></th>
            <th class="sortable" data-column="modello">Modello <i class="bi bi-arrow-down-up"></i></th>
            <th class="sortable" data-column="dataProd">Data Prod. <i class="bi bi-arrow-down-up"></i></th>
            <th class="text-end">Azioni</th>
          </tr>
        </thead>
        <tbody>
  `;

  data.forEach(function (veicolo) {
    const dataProdFormatted = new Date(veicolo.dataProd).toLocaleDateString(
      "it-IT"
    );

    tableHtml += `
      <tr>
        <td>${veicolo.telaio}</td>
        <td>${veicolo.marca}</td>
        <td>${veicolo.modello}</td>
        <td>${dataProdFormatted}</td>
        <td class="text-end">
          <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-primary edit-btn" data-id="${veicolo.telaio}" data-bs-toggle="tooltip" title="Modifica">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-outline-danger delete-btn" data-id="${veicolo.telaio}" data-bs-toggle="tooltip" title="Elimina">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  });

  tableHtml += `
        </tbody>
      </table>
    </div>
  `;

  $("#table-container").html(tableHtml);

  // Re-initialize tooltips for new buttons
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Add sorting functionality
  $(".sortable").on("click", function () {
    const column = $(this).data("column");
    sortTable(column);
  });

  // Handle export and print
  $("#export-csv").on("click", function () {
    exportTableToCSV("veicoli.csv");
  });

  $("#print-table").on("click", function () {
    printTable();
  });
}

// Function to sort table
function sortTable(column) {
  const sortDirection =
    $(".sortable[data-column='" + column + "']").attr("data-sort") === "asc"
      ? "desc"
      : "asc";

  // Reset all sort indicators
  $(".sortable")
    .removeAttr("data-sort")
    .find("i")
    .attr("class", "bi bi-arrow-down-up");

  // Set sort indicator for this column
  $(".sortable[data-column='" + column + "']").attr("data-sort", sortDirection);
  if (sortDirection === "asc") {
    $(".sortable[data-column='" + column + "']")
      .find("i")
      .attr("class", "bi bi-sort-alpha-down");
  } else {
    $(".sortable[data-column='" + column + "']")
      .find("i")
      .attr("class", "bi bi-sort-alpha-up");
  }

  // Get current table data
  const rows = $("table tbody tr").get();

  // Sort rows
  rows.sort(function (a, b) {
    const A = $(a)
      .children("td")
      .eq($(".sortable[data-column='" + column + "']").index())
      .text()
      .toUpperCase();
    const B = $(b)
      .children("td")
      .eq($(".sortable[data-column='" + column + "']").index())
      .text()
      .toUpperCase();

    if (column === "dataProd") {
      // Date comparison
      return sortDirection === "asc"
        ? new Date(A) - new Date(B)
        : new Date(B) - new Date(A);
    } else {
      // String comparison
      return sortDirection === "asc" ? A.localeCompare(B) : B.localeCompare(A);
    }
  });

  // Re-add rows to table
  $.each(rows, function (index, row) {
    $("table tbody").append(row);
  });
}

// Function to export table to CSV
function exportTableToCSV(filename) {
  const csv = [];
  const rows = document.querySelectorAll("table tr");

  for (let i = 0; i < rows.length; i++) {
    const row = [],
      cols = rows[i].querySelectorAll("td, th");

    for (let j = 0; j < cols.length - 1; j++) {
      // Skip last column (actions)
      // Get text content and clean it
      let text = cols[j].innerText
        .replace(/(\r\n|\n|\r)/gm, "")
        .replace(/"/g, '""');
      row.push('"' + text + '"');
    }

    csv.push(row.join(","));
  }

  // Download CSV
  const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
  const downloadLink = document.createElement("a");

  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";

  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);

  showNotification(
    '<i class="bi bi-file-earmark-arrow-down"></i> CSV esportato con successo'
  );
}

// Function to print table
function printTable() {
  // Create print window
  const printWindow = window.open("", "_blank");

  // Get current table HTML
  const tableHtml = document.querySelector(".table-responsive").innerHTML;

  // Create styled content for printing
  const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Veicoli - Stampa</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          margin: 20px;
        }
        h1 {
          text-align: center;
          margin-bottom: 20px;
        }
        table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd;
        }
        th {
          background-color: #f2f2f2;
        }
        .text-end {
          display: none;
        }
        @media print {
          .no-print {
            display: none;
          }
        }
        .header {
          display: flex;
          justify-content: space-between;
          margin-bottom: 20px;
        }
        .date {
          font-size: 12px;
          color: #666;
        }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Elenco Veicoli</h1>
        <div class="date">Data: ${new Date().toLocaleDateString("it-IT")}</div>
      </div>
      ${tableHtml}
      <div class="no-print">
        <button onclick="window.print();window.close();" style="margin-top: 20px; padding: 10px 20px;">Stampa</button>
      </div>
      <script>
        // Hide action buttons column
        document.querySelectorAll('.text-end').forEach(el => el.style.display = 'none');
        // Remove export/print buttons
        const btnGroup = document.querySelector('.btn-group');
        if (btnGroup) btnGroup.parentNode.removeChild(btnGroup);
      </script>
    </body>
    </html>
  `;

  // Write to print window
  printWindow.document.open();
  printWindow.document.write(printContent);
  printWindow.document.close();

  showNotification(
    '<i class="bi bi-printer"></i> Documento pronto per la stampa'
  );
}
