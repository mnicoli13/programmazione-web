// Document ready function for CRUD operations
$(document).ready(function () {
  // Show add vehicle form
  $("#add-veicolo-btn").on("click", function () {
    resetForm();
    $("#veicolo-form-title").html(
      '<i class="bi bi-plus-circle"></i> Aggiungi Veicolo'
    );
    $("#veicolo-form-action").val("add");
    $("#veicolo-form-container").slideDown();
    $("#telaio").prop("readonly", false).focus();
  });

  // Handle edit button click
  $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
    loadVeicoloData(id);
  });

  // Submit veicolo form
  $("#veicolo-form").on("submit", function (e) {
    e.preventDefault();

    // Add loading state to submit button
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn
      .html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvataggio...'
      )
      .prop("disabled", true);

    const formData = $(this).serialize();
    const action = $("#veicolo-form-action").val();

    if (action === "add") {
      addVeicolo(formData, function () {
        // Reset button after request completes
        submitBtn.html(originalBtnText).prop("disabled", false);
      });
    } else {
      updateVeicolo(formData, function () {
        // Reset button after request completes
        submitBtn.html(originalBtnText).prop("disabled", false);
      });
    }
  });

  // Cancel form button
  $("#cancel-form-btn, #close-form-btn").on("click", function () {
    $("#veicolo-form-container").slideUp();
    resetForm();
  });

  // Function to reset form
  function resetForm() {
    $("#veicolo-form")[0].reset();
    $("#veicolo-form").find(".is-invalid").removeClass("is-invalid");
    $("#veicolo-form").find(".invalid-feedback").remove();
  }
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
      showNotification(
        '<i class="bi bi-wifi-off"></i> Errore di comunicazione con il server',
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
