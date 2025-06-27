function setCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (!cartCountElement) return;

    if (count > 0) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = 'inline-block';
    } else {
        cartCountElement.style.display = 'none';
    }
}

function updateCartCount(forceValue = null) {
    const cartCountElement = document.querySelector('.cart-count');
    if (!cartCountElement) return;

    if (forceValue !== null) {
        setCartCount(forceValue);
        return;
    }

    fetch(`${siteUrl}/cart/count`)
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            setCartCount(parseInt(data.count || 0));
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
            cartCountElement.style.display = 'none';
        });
}

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount(); // Initialize count on page load

    // Add to Cart
    document.querySelectorAll('form[action*="/cart/add"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const productId = form.querySelector('input[name="product_id"]').value;

            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...`;

            fetch(`${siteUrl}/cart/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(new FormData(form))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    button.innerHTML = '<i class="bi bi-check2"></i> Added';

                    const cartCountElement = document.querySelector('.cart-count');
                    const currentCount = cartCountElement && cartCountElement.style.display !== 'none'
                        ? parseInt(cartCountElement.textContent || '0')
                        : 0;
                    setCartCount(currentCount + 1);

                    setTimeout(() => {
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.innerHTML = 'Add to Cart';
                        button.disabled = false;
                    }, 2000);
                } else {
                    button.innerHTML = 'Add to Cart';
                    button.disabled = false;
                    alert('Failed to add item: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = 'Add to Cart';
                button.disabled = false;
            });
        });
    });

    // Update Quantity
    document.querySelectorAll('.change-quantity').forEach(btn => {
        btn.addEventListener('click', function () {
            const action = this.getAttribute('data-action');
            const itemId = this.getAttribute('data-id');
            const input = this.closest('.d-flex').querySelector('input');
            let quantity = parseInt(input.value);
            const oldQuantity = quantity;

            if (action === 'increase') quantity++;
            else if (action === 'decrease' && quantity > 1) quantity--;
            else return;

            input.value = quantity;

            fetch(`${siteUrl}/cart/updateQuantity/${itemId}/${quantity}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        const currentCount = parseInt(cartCountElement.textContent || '0');
                        const newCount = currentCount + (quantity - oldQuantity);
                        setCartCount(newCount);
                    }

                    // Update subtotal
                    const priceElement = input.closest('.d-flex').parentElement.querySelector('.text-danger');
                    if (priceElement) {
                        const unitPrice = parseFloat(priceElement.textContent.replace('₹', ''));
                        const totalPrice = (unitPrice * quantity).toFixed(2);
                        const totalElement = priceElement.nextElementSibling;
                        if (totalElement && totalElement.textContent.includes('Total')) {
                            totalElement.textContent = `Total: ₹${totalPrice}`;
                        }
                    }
                } else {
                    input.value = oldQuantity;
                    alert('Error updating quantity: ' + (data.message || ''));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                input.value = oldQuantity;
            });
        });
    });

    // Remove from Cart
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const itemId = this.getAttribute('data-id');
            const cartItem = btn.closest('.d-flex');
            const quantityInput = cartItem ? cartItem.querySelector('input[value]') : null;
            const quantityToRemove = quantityInput ? parseInt(quantityInput.value) : 1;

            fetch(`${siteUrl}/cart/remove/${itemId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            const currentCount = parseInt(cartCountElement.textContent || '0');
                            const newCount = Math.max(0, currentCount - quantityToRemove);
                            setCartCount(newCount);
                        }

                        if (cartItem) {
                            cartItem.remove();
                            const cartItems = document.querySelectorAll('.d-flex.align-items-start.py-3');
                            if (cartItems.length === 0) {
                                const container = document.querySelector('.container');
                                if (container) {
                                    container.innerHTML = `
                                        <div class="alert alert-info text-center py-5">
                                            <h4>Your cart is empty!</h4>
                                            <p><a href="${siteUrl}" class="btn btn-primary mt-3">Continue Shopping</a></p>
                                        </div>`;
                                }
                            }
                        }
                    } else {
                        alert('Failed to remove item: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
});
