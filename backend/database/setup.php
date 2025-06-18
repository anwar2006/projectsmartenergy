<?php
require_once '../../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Create database if not exists
    $db->exec("CREATE DATABASE IF NOT EXISTS smart_energy");
    $db->exec("USE smart_energy");
    
    // Create users table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        role ENUM('super_admin', 'admin', 'client') NOT NULL DEFAULT 'client',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    )");
    
    // Create energy_consumption table
    $db->exec("CREATE TABLE IF NOT EXISTS energy_consumption (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        consumption_value DECIMAL(10,2) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    
    // Insert default super admin if not exists
    $stmt = $db->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $password = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password, email, role) VALUES ('admin', :password, 'admin@example.com', 'super_admin')");
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
    
    echo "Database setup completed successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 