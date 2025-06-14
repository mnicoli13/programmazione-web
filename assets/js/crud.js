// Document ready function for CRUD operations
$(document).ready(function () {
  console.log("loaded crud.js");
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Add Vehicle from empty state
  $("#add-veicolo-empty-btn").on("click", function () {
    $("#addVeicoloModal").modal("show");
  });

  // Save new vehicle
  $("#save-add-veicolo").on("click", function () {
    $(this).prop("disabled", true);
    const form = $("#add-veicolo-form");

    // Form validation
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }

    // Collect form data
    const formData = new FormData(form[0]);

    // Send data
    $.ajax({
      url: "../api/crud/add_veicolo.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status === "success") {
          // Close modal
          $("#addVeicoloModal").modal("hide");

          // Reset form
          form[0].reset();

          // Show notification
          showNotification(
            '<i class="bi bi-check-circle"></i> ' + response.message,
            "success"
          );

          // Reload data
          loadTableData("");
        } else {
          showNotification(
            '<i class="bi bi-exclamation-triangle"></i> ' + response.message,
            "danger"
          );
        }
      },
      error: function () {
        showNotification(
          '<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server',
          "danger"
        );
      },
    });
    $(this).prop("disabled", false);
  });

  // Handler per il pulsante modifica nella tabella
  $(document).on("click", ".edit-btn", function () {
    $(this).prop("disabled", true);

    const id = $(this).data("id");

    // Carica i dati del veicolo
    $.ajax({
      url: "../api/crud/get_veicolo.php",
      type: "GET",
      data: { telaio: id },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          // Popolamento form
          $("#edit-original-telaio").val(response.data.telaio);
          $("#edit-telaio").val(response.data.telaio);
          $("#edit-marca").val(response.data.marca);
          $("#edit-modello").val(response.data.modello);
          $("#edit-dataProd").val(response.data.dataProd);

          // Mostra modale
          $("#editVeicoloModal").modal("show");
        } else {
          showNotification(
            '<i class="bi bi-exclamation-triangle"></i> ' + response.message,
            "danger"
          );
        }
      },
      error: function () {
        showNotification(
          '<i class="bi bi-exclamation-triangle"></i> Errore durante il caricamento dei dati del veicolo',
          "danger"
        );
      },
    });
    $(this).prop("disabled", false);
  });

  // Edit vehicle handler
  $("#save-edit-veicolo").on("click", function () {
    $(this).prop("disabled", true);
    const form = $("#edit-veicolo-form");

    // Form validation
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }

    // Collect form data
    const formData = new FormData(form[0]);

    // Send data
    $.ajax({
      url: "../api/crud/update_veicolo.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status === "success") {
          // Close modal
          $("#editVeicoloModal").modal("hide");

          // Show notification
          showNotification(
            '<i class="bi bi-check-circle"></i> ' + response.message,
            "success"
          );

          // Reload data
          loadTableData("");
        } else {
          showNotification(
            '<i class="bi bi-exclamation-triangle"></i> ' + response.message,
            "danger"
          );
        }
      },
      error: function () {
        showNotification(
          '<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server',
          "danger"
        );
      },
    });
    $(this).prop("disabled", false);
  });

  $(document).on("click", ".delete-btn", function () {
    $(this).prop("disabled", true);
    const id = $(this).data("id");
    $("#delete-telaio").val(id);
    $("#delete-telaio-display").text(id);
    $("#deleteVeicoloModal").modal("show");
    $(this).prop("disabled", false);
  });

  // Delete vehicle handler
  $("#confirm-delete-veicolo").on("click", function () {
    $(this).prop("disabled", true);
    const form = $("#delete-veicolo-form");
    const formData = new FormData(form[0]);

    $.ajax({
      url: "../api/crud/delete_veicolo.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status === "success") {
          // Close modal
          $("#deleteVeicoloModal").modal("hide");

          // Show notification
          showNotification(
            '<i class="bi bi-check-circle"></i> ' + response.message,
            "success"
          );

          // Reload data
          loadTableData("");
        } else {
          showNotification(
            '<i class="bi bi-exclamation-triangle"></i> ' + response.message,
            "danger"
          );
        }
      },
      error: function () {
        showNotification(
          '<i class="bi bi-exclamation-triangle"></i> Errore durante la comunicazione con il server',
          "danger"
        );
      },
    });
    $(this).prop("disabled", false);
  });
});
// Function to load vehicle data for editing
function loadVeicoloData(telaio) {
  // Show loading state in form title
  $("#veicolo-form-title").html(
    '<i class="bi bi-hourglass-split"></i> Caricamento dati...'
  );
  $("#veicolo-form-container").slideDown();

  // Disable form fields during loading
  $("#veicolo-form").find("input, button").prop("disabled", true);

  $.ajax({
    url: "../api/crud/get_veicolo.php",
    type: "GET",
    data: {
      telaio: telaio,
    },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        $("#telaio").val(response.data.telaio).prop("readonly", true);
        $("#marca").val(response.data.marca);
        $("#modello").val(response.data.modello);
        $("#dataProd").val(response.data.dataProd);

        // Reset form state
        $("#veicolo-form")
          .find("input:not(#telaio), button")
          .prop("disabled", false);
        $("#veicolo-form-title").html(
          '<i class="bi bi-pencil-square"></i> Modifica Veicolo'
        );
        $("#veicolo-form-action").val("edit");
      } else {
        $("#veicolo-form-container").slideUp();
        showNotification(
          '<i class="bi bi-exclamation-triangle"></i> Errore: ' +
            response.message,
          "error"
        );
      }
    },
    error: function (xhr, status, error) {
      $("#veicolo-form-container").slideUp();
      showNotification(
        '<i class="bi bi-wifi-off"></i> Errore di comunicazione con il server',
        "error"
      );
    },
  });
}

