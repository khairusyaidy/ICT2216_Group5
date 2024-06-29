<?php

require_once 'db_connect.php';

if (isset($_POST['petID'])) {
    $petID = $_POST['petID'];

    // Fetch the weight of the selected pet
    $stmt = $conn->prepare("SELECT Weight FROM pet WHERE ID = ?");
    $stmt->bind_param("i", $petID);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $weight = $pet['Weight'];

    // Determine the weight range description for the query
    if ($weight == "<5kg") {
        $weightRangePattern = "(5kg or less)";
    } elseif ($weight == "<10kg") {
        $weightRangePattern = "(10kg or less)";
    } else {
        $weightRangePattern = "(15kg or less)";
    }

    $stmt->close();

    // Fetch the services for the determined weight range description
    $stmt = $conn->prepare("SELECT * FROM service WHERE ServiceName LIKE ?");
    $weightRangeLike = "%$weightRangePattern%";
    $stmt->bind_param("s", $weightRangeLike);
    $stmt->execute();
    $result = $stmt->get_result();

    $services = []; // Array to store services
    while ($row = $result->fetch_assoc()) {
        $services[] = $row; // Store each service in the array
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Output HTML for service selection
    echo '<p><b>Select a service:</b></p>';
    foreach ($services as $service) {
        echo '<div>';
        echo '<input type="radio" id="service_' . htmlspecialchars($service['ID']) . '" name="service" value="' . htmlspecialchars($service['ID']) . '" data-name="' . htmlspecialchars($service['ServiceName']) . '" data-price="' . htmlspecialchars($service['Price']) . '">';
        echo '<label for="service_' . htmlspecialchars($service['ID']) . '">' . htmlspecialchars($service['ServiceName']) . ' - $' . htmlspecialchars($service['Price']) . '</label>';
        echo '</div>';
    }
}
?>
