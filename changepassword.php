<?php
// Start session
session_start();

// Include database connection script
include "dbconntest.php";

// Function to sanitize and validate input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $currentPassword = sanitizeInput($_POST['currentPassword']);
    $newPassword = sanitizeInput($_POST['newPassword']);
    $confirmPassword = sanitizeInput($_POST['confirmPassword']);

    // Retrieve customer ID from session
    $customerID = $_SESSION['id'];

    // Query to retrieve hashed password from database
    $query = "SELECT Password FROM customer WHERE ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify if current password matches the stored password
    if (password_verify($currentPassword, $storedPassword)) {
        // Current password matches, proceed to update password
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password in database
            $updateQuery = "UPDATE customer SET Password = ? WHERE ID = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $customerID);
            $updateStmt->execute();
            $updateStmt->close();

            // Set success message
            $_SESSION['password_change_success'] = true;
            echo 'success'; // Send success response
        } else {
            echo 'New password and confirm password do not match.';
        }
    } else {
        // Current password does not match, set error message
        echo 'Current password is incorrect.';
    }
} else {
    // Redirect if accessed directly
    header("Location: profilepage.php");
    exit();
}
?>
