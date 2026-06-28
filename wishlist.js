function toggleWishlist(icon) {

    const productId = icon.getAttribute('data-product-id');
   console.log("Product ID:", productId);  // Log product ID

    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite';
        icon.classList.add('filled');
        addToWishlist(productId);
    } else {
        icon.textContent = 'favorite_border';
        icon.classList.remove('filled');
        removeFromWishlist(productId);
    }
}

function addToWishlist(productId) {
    $.ajax({
        url: '/projectC/wishlist.php',
        type: 'POST',
        data: { product_id: productId, action: 'add' },
        success: function(response) {
            console.log(response);
           
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

function removeFromWishlist(productId) {
    $.ajax({
        url: '/projectC/wishlist.php',
        type: 'POST',
        data: { product_id: productId, action: 'remove' },
        success: function(response) {
            console.log(response);
            
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}
//for handle product detail.php whishlist button

function toggleWishlistDetail(button) {
    const productId = button.getAttribute('data-product-id');
    const icon = button.querySelector('.material-icons');

    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite';
        button.classList.add('filled');
        addToWishlist(productId);
    } else {
        icon.textContent = 'favorite_border';
        button.classList.remove('filled');
        removeFromWishlist(productId);
    }
}
