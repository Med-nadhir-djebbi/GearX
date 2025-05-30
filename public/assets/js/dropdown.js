document.addEventListener("DOMContentLoaded", function () {
  console.log("Dropdown.js loaded");

  // Mobile menu toggle (keep existing functionality)
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const mobileMenu = document.querySelector(".mobile-menu");

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
    });
  }

  // Mobile dropdown toggles within mobile menu (keep existing functionality)
  const mobileDropdownToggles = document.querySelectorAll(".mobile-dropdown-toggle");
  mobileDropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const dropdown = this.nextElementSibling;
      dropdown.classList.toggle("hidden");
    });
  });

  // Main dropdown toggle (categories button)
  const mainDropdownToggle = document.querySelector(".dropdown-toggle");
  const mainDropdownMenu = document.querySelector(".dropdown-menu");

  if (mainDropdownToggle && mainDropdownMenu) {
    mainDropdownToggle.addEventListener("click", function (e) {
      e.stopPropagation();
      mainDropdownMenu.classList.toggle("invisible");
      mainDropdownMenu.classList.toggle("opacity-0");
      mainDropdownMenu.classList.toggle("pointer-events-auto");
    });
  }

  // Handle submenu dropdown toggles
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle:not(.mobile-dropdown-toggle)");
  
  dropdownToggles.forEach((toggle) => {
    // Skip if this is the main categories toggle (already handled above)
    if (toggle === mainDropdownToggle) return;

    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      // Find the closest dropdown menu relative to this toggle
      const dropdownMenu = this.closest(".relative").querySelector(".dropdown-menu, .submenu");
      
      if (!dropdownMenu) return;

      // Close all other dropdowns at the same level
      const parentContainer = this.closest(".relative");
      if (parentContainer && parentContainer.parentElement) {
        parentContainer.parentElement.querySelectorAll(".dropdown-menu, .submenu").forEach((menu) => {
          if (menu !== dropdownMenu) {
            menu.classList.add("invisible", "opacity-0");
            menu.classList.remove("pointer-events-auto");
          }
        });
      }

      // Toggle this dropdown
      dropdownMenu.classList.toggle("invisible");
      dropdownMenu.classList.toggle("opacity-0");
      dropdownMenu.classList.toggle("pointer-events-auto");
    });
  });

  // Hover functionality for desktop
  const hoverableMenus = document.querySelectorAll(".group/category, .group/sub");
  
  hoverableMenus.forEach((menu) => {
    menu.addEventListener("mouseenter", function () {
      if (window.matchMedia("(hover: hover)").matches) { // Only for devices that support hover
        const submenu = this.querySelector(".submenu, .dropdown-menu");
        if (submenu) {
          submenu.classList.remove("invisible", "opacity-0");
          submenu.classList.add("pointer-events-auto");
        }
      }
    });

    menu.addEventListener("mouseleave", function () {
      if (window.matchMedia("(hover: hover)").matches) {
        const submenu = this.querySelector(".submenu, .dropdown-menu");
        if (submenu) {
          submenu.classList.add("invisible", "opacity-0");
          submenu.classList.remove("pointer-events-auto");
        }
      }
    });
  });

  // Close all dropdowns when clicking outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".dropdown-menu") && 
        !e.target.closest(".dropdown-toggle") && 
        !e.target.closest(".submenu")) {
      document.querySelectorAll(".dropdown-menu, .submenu").forEach((menu) => {
        menu.classList.add("invisible", "opacity-0");
        menu.classList.remove("pointer-events-auto");
      });
    }
  });

  // Close dropdowns when pressing Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      document.querySelectorAll(".dropdown-menu, .submenu").forEach((menu) => {
        menu.classList.add("invisible", "opacity-0");
        menu.classList.remove("pointer-events-auto");
      });
    }
  });
}); 