// Function to add a new vehicle
function addVeicolo(formData, callback) {
  $.ajax({
    url: "../api/crud/add_veicolo.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        showNotification(
          '<i class="bi bi-check-circle"></i> Veicolo aggiunto con successo'
        );
        $("#veicolo-form-container").slideUp();
        $("#veicolo-form")[0].reset();
        loadTableData(""); // Reload table data
      } else {
        handleFormErrors(response);
      }
      if (callback) callback();
    },
    error: function (xhr, status, error) {
      console.log("error: ", error);
      console.log("status: ", status);
      console.log("xhr: ", xhr);
      const errorMessage = error || "Errore di comunicazione con il server";
      showNotification(
        `<i class="bi bi-wifi-off"></i> ${errorMessage}`,
        "error"
      );
      if (callback) callback();
    },
  });
}

// Function to update a vehicle
function updateVeicolo(formData, callback) {
  $.ajax({
    url: "../api/crud/update_veicolo.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        showNotification(
          '<i class="bi bi-check-circle"></i> Veicolo aggiornato con successo'
        );
        $("#veicolo-form-container").slideUp();
        $("#veicolo-form")[0].reset();
        loadTableData(""); // Reload table data
      } else {
        handleFormErrors(response);
      }
      if (callback) callback();
    },
    error: function (xhr, status, error) {
      showNotification(
        '<i class="bi bi-wifi-off"></i> Errore di comunicazione con il server',
        "error"
      );
      if (callback) callback();
    },
  });
}

// Function to handle form validation errors
function handleFormErrors(response) {
  // Clear previous errors
  $("#veicolo-form").find(".is-invalid").removeClass("is-invalid");
  $("#veicolo-form").find(".invalid-feedback").remove();

  // Show general error
  showNotification(
    '<i class="bi bi-exclamation-triangle"></i> ' + response.message,
    "error"
  );

  // If we have field-specific errors
  if (response.errors) {
    Object.keys(response.errors).forEach((field) => {
      const input = $(`#${field}`);
      input.addClass("is-invalid");
      input.after(
        `<div class="invalid-feedback">${response.errors[field]}</div>`
      );
    });
  }
}
