<?php

// Include database connection script
include "db_connect.php"; // Ensure this file initializes $mysqli correctly

session_start(); // Start PHP session to access $_SESSION variables

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Validate and sanitize input
$currentPassword = sanitizeInput($_POST['currentPassword']);
$newPassword = sanitizeInput($_POST['newPassword']);
$confirmPassword = sanitizeInput($_POST['confirmPassword']);

// Retrieve customer ID from session
$customerID = $_SESSION['id'];

// Query to fetch current password hash from database
$query = "SELECT Password FROM customer WHERE ID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

// Verify if the current password matches the one in the database
if (password_verify($currentPassword, $hashedPassword)) {
    // Check if new password and confirm password match
    if ($newPassword === $confirmPassword) {
        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in the database
        
// Execute update query
$updateQuery = "UPDATE customer SET Password = ? WHERE ID = ?";
$updateStmt = $mysqli->prepare($updateQuery);
$updateStmt->bind_param("si", $hashedNewPassword, $customerID);

if ($updateStmt->execute()) {
    // Password updated successfully
    $_SESSION['password_change_success'] = true;
    header("Location: profilepage.php");
    exit();
} else {
    // Error updating password
    $_SESSION['password_change_error'] = "Error updating password: " . $mysqli->error;
    header("Location: profilepage.php");
    exit();
}

}
}else {
    // Redirect to profile page with error message
    $_SESSION['password_change_error'] = "Current password is incorrect.";
    header("Location: profilepage.php");
    exit();
}
?>
