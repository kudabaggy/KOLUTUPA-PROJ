-- KOLUTUPA Database Schema
-- Second-hand clothing marketplace (like Carousell/Vinted for Indonesia)

CREATE DATABASE IF NOT EXISTS kolutupa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kolutupa;

-- Users table
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    rating DECIMAL(2,1) DEFAULT 5.0,
    wallet_balance DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seller addresses
CREATE TABLE seller_addresses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    recipient_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    full_address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    is_primary TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Followers
CREATE TABLE followers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    follower_id INT UNSIGNED NOT NULL,
    following_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_follow (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Products
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category ENUM('Pria','Wanita','Branded','Sale') NOT NULL,
    sub_category VARCHAR(50) DEFAULT NULL,
    brand VARCHAR(100) DEFAULT NULL,
    condition_item ENUM('Sangat baik','Baik','Cukup','Kurang') NOT NULL DEFAULT 'Baik',
    size VARCHAR(20) DEFAULT NULL,
    color VARCHAR(100) DEFAULT NULL,
    price DECIMAL(12,2) NOT NULL,
    is_negotiable TINYINT(1) DEFAULT 1,
    status ENUM('active','sold','draft') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Product images
CREATE TABLE product_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product measurements
CREATE TABLE product_measurements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    label VARCHAR(50) NOT NULL,
    value VARCHAR(50) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Likes
CREATE TABLE likes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT DEFAULT 1,
    product_price DECIMAL(12,2) NOT NULL,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending','dalam_proses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
    payment_method ENUM('e_wallet','qr','transfer_bank') DEFAULT NULL,
    payment_deadline DATETIME DEFAULT NULL,
    recipient_name VARCHAR(100) DEFAULT NULL,
    recipient_phone VARCHAR(20) DEFAULT NULL,
    shipping_address TEXT DEFAULT NULL,
    shipping_detail VARCHAR(255) DEFAULT NULL,
    shipping_method VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (seller_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Negotiations
CREATE TABLE negotiations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    buyer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    offered_price DECIMAL(12,2) NOT NULL,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- Cart
CREATE TABLE cart (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Messages
CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT UNSIGNED NOT NULL,
    receiver_id INT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications
CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    from_user_id INT UNSIGNED DEFAULT NULL,
    product_id INT UNSIGNED DEFAULT NULL,
    type ENUM('order_shipped','item_liked_sale','negotiation','review','system') NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Reviews
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    reviewer_id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id),
    FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- ─── SEED DATA ───────────────────────────────────────────────────────────────

-- Demo users (passwords are bcrypt of 'password123')
INSERT INTO users (name, username, email, password, bio, rating) VALUES
('KOLTUPS', 'koltups', 'koltups@kolutupa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rebels shop', 5.0),
('HFRTM STORE', 'hfrtm_store', 'hfrtm@kolutupa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ngizinc Shop', 5.0),
('Rasya Labrador', 'rasya_labrador', 'rasya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 4.8),
('Medan Store', 'medan_store', 'medan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 4.5);

-- Seller addresses
INSERT INTO seller_addresses (user_id, recipient_name, phone, full_address, city, province, postal_code) VALUES
(1, 'KOLTUPS', '081223456789', 'Jl. DI Panjaitan No.128, Karangreja, Purwokerto Kidul, Kec. Purwokerto Sel.', 'Purwokerto', 'Jawa Tengah', '53147'),
(2, 'HFRTM STORE', '082233445566', 'Jl. Sudirman No. 10, Purwokerto', 'Purwokerto', 'Jawa Tengah', '53100');

-- Followers
INSERT INTO followers (follower_id, following_id) VALUES (1, 2);

-- Products for KOLTUPS
INSERT INTO products (seller_id, title, description, category, sub_category, brand, condition_item, size, color, price) VALUES
(1, 'Kaos Putih XONW', 'Measurements\nSize (Large)\nPanjang - 71cm\nLebar - 55cm\n\nTIDAK ADA MINUSS !!!', 'Pria', 'Top', 'XONW', 'Sangat baik', 'XL', 'White, Pink', 115000),
(1, 'Carhartt Detroit Jacket', 'Vintage Carhartt Detroit jacket in great condition.', 'Pria', 'Jacket', 'Carhartt', 'Baik', 'M', 'Grey', 500000),
(1, 'Dickies Hoodie Brown', 'Brown Dickies hoodie. Minimal wear.', 'Pria', 'Hoodie', 'Dickies', 'Baik', 'M', 'Brown', 320000),
(1, 'Genuine Dickies Tee', 'Original Dickies graphic tee.', 'Pria', 'Top', 'Dickies', 'Baik', 'M', 'Brown', 220000),
(1, 'Dr Pepper Ringer Tee', 'Vintage Dr Pepper ringer tee.', 'Pria', 'Top', NULL, 'Sangat baik', 'M', 'Cream, Maroon', 640000),
(1, 'Levis Denim Jacket', 'Classic Levis denim trucker jacket.', 'Pria', 'Jacket', 'Levis', 'Baik', 'M', 'Blue', 1000000);

-- Products for HFRTM STORE
INSERT INTO products (seller_id, title, description, category, sub_category, brand, condition_item, size, color, price, is_negotiable) VALUES
(2, 'Lakers Short Pans', 'Size (M)\nPinggang - 80 Cm\nHip - 98 Cm\n\nTIDAK ADA MINUSS !!!', 'Pria', 'Bottom', 'NBA', 'Sangat baik', 'M', 'Black, Red', 220000, 1),
(2, 'Dickies Brown Hoodie', 'Dickies hoodie, great condition.', 'Pria', 'Hoodie', 'Dickies', 'Baik', 'M', 'Brown', 320000, 1),
(2, 'Brown Leather Jacket', 'Vintage brown leather jacket.', 'Pria', 'Jacket', NULL, 'Baik', 'M', 'Brown', 500000, 1),
(2, 'Pink Floyd Tee', 'Authentic Pink Floyd graphic tee.', 'Pria', 'Top', NULL, 'Sangat baik', 'M', 'Black', 500000, 1);

-- Product images (placeholders)
INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES
(1, 'uploads/products/kaos-putih-xonw.jpg', 1, 0),
(2, 'uploads/products/carhartt-jacket.jpg', 1, 0),
(3, 'uploads/products/dickies-hoodie.jpg', 1, 0),
(4, 'uploads/products/dickies-tee.jpg', 1, 0),
(5, 'uploads/products/dr-pepper-tee.jpg', 1, 0),
(6, 'uploads/products/levis-jacket.jpg', 1, 0),
(7, 'uploads/products/lakers-shorts.jpg', 1, 0),
(8, 'uploads/products/dickies-hoodie2.jpg', 1, 0),
(9, 'uploads/products/leather-jacket.jpg', 1, 0),
(10, 'uploads/products/pink-floyd-tee.jpg', 1, 0);

-- Product measurements
INSERT INTO product_measurements (product_id, label, value) VALUES
(1, 'Panjang', '71cm'), (1, 'Lebar', '55cm'),
(7, 'Pinggang', '80cm'), (7, 'Hip', '98cm');

-- Likes
INSERT INTO likes (user_id, product_id) VALUES (1, 7), (1, 9);

-- Cart
INSERT INTO cart (user_id, product_id) VALUES (1, 7);

-- Notifications
INSERT INTO notifications (user_id, from_user_id, product_id, type, message, created_at) VALUES
(1, 2, 7, 'order_shipped', 'HFRTM STORE mengatur pengiriman ke alamat anda.', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 2, 2, 'item_liked_sale', 'HFRTM STORE lagi diskon barang yang kamu like.', DATE_SUB(NOW(), INTERVAL 23 HOUR)),
(1, 4, 1, 'negotiation', 'MEDAN ingin nego Kaos Putih XONW mu.', DATE_SUB(NOW(), INTERVAL 9 DAY));

-- Reviews for HFRTM STORE
INSERT INTO orders (invoice_number, buyer_id, seller_id, product_id, product_price, shipping_cost, total_amount, status, payment_method, recipient_name, recipient_phone, shipping_address)
VALUES
('INV-001', 4, 2, 7, 220000, 20000, 240000, 'selesai', 'transfer_bank', 'Medan Store', '083344556677', 'Jl. Merdeka No.1, Medan'),
('INV-002', 3, 2, 8, 320000, 20000, 340000, 'selesai', 'e_wallet', 'Rasya Labrador', '081122334455', 'Jl. Sunda No.5, Bandung'),
('INV-003', 1, 2, 9, 500000, 20000, 520000, 'selesai', 'qr', 'KOLTUPS', '081223456789', 'Jl. DI Panjaitan No.128, Purwokerto');

INSERT INTO reviews (order_id, reviewer_id, seller_id, rating, comment) VALUES
(1, 4, 2, 4, 'Pelayanan cepat dan ramah'),
(2, 3, 2, 5, 'Bagus banget'),
(3, 1, 2, 5, 'Mantap');

-- Messages
INSERT INTO messages (sender_id, receiver_id, content) VALUES
(2, 1, 'WOI KALO NEGO NGOTAK DONG!!!!'),
(3, 1, 'Bang, mau jacket denim dong');
