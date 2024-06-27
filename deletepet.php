<?php
include "db_connect.php"; // Ensure this file initializes $mysqli correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $petID = intval($_POST["pet_id"]);
    $query = "DELETE FROM pet WHERE ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $petID);
    if ($stmt->execute()) {
        echo "Pet deleted successfully.";
    } else {
        echo "Error deleting pet: " . $stmt->error;
    }
    $stmt->close();
}
?>
