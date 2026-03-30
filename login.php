<?php
session_start();
include 'db.php';

// If already logged in, go to homepage
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Find user by email
    $sql    = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Save user info in session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Send admin to admin panel, customers to homepage
        if ($user['role'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = 'Wrong email or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Login</title>
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
        <a href="register.php" class="btn btn-gold btn-sm">Sign Up</a>
    </div>
</nav>

<!-- LOGIN FORM -->
<div class="form-wrap">
    <div class="form-card">
        <div class="form-logo">SHOPNEST</div>
        <div class="form-sub">Welcome back! Sign in to continue.</div>

        <!-- Show error if login failed -->
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input class="form-input" type="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input class="form-input" type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="form-submit">Sign In</button>
        </form>

        <!-- Demo login info box -->
        <div style="margin-top:20px;padding:14px;background:rgba(201,168,76,0.06);border-radius:8px;border:1px solid rgba(201,168,76,0.12);">
            <div style="font-size:11px;color:#9090a8;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px">Demo Login</div>
            <div style="font-size:13px;color:#c0c0d8;">Email: <strong style="color:#c9a84c">admin@shopnest.com</strong></div>
            <div style="font-size:13px;color:#c0c0d8;margin-top:4px;">Password: <strong style="color:#c9a84c">password</strong></div>
        </div>

        <div class="form-switch">
            Don't have an account? <a href="register.php">Sign Up</a>
        </div>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>
