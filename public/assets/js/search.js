// Live search functionality
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchResults = document.getElementById("searchResults");
  const searchResultsContent = document.getElementById("searchResultsContent");
  const searchLoading = document.getElementById("searchLoading");
  let searchTimeout;

  if (!searchInput || !searchResults || !searchResultsContent || !searchLoading)
    return;

  // Handle input changes
  searchInput.addEventListener("input", function () {
    const query = this.value.trim();

    // Clear previous timeout
    clearTimeout(searchTimeout);

    if (query.length < 2) {
      searchResultsContent.innerHTML = "";
      return;
    }

    // Show loading state
    searchLoading.style.display = "block";
    searchResultsContent.style.display = "none";

    // Set a timeout to prevent too many requests
    searchTimeout = setTimeout(() => {
      fetch(`/search?q=${encodeURIComponent(query)}&ajax=1`)
        .then((response) => response.text())
        .then((html) => {
          searchLoading.style.display = "none";
          searchResultsContent.style.display = "block";
          searchResultsContent.innerHTML = html;
        })
        .catch((error) => {
          console.error("Search error:", error);
          searchLoading.style.display = "none";
        });
    }, 300);
  });

  // Hide results when clicking outside
  document.addEventListener("click", function (e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.classList.remove("visible");
    }
  });

  // Show results when input is focused
  searchInput.addEventListener("focus", function () {
    if (this.value.trim().length >= 2) {
      searchResults.classList.add("visible");
    }
  });

  // Show results when typing
  searchInput.addEventListener("input", function () {
    const query = this.value.trim();
    if (query.length >= 2) {
      searchResults.classList.add("visible");
    } else {
      searchResults.classList.remove("visible");
    }
  });

  // Handle form submission
  document
    .getElementById("searchForm")
    .addEventListener("submit", function (e) {
      if (searchInput.value.trim().length < 2) {
        e.preventDefault();
      }
    });
});
