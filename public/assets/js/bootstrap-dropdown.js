document.addEventListener("DOMContentLoaded", function () {
  // Initialize Bootstrap dropdowns if Bootstrap is available
  if (typeof bootstrap !== "undefined") {
    var dropdownElementList = [].slice.call(
      document.querySelectorAll(".dropdown-toggle")
    );
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });
  } else {
    // Fallback initialization for Bootstrap dropdowns using jQuery if available
    if (typeof $ !== "undefined" && typeof $.fn.dropdown !== "undefined") {
      $(".dropdown-toggle").dropdown();
    }
  }

  // Initialize all Bootstrap tooltips (used in product cards)
  if (
    typeof bootstrap !== "undefined" &&
    typeof bootstrap.Tooltip !== "undefined"
  ) {
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  } else if (typeof $ !== "undefined" && typeof $.fn.tooltip !== "undefined") {
    $('[data-toggle="tooltip"]').tooltip();
  }
});
