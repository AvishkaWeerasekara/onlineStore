<?php

session_start();

include 'db.php';

$result   = mysqli_query($conn, "SELECT * FROM products LIMIT 3");
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="navbar">
    <a href="index.php" class="logo">SHOP<span>NEST</span></a>

    <ul class="nav-links">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="cart.php">Cart</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="admin.php">Admin</a></li>
        <?php endif; ?>
    </ul>

    <div class="nav-right">
        <a href="cart.php" class="cart-icon">
            🛍
            <span class="cart-badge" id="cart-count">0</span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="color:#9090a8;font-size:13px">Hi, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-gold btn-sm">Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- ── HERO SECTION ── -->
<section class="hero">
    <div class="hero-text">
        <div class="hero-tag">New Collection 2026</div>
        <h1>Discover <em>Luxury</em><br>at Your<br>Fingertips</h1>
        <p>Curated premium products delivered to your door. Elevate your everyday with ShopNest.</p>
        <div class="hero-buttons">
            <a href="shop.php" class="btn btn-gold">Explore Shop</a>
            <a href="shop.php" class="btn btn-outline">View All</a>
        </div>
    </div>
    <div class="hero-image">
        <div class="hero-circle">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop" alt="Hero">
        </div>
    </div>
</section>

<!-- ── STATS BAR ── -->
<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-num">12K+</div>
        <div class="stat-lbl">Products</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">98%</div>
        <div class="stat-lbl">Satisfaction</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">50+</div>
        <div class="stat-lbl">Brands</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">24/7</div>
        <div class="stat-lbl">Support</div>
    </div>
</div>

<!-- ── FEATURED PRODUCTS ── -->
<section class="section">
    <div class="section-header">
        <div class="section-tag">Featured</div>
        <h2 class="section-title">Trending This Week</h2>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
        <div class="product-card">
            <div class="badge-new">New</div>
            <img src="<?php echo $p['image_url']; ?>" alt="<?php echo $p['name']; ?>">
            <div class="card-body">
                <div class="card-category"><?php echo $p['category']; ?></div>
                <div class="card-name"><?php echo $p['name']; ?></div>
                <div class="card-stars">★★★★★</div>
                <div class="card-footer">
                    <div class="card-price">Rs.<?php echo number_format($p['price'], 2); ?></div>
                    <button class="add-btn" onclick="addToCart(<?php echo $p['id']; ?>, '<?php echo $p['name']; ?>', <?php echo $p['price']; ?>, '<?php echo $p['image_url']; ?>')">
                        + Add
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:36px;">
        <a href="shop.php" class="btn btn-outline">View All Products →</a>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
    <div>
        <div class="footer-title">ShopNest</div>
        <p style="color:#9090a8;font-size:13px;line-height:1.7">Premium products for the discerning shopper.</p>
    </div>
    <div>
        <div class="footer-title">Shop</div>
        <ul class="footer-links">
            <li><a href="shop.php">All Products</a></li>
            <li><a href="shop.php?cat=Electronics">Electronics</a></li>
            <li><a href="shop.php?cat=Footwear">Footwear</a></li>
            <li><a href="shop.php?cat=Accessories">Accessories</a></li>
        </ul>
    </div>
    <div>
        <div class="footer-title">Account</div>
        <ul class="footer-links">
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="cart.php">My Cart</a></li>
        </ul>
    </div>
</footer>
<div class="footer-bottom">
    &copy; <?php echo date('Y'); ?> ShopNest. All rights reserved.
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <span class="toast-icon">✓</span>
    <span id="toast-msg">Item added!</span>
</div>

<!-- Link JavaScript file -->
<script src="main.js"></script>
</body>
</html>
