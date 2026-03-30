# ShopNest — Beginner Friendly Setup Guide

## Files in this project

```
shopnest/
 ├── database.sql   ← Run this FIRST in phpMyAdmin
 ├── db.php         ← Database connection settings
 ├── style.css      ← All the styling (CSS)
 ├── main.js        ← JavaScript (cart, toast)
 ├── index.php      ← Homepage
 ├── shop.php       ← Shop page with filters
 ├── cart.php       ← Shopping cart page
 ├── login.php      ← Login page
 ├── register.php   ← Register page
 ├── logout.php     ← Logout (clears session)
 ├── checkout.php   ← Order confirmation page
 └── admin.php      ← Admin panel (all tabs)
```

---

## Setup Steps

### Step 1 — Start XAMPP
Open XAMPP and start **Apache** and **MySQL**.

### Step 2 — Create the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **SQL** tab at the top
3. Open the file `database.sql` in Notepad
4. Copy all the text and paste it into the SQL box
5. Click **Go**

You should see the `shopnest` database appear on the left.

### Step 3 — Configure Database Connection
Open `db.php` and check these lines:
```php
$username = "root";   // usually "root" for XAMPP
$password = "";       // usually blank for XAMPP
```

### Step 4 — Copy Files to XAMPP
Copy the entire `shopnest` folder to:
- **Windows XAMPP**: `C:\xampp\htdocs\shopnest\`
- **Mac XAMPP**: `/Applications/XAMPP/htdocs/shopnest/`

### Step 5 — Open in Browser
Go to: `http://localhost/shopnest/`

---

## Login Details

| Role  | Email                 | Password  |
|-------|-----------------------|-----------|
| Admin | admin@shopnest.com    | password  |

---

## Pages Explained

| File           | What it does                                      |
|----------------|---------------------------------------------------|
| `index.php`    | Homepage with hero and featured products          |
| `shop.php`     | Browse all products, filter by category           |
| `cart.php`     | View cart, change quantities, checkout            |
| `login.php`    | Login form                                        |
| `register.php` | Create a new account                              |
| `logout.php`   | Clears session and goes back to homepage          |
| `checkout.php` | Saves order to database and shows success message |
| `admin.php`    | Admin panel with 4 tabs (dashboard/users/products/orders) |

---

## How the Cart Works

The cart uses **localStorage** (browser storage) so items stay even after refreshing. When you checkout, the cart data is sent to `checkout.php` which saves it to the database.

---

*Good luck with your assignment! Deadline: 2nd April 2026*
