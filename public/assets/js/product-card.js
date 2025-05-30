// Product Card Interactions
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Bootstrap tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // Handle card links when clicking inside action buttons area
  $(".product-actions").on("click", function (e) {
    e.stopPropagation();
  });

  // Function to handle adding to cart from card view
  window.addToCart = function (form, productId) {
    const cartQuantityInput = document.getElementById(
      "cart-quantity-" + productId
    );
    // Default to 1 item when adding from card view
    cartQuantityInput.value = 1;
    return true;
  };
});
