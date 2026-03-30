-- =============================================
--  ShopNest Simple Database
--  Step 1: Open phpMyAdmin
--  Step 2: Click "SQL" tab
--  Step 3: Paste this entire file and click Go
-- =============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS shopnest;
USE shopnest;

-- Table 1: Users (stores registered users)
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       VARCHAR(20)  NOT NULL DEFAULT 'customer',
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
);

-- Table 2: Products (stores shop items)
CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    description VARCHAR(300),
    price       DECIMAL(10,2) NOT NULL,
    stock       INT           DEFAULT 0,
    category    VARCHAR(80),
    image_url   VARCHAR(300)
);

-- Table 3: Orders (stores customer orders)
CREATE TABLE orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    total      DECIMAL(10,2) NOT NULL,
    status     VARCHAR(30)  DEFAULT 'pending',
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
);

-- Add sample admin user
-- Password is: admin123
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@shopnest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Add sample products
INSERT INTO products (name, description, price, stock, category, image_url) VALUES
('Wireless Headphones',  'Premium noise-cancelling headphones',  89.99, 40, 'Electronics', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400'),
('Running Shoes',        'Lightweight athletic running shoes',    64.99, 75, 'Footwear',    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400'),
('Leather Wallet',       'Slim genuine leather bifold wallet',    29.99,120, 'Accessories', 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=400'),
('Mechanical Keyboard',  'RGB mechanical keyboard TKL layout',  109.99, 25, 'Electronics', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400'),
('Yoga Mat',             'Non-slip eco-friendly exercise mat',    34.99, 60, 'Sports',      'https://images.unsplash.com/photo-1601925228073-9f7fe19bb9b2?w=400'),
('Sunglasses',           'UV400 polarised aviator sunglasses',    49.99, 90, 'Accessories', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400');
