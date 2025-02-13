CREATE DATABASE IF NOT EXISTS testing_db;
USE testing_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    INDEX idx_name (name)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    INDEX idx_total_price (total_price)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
);

INSERT INTO products (name, price) VALUES
('Товар1', 100.0),
('Товар2', 500.0);
