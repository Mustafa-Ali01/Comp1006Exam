-- Create the database and tables
CREATE DATABASE IF NOT EXISTS gallery_db;
USE gallery_db;

-- Table for admin accounts
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL -- Storing the hashed password
);

-- Table to store image metadata
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL -- Path to the file in /uploads/
);