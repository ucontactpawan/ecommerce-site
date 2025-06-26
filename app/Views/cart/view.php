<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/cart.css') ?>">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">My Cart</h1>
        <?php if (isset($cartItems) && !empty($cartItems)): ?>
            <table class="table table-bordered cart-table">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= $item['product']['name'] ?></td>
                            <td>₹<?= number_format($item['product']['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['product']['price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-item" data-id="<?= $item['id'] ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define the site URL as a global variable
        const siteUrl = '<?= rtrim(base_url(), '/') ?>/index.php';
    </script>
    <script src="<?= base_url('js/cart.js') ?>"></script>
    <script>
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const removeUrl = `${siteUrl}/cart/remove/${itemId}`;
                
                fetch(removeUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('tr').remove();
                            updateCartCount();
                            
                            if (!document.querySelector('.cart-table tbody tr')) {
                                location.reload();
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>