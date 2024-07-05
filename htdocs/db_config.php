<?php
$servername = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "soubu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
