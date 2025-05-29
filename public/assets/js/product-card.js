// Product Card Interactions
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Bootstrap tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // Handle Add to Wishlist button
  $(".wishlist-btn").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // You can add AJAX call here to add the product to the wishlist
    // For now, just show a notification
    alert("Product added to wishlist!");
  });

  // Handle Add to Cart button
  $(".cart-btn").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // You can add AJAX call here to add the product to the cart
    // For now, just show a notification
    alert("Product added to cart!");
  });
});
