// Wishlist functionality
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Bootstrap tooltips if available
  if (typeof $('[data-toggle="tooltip"]').tooltip === "function") {
    $('[data-toggle="tooltip"]').tooltip();
  }

  // Quantity controls for product detail page
  const decreaseBtn = document.getElementById("decreaseQuantity");
  const increaseBtn = document.getElementById("increaseQuantity");
  const quantityInput = document.getElementById("quantity");

  if (decreaseBtn && increaseBtn && quantityInput) {
    decreaseBtn.addEventListener("click", function () {
      let currentValue = parseInt(quantityInput.value);
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
      }
    });

    increaseBtn.addEventListener("click", function () {
      let currentValue = parseInt(quantityInput.value);
      let maxValue = parseInt(quantityInput.getAttribute("max"));
      if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
      }
    });
  }

  // Add visual feedback for wishlist buttons
  const wishlistBtns = document.querySelectorAll('a[href*="add-to-wishlist"]');
  wishlistBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      // Add a visual pulse effect
      this.classList.add("wishlist-pulse");

      // Remove the class after animation completes
      setTimeout(() => {
        this.classList.remove("wishlist-pulse");
      }, 700);
    });
  });

  // Add visual effect when moving from wishlist to cart
  const wishlistToCartBtns = document.querySelectorAll(
    'a[href*="wishlist-to-cart"]'
  );
  wishlistToCartBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      // Get the parent row
      const row = this.closest("tr");
      if (row) {
        // Add fade out effect
        row.style.transition = "opacity 0.5s";
        row.style.opacity = "0";
      }
    });
  });

  // Add to Cart functionality for product detail page
  const addToCartBtn = document.querySelector(
    '.gaming-button[onclick*="addToCart"]'
  );
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", function (e) {
      e.preventDefault();
      const productId =
        this.getAttribute("onclick").match(/addToCart\((\d+)\)/)[1];
      const quantity = document.getElementById("quantity")
        ? document.getElementById("quantity").value
        : 1;

      // Redirect to the add to cart URL with the quantity
      window.location.href = `/cart/add/${productId}?quantity=${quantity}`;
    });
  }
});

// Function used by the addToCart onclick handler
function addToCart(productId) {
  const quantity = document.getElementById("quantity")
    ? document.getElementById("quantity").value
    : 1;
  window.location.href = `/cart/add/${productId}?quantity=${quantity}`;
  return false;
}
