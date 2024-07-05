<?php
// Include your database configuration file here
include "dbconntest.php"; // Adjust path as necessary

// Retrieve form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
$role = trim($_POST["role"]);

// SQL insert statement
$stmt = $conn->prepare("INSERT INTO staff (FirstName, LastName, Email, PhoneNo, Password, Role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $password, $role);

// Execute and check for success
if ($stmt->execute()) {
    // Redirect to staff account management page or show success message
    header("Location: staffaccount.php");
    exit();
} else {
    // Handle error
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
