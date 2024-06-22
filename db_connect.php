<?php
$servername = "localhost";  // Replace with your MySQL server hostname
$username = "root";         // Replace with your MySQL username
$password = "";             // Replace with your MySQL password
$dbname = "SSDDB";  // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
