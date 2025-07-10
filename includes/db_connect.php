<?php
// db_connect.php

$servername = "localhost"; // Your database server name (usually localhost)
$username = "root";      // Your database username (e.g., root for XAMPP/WAMP)
$password = "";          // Your database password (empty for XAMPP/WAMP by default)
$dbname = "notesync_db";  // The name of the database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8
$conn->set_charset("utf8");

// You can now use $conn to interact with your database
// Example: $result = $conn->query("SELECT * FROM users");
?>
