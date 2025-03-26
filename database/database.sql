-- admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin', 'moderator') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- admin user
INSERT INTO admin (username, email, password, role)
VALUES (
        'admin',
        'admin@gmail.com',
        '$2y$10$9aR/dvcsuviFlaao3SYBWulgIIHgoU85.MQIeeEDdsIj6cHs9VFsS',
        'superadmin'
    );
-- categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('1', '0') DEFAULT '1',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- add categories
INSERT INTO categories (name, description, status)
VALUES ('keychains', 'A diverse and functional collection of key chains, designed to keep your keys organized while adding a touch of personality to your daily carry.', 'active');