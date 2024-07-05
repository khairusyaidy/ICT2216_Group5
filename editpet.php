<?php
// Include database connection script
include "dbconntest.php"; // Ensure this file initializes $mysqli correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $petID = $_POST['pet_id'];
    $editedName = $_POST['editPetName'];
    $editedAge = $_POST['editPetAge'];
    $editedBreed = $_POST['editPetBreed'];
    $editedWeight = $_POST['editPetWeight'];
    $editedCoatType = $_POST['editPetCoatType'];
    $editedGender = $_POST['editPetGender'];
    $editedBehaviour = $_POST['editPetBehaviour'];

    // Validate pet age (integer validation)
    if (!ctype_digit($editedAge)) {
        echo "Invalid age input. Please enter a valid integer.";
        exit(); // Stop further execution
    }

    // Update pet information in database
    $queryUpdatePet = "UPDATE pet SET Name=?, Age=?, Breed=?, Weight=?, CoatType=?, Gender=?, Behaviour=? WHERE ID=?";
    $stmtUpdatePet = $mysqli->prepare($queryUpdatePet);
    $stmtUpdatePet->bind_param("sssssssi", $editedName, $editedAge, $editedBreed, $editedWeight, $editedCoatType, $editedGender, $editedBehaviour, $petID);

    if ($stmtUpdatePet->execute()) {
        // Success: Redirect or show success message
        header("Location: profilepage.php"); // Redirect to profile page after successful update
        exit();
    } else {
        // Error: Handle error (display message or log)
        echo "Error updating pet information: " . $stmtUpdatePet->error;
    }

    $stmtUpdatePet->close();
}

// Close database connection
$mysqli->close();
?>
