CREATE DATABASE IF NOT EXISTS shop
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE shop;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity_in_storage INT NOT NULL DEFAULT 0,
    categorie_id INT NOT NULL,
    CONSTRAINT fk_products_categorie
        FOREIGN KEY (categorie_id) REFERENCES categories(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tel VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    status ENUM('новый', 'в обработке', 'доставлен', 'отменён') NOT NULL DEFAULT 'новый',
    data DATE NOT NULL,
    client_id INT NOT NULL,
    CONSTRAINT fk_orders_client
        FOREIGN KEY (client_id) REFERENCES clients(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS positions_order (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_positions_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_positions_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (title) VALUES
('Электроника'),
('Одежда'),
('Книги');

INSERT INTO products (title, price, quantity_in_storage, categorie_id) VALUES
('Продукт1', 25000.00, 10, 1),
('Продукт2', 60000.00, 5, 1),
('Продукт3', 1500.00, 50, 2),
('Продукт4', 4000.00, 20, 2),
('Продукт5', 800.00, 30, 3);

INSERT INTO clients (name, email, tel) VALUES
('1', '1@example.com', '+79001234567'),
('2', '2@example.com', '+79007654321'),
('3', '3@example.com', '+79001112233');