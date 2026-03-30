<?php
// checkout.php — Saves the order to the database
session_start();
include 'db.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get cart data sent from cart page
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_data'])) {
    $cart_data = json_decode($_POST['cart_data'], true);

    if (!$cart_data || count($cart_data) == 0) {
        header('Location: cart.php');
        exit();
    }

    // Calculate total
    $total = 0;
    foreach ($cart_data as $item) {
        $total = $total + ($item['price'] * $item['qty']);
    }
    $tax   = $total * 0.08;
    $total = $total + $tax;

    $user_id = $_SESSION['user_id'];

    // Save order to database
    $sql = "INSERT INTO orders (user_id, total, status) VALUES ('$user_id', '$total', 'pending')";
    mysqli_query($conn, $sql);

    // Clear message
    $order_id = mysqli_insert_id($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Order Placed!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="logo">SHOP<span>NEST</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
    </ul>
    <div class="nav-right">
        <a href="cart.php" class="cart-icon">🛍<span class="cart-badge" id="cart-count">0</span></a>
    </div>
</nav>

<!-- SUCCESS MESSAGE -->
<div style="text-align:center;padding:100px 40px;">
    <div style="font-size:64px;margin-bottom:20px;">✅</div>
    <h2 style="font-family:'Playfair Display',serif;font-size:38px;color:#e8c87a;margin-bottom:16px;">
        Order Placed!
    </h2>
    <p style="color:#9090a8;font-size:16px;margin-bottom:8px;">
        Thank you, <strong style="color:#fdf6e3"><?php echo $_SESSION['username']; ?></strong>!
    </p>
    <p style="color:#9090a8;font-size:15px;margin-bottom:32px;">
        Order #<?php echo $order_id ?? '—'; ?> has been received. Total: <strong style="color:#e8c87a">$<?php echo number_format($total ?? 0, 2); ?></strong>
    </p>
    <a href="shop.php" class="btn btn-gold">Continue Shopping</a>
    <a href="index.php" class="btn btn-outline" style="margin-left:12px">Go Home</a>
</div>

<script src="main.js"></script>
<script>
    // Clear the cart after successful order
    localStorage.removeItem('shopnest_cart');
    updateCartBadge();
</script>
</body>
</html>
