<?php
// Include database connection script
include "db_connect.php"; // Ensure this file initializes $mysqli correctly



// Function to sanitize and validate input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Function to validate age as integer
function validateAge($age)
{
    return preg_match('/^\d+$/', $age);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "head.inc.php"; ?>
</head>

<body>
    <!-- Topbar Start -->
    <?php include "topbar.inc.php"; ?>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <?php include "nav.inc.php"; ?>
    <!-- Navbar End -->

    <!-- Profile Content Start -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4">Owner Info</h2>
                <?php
                // Fetch and display customer information
                $customerID = $_SESSION['id']; // Corrected session variable name
                $query = "SELECT FirstName, LastName, Email, PhoneNo FROM customer WHERE ID = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $customerID);
                $stmt->execute();
                $stmt->bind_result($firstName, $lastName, $email, $phoneNo);
                $stmt->fetch();
                $stmt->close();
                ?>
                <p>Name: <?php echo htmlspecialchars($firstName . " " . $lastName); ?></p>
                <p>Phone No.: <?php echo htmlspecialchars($phoneNo); ?></p>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>

                <!-- Change Password Form -->
                <div class="d-inline-block mb-4">
                    <form action="changepassword.php" method="post">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
                <hr>

                <h2 class="mt-4 mb-4">Pets Info</h2>
                <!-- Add Pet Button -->
                <button class="btn btn-success mr-2 mb-4" data-toggle="modal" data-target="#addPetModal">Add Pet</button>

                <?php
                // Fetch and display pets information
                $queryPets = "SELECT ID, Name, Age, Breed, Weight, CoatType, Gender, Behaviour FROM pet WHERE CustomerID = ?";
                $stmtPets = $mysqli->prepare($queryPets);
                $stmtPets->bind_param("i", $customerID);
                $stmtPets->execute();
                $stmtPets->bind_result($petID, $petName, $petAge, $petBreed, $petWeight, $petCoatType, $petGender, $petBehaviour);

                while ($stmtPets->fetch()) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($petName) . "</h5>";
                    echo "<p class='card-text'>Age: " . htmlspecialchars($petAge) . "</p>";
                    echo "<p class='card-text'>Breed: " . htmlspecialchars($petBreed) . "</p>";
                    echo "<p class='card-text'>Weight: " . htmlspecialchars($petWeight) . "</p>";
                    echo "<p class='card-text'>Coat Type: " . htmlspecialchars($petCoatType) . "</p>";
                    echo "<p class='card-text'>Gender: " . htmlspecialchars($petGender) . "</p>";
                    echo "<p class='card-text'>Behaviour: " . htmlspecialchars($petBehaviour) . "</p>";
                    echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editPetModal$petID'>Edit</button>";
                    echo "<button class='btn btn-danger ml-2' onclick='confirmDelete($petID)'>Delete</button>";
                    echo "</div>";
                    echo "</div>";

                    
                    
                    
                    
                    
                    
                    
                    
                    
                     // Add Pet Modal
                    echo "<div class='modal fade' id='addPetModal' tabindex='-1' role='dialog' aria-labelledby='addPetModalLabel' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='addPetModalLabel'>Add Pet</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";

                    // Add Pet form with validations
                    echo "<form action='addpet.php' method='post' id='addPetForm'>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetName'>Name</label>";
                    echo "<input type='text' class='form-control' id='addPetName' name='addPetName' value=''>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetAge'>Age</label>";
                    echo "<input type='text' class='form-control' id='addPetAge' name='addPetAge' value=''>";
                    echo "<small class='text-danger' id='addPetAgeError'></small>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetBreed'>Breed</label>";
                    echo "<input type='text' class='form-control' id='addPetBreed' name='addPetBreed' value=''>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetWeight'>Weight (kg)</label>";
                    echo "<select class='form-control' id='addPetWeight' name='addPetWeight'>";
                    echo "<option value='<5kg'" . ($petWeight == "<5kg" ? " selected" : "") . ">Less than 5kg</option>";
                    echo "<option value='<10kg'" . ($petWeight == "<10kg" ? " selected" : "") . ">Less than 10kg</option>";
                    echo "<option value='<15kg'" . ($petWeight == "<15kg" ? " selected" : "") . ">Less than 15kg</option>";
                    echo "</select>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetCoatType'>Coat Type</label>";
                    echo "<input type='text' class='form-control' id='addPetCoatType' name='addPetCoatType' value=''>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetGender'>Gender</label>";
                    echo "<input type='text' class='form-control' id='addPetGender' name='addPetGender' value=''>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='addPetBehaviour'>Behaviour</label>";
                    echo "<input type='text' class='form-control' id='addPetBehaviour' name='addPetBehaviour' value=''>";
                    echo "</div>";
                    echo "<button type='button' class='btn btn-primary' onclick='validateAddPetForm()'>Add Pet</button>";
                    echo "</form>";

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    // Edit Pet Modal
                    echo "<div class='modal fade' id='editPetModal$petID' tabindex='-1' role='dialog' aria-labelledby='editPetModalLabel$petID' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='editPetModalLabel$petID'>Edit Pet</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";

                    // Edit Pet form with validations
                    echo "<form action='editpet.php' method='post' id='editPetForm$petID'>";
                    echo "<input type='hidden' name='pet_id' value='$petID'>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetName'>Name</label>";
                    echo "<input type='text' class='form-control' id='editPetName' name='editPetName' value='" . htmlspecialchars($petName) . "'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetAge'>Age</label>";
                    echo "<input type='text' class='form-control' id='editPetAge' name='editPetAge' value='" . htmlspecialchars($petAge) . "'>";
                    echo "<small class='text-danger' id='editPetAgeError$petID'></small>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetBreed'>Breed</label>";
                    echo "<input type='text' class='form-control' id='editPetBreed' name='editPetBreed' value='" . htmlspecialchars($petBreed) . "'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetWeight'>Weight (kg)</label>";
                    echo "<select class='form-control' id='editPetWeight' name='editPetWeight'>";
                    echo "<option value='<5kg'" . ($petWeight == "<5kg" ? " selected" : "") . ">Less than 5kg</option>";
                    echo "<option value='<10kg'" . ($petWeight == "<10kg" ? " selected" : "") . ">Less than 10kg</option>";
                    echo "<option value='<15kg'" . ($petWeight == "<15kg" ? " selected" : "") . ">Less than 15kg</option>";
                    echo "</select>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetCoatType'>Coat Type</label>";
                    echo "<input type='text' class='form-control' id='editPetCoatType' name='editPetCoatType' value='" . htmlspecialchars($petCoatType) . "'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetGender'>Gender</label>";
                    echo "<input type='text' class='form-control' id='editPetGender' name='editPetGender' value='" . htmlspecialchars($petGender) . "'>";
                    echo "</div>";
                    echo "<div class='form-group'>";
                    echo "<label for='editPetBehaviour'>Behaviour</label>";
                    echo "<input type='text' class='form-control' id='editPetBehaviour' name='editPetBehaviour' value='" . htmlspecialchars($petBehaviour) . "'>";
                    echo "</div>";
                    echo "<button type='button' class='btn btn-primary' onclick='validateEditPetForm($petID)'>Save Changes</button>";
                    echo "</form>";

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    

                    // Delete Pet Modal
                    echo "<div class='modal fade' id='deletePetModal$petID' tabindex='-1' role='dialog' aria-labelledby='deletePetModalLabel$petID' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='deletePetModalLabel$petID'>Delete Pet</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<p>Are you sure you want to delete " . htmlspecialchars($petName) . "?</p>";
                    echo "<form action='deletepet.php' method='post'>";
                    echo "<input type='hidden' name='pet_id' value='$petID'>";
                    echo "<button type='submit' class='btn btn-danger'>Delete</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }

                $stmtPets->close();
                ?>
            </div>
        </div>
    </div>
    <!-- Profile Content End -->

    <!-- Footer Start -->
    <?php include "footer.inc.php"; ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/select2/js/select2.full.min.js"></script>
    <script src="lib/sweetalert/sweetalert.min.js"></script>
    <script src="lib/jquery-steps/jquery.steps.min.js"></script>
    <script src="lib/parsleyjs/parsley.min.js"></script>
    <script src="lib/Chart.js/Chart.min.js"></script>
    <script src="js/main.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Client-side validation for pet age as integer
        function validateEditPetForm(petID) {
            var editedAge = document.getElementById('editPetAge').value.trim();

            // Check if age is not empty and is a valid integer
            if (editedAge === '' || !(/^\d+$/.test(editedAge))) {
                document.getElementById('editPetAgeError'+petID).textContent = 'Age must be a valid integer.';
                return false;
            } else {
                document.getElementById('editPetForm'+petID).submit(); // Submit form if validation passes
            }
        }

        // Client-side validation for adding a new pet
        function validateAddPetForm() {
            var petAge = document.getElementById('addPetAge').value.trim();

            // Check if age is not empty and is a valid integer
            if (petAge === '' || !(/^\d+$/.test(petAge))) {
                document.getElementById('addPetAgeError').textContent = 'Age must be a valid integer.';
                return false;
            } else {
                document.getElementById('addPetForm').submit(); // Submit form if validation passes
            }
        }
        
        
        function confirmDelete(petID) {
            if (confirm("Are you sure you want to delete this pet?")) {
                // Create an AJAX request to delete the pet
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "deletepet.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Reload the page or handle the response as needed
                        location.reload();
                    }
                };
                xhr.send("pet_id=" + petID);
            }
        }
        
    </script>

   

</body>

</html>
