<?php
session_start();
include 'db.php';

// Get category filter from URL (e.g. shop.php?cat=Electronics)
$category = isset($_GET['cat']) ? $_GET['cat'] : 'All';

// Build SQL query based on filter
if ($category == 'All') {
    $sql = "SELECT * FROM products";
} else {
    $sql    = "SELECT * FROM products WHERE category = '$category'";
}

$result   = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get all unique categories for sidebar
$cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM products");
$categories = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="logo">SHOP<span>NEST</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php" class="active">Shop</a></li>
        <li><a href="cart.php">Cart</a></li>
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

<!-- SHOP LAYOUT (sidebar + products) -->
<div class="shop-layout">

    <!-- SIDEBAR FILTERS -->
    <aside class="shop-sidebar">
        <div class="filter-title">Categories</div>

        <a href="shop.php" class="filter-link <?php echo $category == 'All' ? 'active' : ''; ?>">
            All Products
        </a>

        <?php foreach ($categories as $cat): ?>
        <a href="shop.php?cat=<?php echo $cat['category']; ?>"
           class="filter-link <?php echo $category == $cat['category'] ? 'active' : ''; ?>">
            <?php echo $cat['category']; ?>
        </a>
        <?php endforeach; ?>
    </aside>

    <!-- PRODUCTS AREA -->
    <main class="shop-main">
        <div class="shop-top">
            <h2 class="shop-main-title">
                <?php echo $category == 'All' ? 'All Products' : $category; ?>
                <span style="color:#9090a8;font-size:15px;font-family:'DM Sans',sans-serif;font-weight:400">
                    (<?php echo count($products); ?> items)
                </span>
            </h2>
        </div>

        <?php if (count($products) == 0): ?>
            <div style="text-align:center;padding:80px 0;color:#9090a8">
                <div style="font-size:48px;margin-bottom:16px">🔍</div>
                <p>No products found. <a href="shop.php" style="color:#c9a84c">Clear filter</a></p>
            </div>
        <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
            <div class="product-card">
                <?php if ($p['stock'] < 30): ?>
                    <div class="badge-new" style="background:#e05252">Low Stock</div>
                <?php else: ?>
                    <div class="badge-new">In Stock</div>
                <?php endif; ?>

                <img src="<?php echo $p['image_url']; ?>" alt="<?php echo $p['name']; ?>">

                <div class="card-body">
                    <div class="card-category"><?php echo $p['category']; ?></div>
                    <div class="card-name"><?php echo $p['name']; ?></div>
                    <div class="card-stars">★★★★★</div>
                    <p style="font-size:12px;color:#9090a8;margin-top:6px;line-height:1.5">
                        <?php echo $p['description']; ?>
                    </p>
                    <div class="card-footer">
                        <div class="card-price">$<?php echo number_format($p['price'], 2); ?></div>
                        <button class="add-btn" onclick="addToCart(
                            <?php echo $p['id']; ?>,
                            '<?php echo $p['name']; ?>',
                            <?php echo $p['price']; ?>,
                            '<?php echo $p['image_url']; ?>'
                        )">+ Add</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- FOOTER -->
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
    <span id="toast-msg">Item added!</span>
</div>

<script src="main.js"></script>
</body>
</html>
