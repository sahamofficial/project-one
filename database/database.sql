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