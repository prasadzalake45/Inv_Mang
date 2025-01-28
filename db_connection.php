<?php
// Database connection settings
$host = 'localhost';  // Database host (usually localhost)
$db_name = 'inv_mang';  // Replace with your database name
$username = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// You can also set the charset to avoid issues with special characters
$conn->set_charset("utf8");

// Optional: You can define constants or functions for your connection if necessary
// Example: define('DB_CONNECTION', $conn);

?>
