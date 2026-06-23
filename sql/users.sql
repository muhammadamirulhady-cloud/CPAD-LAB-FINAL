USE books_api;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('member', 'admin') NOT NULL DEFAULT 'member',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (name, email, password_hash, role) VALUES
('Demo Admin', 'admin@books.test', '$2y$10$I62XqN3z.m8z7B3y87Z9e.LwN2/L9b9w2Z3x4c5v6b7n8m9q0w1e2', 'admin'),
('Demo Member', 'member@books.test', '$2y$10$I62XqN3z.m8z7B3y87Z9e.LwN2/L9b9w2Z3x4c5v6b7n8m9q0w1e2', 'member');