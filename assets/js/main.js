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

  // Dynamic link handling for related tables
  $(document).on("click", ".table-link", function (e) {
    e.preventDefault();
    const target = $(this).data("target");
    const value = $(this).data("value");

    window.location.href = "?page=" + target + "&filter=" + value;
  });
});
