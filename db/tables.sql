-- Create Users table
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- This should be a hash, not a plain text password
    is_admin BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Files table
CREATE TABLE Files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES Users(id)
);

-- Insert some test users
-- In practice, passwords should be hashed before being stored in the database
-- Here we're using plain text for simplicity, but this is not secure
INSERT INTO Users (email, password, is_admin) VALUES ('uploadadmin@aniron.us', 'admin123', 1);
INSERT INTO Users (email, password, is_admin) VALUES ('user@aniron.us', 'user123', 0);

-- Insert some test files
INSERT INTO Files (name, path, uploaded_by) VALUES ('test_file_1.txt', '/path/to/test_file_1.txt', 1);
INSERT INTO Files (name, path, uploaded_by) VALUES ('test_file_2.txt', '/path/to/test_file_2.txt', 1);

