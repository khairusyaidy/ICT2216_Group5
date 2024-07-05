<?php
$servername = "g5-mysql-db";
$username = "g5dba";
$password = "Group$2216!";
$dbname = "g5db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
