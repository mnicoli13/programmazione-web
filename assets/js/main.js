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
