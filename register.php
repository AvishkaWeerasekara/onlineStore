<?php
session_start();
include 'db.php';

// If already logged in, go to homepage
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Simple validation checks
    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password != $confirm) {
        $error = 'Passwords do not match. Please try again.';
    } else {
        // Check if email is already used
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'This email is already registered. Please login.';
        } else {
            // Hash the password for security
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Save to database
            $sql = "INSERT INTO users (username, email, password, role)
                    VALUES ('$username', '$email', '$hashed', 'customer')";

            if (mysqli_query($conn, $sql)) {
                $success = 'Account created! You can now login.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest — Register</title>
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
        <a href="login.php" class="btn btn-outline btn-sm">Login</a>
    </div>
</nav>

<!-- REGISTER FORM -->
<div class="form-wrap">
    <div class="form-card">
        <div class="form-logo">SHOPNEST</div>
        <div class="form-sub">Create your account to get started.</div>

        <!-- Show error or success messages -->
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="login.php" style="color:#4caf84;font-weight:600"> Login →</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input class="form-input" type="text" name="username"
                       placeholder="johndoe"
                       value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"
                       required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input class="form-input" type="email" name="email"
                       placeholder="you@example.com"
                       value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"
                       required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input class="form-input" type="password" name="password"
                       placeholder="Min. 6 characters" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input class="form-input" type="password" name="confirm"
                       placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="form-submit">Create Account</button>
        </form>

        <div class="form-switch">
            Already have an account? <a href="login.php">Sign In</a>
        </div>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>
