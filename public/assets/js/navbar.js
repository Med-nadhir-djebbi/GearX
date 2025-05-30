document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.getElementById("mainNavbar");
  if (navbar) {
    // Add a small delay to ensure smooth transition after page load
    setTimeout(() => {
      navbar.classList.add("opacity-100");
    }, 100);
  }
});
