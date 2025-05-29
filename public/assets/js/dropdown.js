// Custom dropdown handling for Tailwind/Bootstrap integration
document.addEventListener("DOMContentLoaded", function () {
  console.log("Dropdown.js loaded");

  // Initialize Tailwind dropdowns for mobile
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const mobileMenu = document.querySelector(".mobile-menu");

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
    });
  }

  // Mobile dropdown toggles within mobile menu
  const mobileDropdownToggles = document.querySelectorAll(
    ".mobile-dropdown-toggle"
  );
  mobileDropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const dropdown = this.nextElementSibling;
      dropdown.classList.toggle("hidden");
    });
  });

  // Handle click events for main dropdown buttons
  const mainDropdownContainers = document.querySelectorAll(
    ".relative > .dropdown-toggle"
  );
  console.log("Found main dropdown buttons:", mainDropdownContainers.length);

  mainDropdownContainers.forEach((button) => {
    const container = button.closest(".relative");
    const menu = container.querySelector(".dropdown-menu");

    if (!menu) return;

    // Show dropdown
    function showDropdown() {
      menu.classList.remove("invisible", "opacity-0");
      menu.classList.add("pointer-events-auto");
    }

    // Hide dropdown
    function hideDropdown() {
      menu.classList.add("invisible", "opacity-0");
      menu.classList.remove("pointer-events-auto");
    }

    // Handle click events only for main dropdowns
    button.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const isVisible = !menu.classList.contains("invisible");

      // Close all main dropdowns first
      document.querySelectorAll(".dropdown-menu").forEach((d) => {
        if (d !== menu) {
          d.classList.add("invisible", "opacity-0");
          d.classList.remove("pointer-events-auto");
        }
      });

      // Toggle this dropdown
      if (isVisible) {
        hideDropdown();
      } else {
        showDropdown();
      }
    });
  });

  // Handle hover for items with submenus (including main categories)
  const submenuContainers = document.querySelectorAll(".relative");
  console.log("Found submenu containers:", submenuContainers.length);

  submenuContainers.forEach((container) => {
    const item = container.querySelector(".has-submenu");
    const submenu = container.querySelector(".submenu");
    const isMainCategory = container.parentElement.classList.contains("py-2"); // Check if it's a main category container

    if (!submenu || !item) return;

    let hoverTimeout;

    // Show submenu
    function showSubmenu(e) {
      if (e) e.preventDefault(); // Prevent navigation when hovering items with submenus
      clearTimeout(hoverTimeout);

      // Only show submenu if main dropdown is visible (for main categories)
      if (
        isMainCategory &&
        container.closest(".dropdown-menu").classList.contains("invisible")
      ) {
        return;
      }

      submenu.classList.remove("invisible", "opacity-0");
      submenu.classList.add("pointer-events-auto");
    }

    // Hide submenu
    function hideSubmenu() {
      hoverTimeout = setTimeout(() => {
        submenu.classList.add("invisible", "opacity-0");
        submenu.classList.remove("pointer-events-auto");
      }, 100); // Small delay to prevent flickering
    }

    // Handle mouse events for desktop
    container.addEventListener("mouseenter", showSubmenu);
    container.addEventListener("mouseleave", hideSubmenu);

    // For touch devices, handle taps on the parent item
    item.addEventListener("click", function (e) {
      if (window.matchMedia("(hover: none)").matches) {
        e.preventDefault();
        e.stopPropagation();

        const isVisible = !submenu.classList.contains("invisible");

        // Close all other submenus at this level
        const siblings = container.parentElement.querySelectorAll(".submenu");
        siblings.forEach((s) => {
          if (s !== submenu) {
            s.classList.add("invisible", "opacity-0");
            s.classList.remove("pointer-events-auto");
          }
        });

        // Toggle this submenu
        if (isVisible) {
          hideSubmenu();
          console.log("Submenu is now hidden");
        } else {
          showSubmenu(e);
          console.log("Submenu is now visible");
        }
      }
    });
  });

  // Add hover protection for clicked menus
  const mainDropdownMenus = document.querySelectorAll(".dropdown-menu");
  mainDropdownMenus.forEach((menu) => {
    menu.addEventListener("mouseenter", function () {
      console.log("Mouse entered main dropdown");
      const button = menu
        .closest(".relative")
        .querySelector(".dropdown-toggle");
      if (button && !menu.classList.contains("invisible")) {
        menu.dataset.hovering = "true";
      }
    });

    menu.addEventListener("mouseleave", function () {
      console.log("Mouse left main dropdown");
      menu.dataset.hovering = "false";
    });
  });

  // Close dropdowns when clicking outside - single event listener
  document.addEventListener("click", function (e) {
    if (
      !e.target.closest(".dropdown-toggle") &&
      !e.target.closest(".dropdown-menu") &&
      !e.target.closest(".has-submenu") &&
      !e.target.closest(".submenu")
    ) {
      const openDropdowns = document.querySelectorAll(
        ".dropdown-menu:not(.invisible), .submenu:not(.invisible)"
      );
      openDropdowns.forEach((dropdown) => {
        dropdown.classList.add("invisible");
        dropdown.classList.add("opacity-0");
      });
    }
  });
});
