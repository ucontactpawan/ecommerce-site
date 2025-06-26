document.addEventListener('DOMContentLoaded', function() {
    // Get the siteUrl from a global variable that should be set in your view
    const siteUrl = window.siteUrl;
    if (!siteUrl) {
        console.error('Site URL not properly configured');
        return; // Exit early if siteUrl is not set
    }
    
    // Function to update cart table if we're on the cart page
    function updateCartTable() {
        const cartTable = document.querySelector('.cart-table');
        if (cartTable) {
            fetch(`${siteUrl}/cart/view`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch cart view');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('.cart-table');
                    if (newTable) {
                        cartTable.innerHTML = newTable.innerHTML;
                    }
                })
                .catch(error => {
                    console.error('Error updating cart table:', error);
                });
        }
    }

    // Fetch cart count and update the Cart icon badge
    function updateCartCount() {
        fetch(`${siteUrl}/cart/count`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch cart count');
                }
                return response.json();
            })
            .then(data => {
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.count;
                    cartCount.style.display = data.count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }

    // Initial cart count update
    updateCartCount();

    // Add event listener for Add to Cart buttons
    const addToCartButtons = document.querySelectorAll('.btn-primary');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('form');
            
            // Disable button immediately to prevent double clicks
            button.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update cart count first
                    updateCartCount();
                    
                    // Show success state
                    button.innerHTML = '<i class="bi bi-check-lg"></i> Added';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.innerHTML = 'Add to Cart';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Failed to add item to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error state
                button.innerHTML = '<i class="bi bi-x-lg"></i> Failed';
                button.classList.remove('btn-primary');
                button.classList.add('btn-danger');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = 'Add to Cart';
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-primary');
                    button.disabled = false;
                }, 2000);
                
                // Show error message to user
                if (error.message.includes('must be logged in')) {
                    window.location.href = `${siteUrl}/login`;
                } else {
                    alert(error.message || 'Failed to add item to cart');
                }
            });
        });
    });

    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            if (!itemId) {
                console.error('No item ID found');
                return;
            }
            
            const row = this.closest('tr');
            const removeUrl = `${siteUrl}/cart/remove/${itemId}`;
            
            fetch(removeUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Server error occurred');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    row.remove();
                    updateCartCount();
                    
                    const tbody = document.querySelector('.cart-table tbody');
                    if (!tbody.hasChildNodes()) {
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Failed to remove item from cart');
            });
        });
    });
});    
