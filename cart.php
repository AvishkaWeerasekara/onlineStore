<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="logo">SHOP<span>NEST</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="cart.php" class="active">Cart</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="admin.php">Admin</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav-right">
        <a href="cart.php" class="cart-icon">🛍<span class="cart-badge" id="cart-count">0</span></a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="color:#9090a8;font-size:13px">Hi, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-gold btn-sm">Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- CART CONTENT -->
<div class="page-wrap">
    <h2 class="page-title">Your Cart</h2>

    <!-- Cart Items Table (filled by JavaScript) -->
    <table class="cart-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody id="cart-items">
            <!-- JavaScript fills this -->
        </tbody>
    </table>

    <!-- Order Summary -->
    <div class="cart-summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">$0.00</span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span style="color:#4caf84">Free</span>
        </div>
        <div class="summary-row">
            <span>Tax (8%)</span>
            <span id="tax">$0.00</span>
        </div>
        <div class="summary-total">
            <span>Total</span>
            <span id="total">$0.00</span>
        </div>

        <!-- Checkout button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <button class="btn btn-gold" style="width:100%;margin-top:20px;padding:15px;font-size:13px;"
                onclick="placeOrder()">
                Proceed to Checkout →
            </button>
        <?php else: ?>
            <a href="login.php" class="btn btn-gold" style="display:block;width:100%;margin-top:20px;padding:15px;font-size:13px;text-align:center;">
                Login to Checkout →
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div>
        <div class="footer-title">ShopNest</div>
        <p style="color:#9090a8;font-size:13px">Premium products for everyone.</p>
    </div>
    <div>
        <div class="footer-title">Shop</div>
        <ul class="footer-links">
            <li><a href="shop.php">All Products</a></li>
        </ul>
    </div>
    <div>
        <div class="footer-title">Account</div>
        <ul class="footer-links">
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </div>
</footer>
<div class="footer-bottom">&copy; <?php echo date('Y'); ?> ShopNest. All rights reserved.</div>

<!-- Toast -->
<div class="toast" id="toast">
    <span class="toast-icon">✓</span>
    <span id="toast-msg">Done!</span>
</div>

<script src="main.js"></script>
<script>
// Place order — sends cart to server
function placeOrder() {
    var cart = getCart();
    if (cart.length === 0) {
        showToast('Your cart is empty!');
        return;
    }

    // Send cart data to checkout.php
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'checkout.php';

    var input = document.createElement('input');
    input.type  = 'hidden';
    input.name  = 'cart_data';
    input.value = JSON.stringify(cart);
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
