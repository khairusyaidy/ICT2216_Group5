<?php
// Start the session
session_start();

// Include database connection script
include "dbconntest.php"; // Ensure this file initializes $mysqli correctly

// Function to sanitize and validate input
function sanitizeInput($input)
{
    global $mysqli; // Ensure $mysqli is accessible within the function
    return trim($mysqli->real_escape_string($input));
}

// Ensure the script only processes POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $petName = sanitizeInput($_POST['addPetName']);
    $petAge = sanitizeInput($_POST['addPetAge']);
    $petBreed = sanitizeInput($_POST['addPetBreed']);
    $petWeight = trim($_POST['addPetWeight']); // Directly trim input without htmlspecialchars
    $petCoatType = sanitizeInput($_POST['addPetCoatType']);
    $petGender = sanitizeInput($_POST['addPetGender']);
    $petBehaviour = sanitizeInput($_POST['addPetBehaviour']);

    // Check if session variable is set
    if (!isset($_SESSION['id'])) {
        echo "Error: Customer ID not found in session.";
        exit(); // Stop further execution
    }

    $customerID = $_SESSION['id']; // Corrected session variable name

    // Validate age as integer (using ctype_digit)
    if (!ctype_digit($petAge)) {
        echo "Invalid age input. Please enter a valid integer.";
        exit(); // Stop further execution
    }

    // Prepare and execute the SQL insertion query
    $insertQuery = "INSERT INTO pet (CustomerID, Name, Age, Breed, Weight, CoatType, Gender, Behaviour) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $mysqli->prepare($insertQuery);
    $insertStmt->bind_param("isdsssss", $customerID, $petName, $petAge, $petBreed, $petWeight, $petCoatType, $petGender, $petBehaviour);
    $insertStmt->execute();

    // Check if the insertion was successful
    if ($insertStmt->affected_rows > 0) {
        // Redirect back to profile page on success
        header("Location: profilepage.php");
        exit();
    } else {
        // Error handling: Display error message or log error
        echo "Error adding pet information: " . $insertStmt->error;
    }

    // Close statement
    $insertStmt->close();
}

// Close database connection
$mysqli->close();

// Redirect back to profilepage.php after processing (if not already redirected)
header("Location: profilepage.php");
exit();
?>
