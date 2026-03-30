<?php
// admin.php — Admin Panel
session_start();
include 'db.php';

// Only admins can see this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// ── HANDLE ACTIONS ────────────────────────────

// Delete a user
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    header('Location: admin.php?tab=users');
    exit();
}

// Delete a product
if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");
    header('Location: admin.php?tab=products');
    exit();
}

// Update order status
if (isset($_POST['update_order'])) {
    $id     = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = '$id'");
    header('Location: admin.php?tab=orders');
    exit();
}

// Add new product
if (isset($_POST['add_product'])) {
    $name  = $_POST['name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $cat   = $_POST['category'];
    $img   = $_POST['image_url'];
    mysqli_query($conn, "INSERT INTO products (name, description, price, stock, category, image_url)
                         VALUES ('$name', '$desc', '$price', '$stock', '$cat', '$img')");
    header('Location: admin.php?tab=products');
    exit();
}

// ── GET DATA ──────────────────────────────────
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

// Counts for dashboard
$user_count    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$product_count = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
$order_count   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$revenue       = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(total),0) FROM orders"))[0];

// Data for each tab
$users    = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC"), MYSQLI_ASSOC);
$products = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC"), MYSQLI_ASSOC);
$orders   = mysqli_fetch_all(mysqli_query($conn, "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC"), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="logo">SHOP<span>NEST</span></a>
    <ul class="nav-links">
        <li><a href="index.php">Store</a></li>
        <li><a href="admin.php" class="active">Admin</a></li>
    </ul>
    <div class="nav-right">
        <span style="color:#9090a8;font-size:13px">Hi, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
    </div>
</nav>

<!-- ADMIN LAYOUT -->
<div class="admin-wrap">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-label">Admin Panel</div>
            <div class="sidebar-brand-name">ShopNest</div>
        </div>

        <a href="admin.php?tab=dashboard" class="sidebar-link <?php echo $tab=='dashboard'?'active':''; ?>">
            ⊞ Dashboard
        </a>
        <a href="admin.php?tab=users" class="sidebar-link <?php echo $tab=='users'?'active':''; ?>">
            👥 Users
        </a>
        <a href="admin.php?tab=products" class="sidebar-link <?php echo $tab=='products'?'active':''; ?>">
            📦 Products
        </a>
        <a href="admin.php?tab=orders" class="sidebar-link <?php echo $tab=='orders'?'active':''; ?>">
            🧾 Orders
        </a>

        <div style="padding:16px 22px;margin-top:20px;">
            <a href="index.php" style="display:block;text-align:center;border:1px solid rgba(201,168,76,0.15);color:#9090a8;padding:8px;border-radius:4px;font-size:12px;text-decoration:none;">
                ← Back to Store
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <!-- ── DASHBOARD TAB ── -->
        <?php if ($tab == 'dashboard'): ?>
        <div class="admin-header">
            <h2 class="admin-title">Dashboard</h2>
            <span style="color:#9090a8;font-size:13px"><?php echo date('D, d M Y'); ?></span>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon">💰</div>
                <div class="stat-card-val">$<?php echo number_format($revenue, 2); ?></div>
                <div class="stat-card-lbl">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">🛒</div>
                <div class="stat-card-val"><?php echo $order_count; ?></div>
                <div class="stat-card-lbl">Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">👤</div>
                <div class="stat-card-val"><?php echo $user_count; ?></div>
                <div class="stat-card-lbl">Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">📦</div>
                <div class="stat-card-val"><?php echo $product_count; ?></div>
                <div class="stat-card-lbl">Products</div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <h3 style="font-family:'Playfair Display',serif;font-size:20px;margin-bottom:16px;">Recent Orders</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($orders, 0, 5) as $o): ?>
                <tr>
                    <td>#<?php echo $o['id']; ?></td>
                    <td><?php echo $o['username']; ?></td>
                    <td style="color:#e8c87a;font-family:'Playfair Display',serif;font-size:16px">$<?php echo number_format($o['total'], 2); ?></td>
                    <td>
                        <?php
                        $badge_class = 'badge-gray';
                        if ($o['status'] == 'delivered')  $badge_class = 'badge-green';
                        if ($o['status'] == 'cancelled')  $badge_class = 'badge-red';
                        if ($o['status'] == 'shipped')    $badge_class = 'badge-gold';
                        if ($o['status'] == 'processing') $badge_class = 'badge-blue';
                        ?>
                        <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($o['status']); ?></span>
                    </td>
                    <td style="color:#9090a8"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- ── USERS TAB ── -->
        <?php elseif ($tab == 'users'): ?>
        <div class="admin-header">
            <h2 class="admin-title">Manage Users</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td style="color:#9090a8">#<?php echo $u['id']; ?></td>
                    <td style="color:#fdf6e3"><?php echo $u['username']; ?></td>
                    <td><?php echo $u['email']; ?></td>
                    <td>
                        <span class="badge <?php echo $u['role']=='admin'?'badge-gold':'badge-gray'; ?>">
                            <?php echo ucfirst($u['role']); ?>
                        </span>
                    </td>
                    <td style="color:#9090a8"><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                    <td>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="admin.php?delete_user=<?php echo $u['id']; ?>&tab=users"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this user?')">Delete</a>
                        <?php else: ?>
                        <span style="color:#9090a8;font-size:12px">You</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- ── PRODUCTS TAB ── -->
        <?php elseif ($tab == 'products'): ?>
        <div class="admin-header">
            <h2 class="admin-title">Manage Products</h2>
            <button class="btn btn-gold btn-sm"
                onclick="document.getElementById('add-form').style.display = document.getElementById('add-form').style.display === 'none' ? 'block' : 'none'">
                + Add Product
            </button>
        </div>

        <!-- Add Product Form (hidden by default) -->
        <div id="add-form" style="display:none;background:#13131a;border:1px solid rgba(201,168,76,0.12);border-radius:10px;padding:24px;margin-bottom:24px;">
            <h3 style="font-family:'Playfair Display',serif;font-size:20px;margin-bottom:18px;">Add New Product</h3>
            <form method="POST">
                <input type="hidden" name="add_product" value="1">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input class="form-input" type="text" name="name" placeholder="e.g. Wireless Headphones" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input class="form-input" type="text" name="category" placeholder="Electronics, Footwear…" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price ($)</label>
                        <input class="form-input" type="number" name="price" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input class="form-input" type="number" name="stock" placeholder="50" required>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Image URL</label>
                        <input class="form-input" type="text" name="image_url" placeholder="https://images.unsplash.com/…">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Description</label>
                        <input class="form-input" type="text" name="description" placeholder="Short product description">
                    </div>
                </div>
                <button type="submit" class="btn btn-gold">Add Product</button>
                <button type="button" class="btn btn-outline" style="margin-left:10px;"
                    onclick="document.getElementById('add-form').style.display='none'">Cancel</button>
            </form>
        </div>

        <!-- Products Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><img src="<?php echo $p['image_url']; ?>" style="width:48px;height:48px;border-radius:6px;object-fit:cover;border:1px solid rgba(201,168,76,0.1);" alt=""></td>
                    <td style="color:#fdf6e3"><?php echo $p['name']; ?></td>
                    <td>
                        <span class="badge badge-gold"><?php echo $p['category']; ?></span>
                    </td>
                    <td style="color:#e8c87a;font-family:'Playfair Display',serif;font-size:16px;">$<?php echo number_format($p['price'], 2); ?></td>
                    <td style="color:<?php echo $p['stock'] < 10 ? '#e05252' : '#c0c0d8'; ?>">
                        <?php echo $p['stock']; ?>
                    </td>
                    <td>
                        <a href="admin.php?delete_product=<?php echo $p['id']; ?>&tab=products"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- ── ORDERS TAB ── -->
        <?php elseif ($tab == 'orders'): ?>
        <div class="admin-header">
            <h2 class="admin-title">Manage Orders</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td style="color:#fdf6e3;font-weight:500">#<?php echo $o['id']; ?></td>
                    <td><?php echo $o['username']; ?></td>
                    <td style="color:#e8c87a;font-family:'Playfair Display',serif;font-size:16px">$<?php echo number_format($o['total'], 2); ?></td>
                    <td>
                        <?php
                        $badge_class = 'badge-gray';
                        if ($o['status'] == 'delivered')  $badge_class = 'badge-green';
                        if ($o['status'] == 'cancelled')  $badge_class = 'badge-red';
                        if ($o['status'] == 'shipped')    $badge_class = 'badge-gold';
                        if ($o['status'] == 'processing') $badge_class = 'badge-blue';
                        ?>
                        <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($o['status']); ?></span>
                    </td>
                    <td style="color:#9090a8"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                    <td>
                        <!-- Quick status update form -->
                        <form method="POST" style="display:flex;gap:6px;">
                            <input type="hidden" name="update_order" value="1">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" style="background:#252533;border:1px solid rgba(201,168,76,0.15);color:#c0c0d8;padding:5px 8px;border-radius:4px;font-size:11px;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                <option value="pending"    <?php echo $o['status']=='pending'   ?'selected':''; ?>>Pending</option>
                                <option value="processing" <?php echo $o['status']=='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipped"    <?php echo $o['status']=='shipped'   ?'selected':''; ?>>Shipped</option>
                                <option value="delivered"  <?php echo $o['status']=='delivered' ?'selected':''; ?>>Delivered</option>
                                <option value="cancelled"  <?php echo $o['status']=='cancelled' ?'selected':''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-gold">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php endif; ?>

    </main>
</div>

<script src="main.js"></script>
</body>
</html>
