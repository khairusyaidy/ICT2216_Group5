<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Fur Season Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%; /* Adjusted to full width for responsiveness */
            max-width: 500px; /* Adjust max-width as per your design */
            margin: 50px auto; /* Center the container */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: calc(100% - 22px); /* Adjusted width to fit within the container */
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .buttons a {
            font-size: 1em;
            color: #4CAF50;
            text-decoration: none;
            margin-left: 10px;
        }
        .error-message {
            color: #f44336;
            margin-top: 5px;
            font-size: 0.9em;
        }
        .pet-info {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Sign Up</h1>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SSDDB";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $first_name = $last_name = $email = $phone = $password = $confirm_password = "";
    $first_name_err = $last_name_err = $email_err = $phone_err = $password_err = $confirm_password_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize input
        if (empty(trim($_POST['first_name']))) {
            $first_name_err = "Please enter first name.";
        } else {
            $first_name = $conn->real_escape_string(trim($_POST['first_name']));
        }

        if (empty(trim($_POST['last_name']))) {
            $last_name_err = "Please enter last name.";
        } else {
            $last_name = $conn->real_escape_string(trim($_POST['last_name']));
        }

        if (empty(trim($_POST['email']))) {
            $email_err = "Please enter email.";
        } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        } else {
            $email = $conn->real_escape_string(trim($_POST['email']));
        }

        if (empty(trim($_POST['phone']))) {
            $phone_err = "Please enter phone number.";
        } elseif (!preg_match("/^[0-9]{8}$/", trim($_POST['phone']))) {
            $phone_err = "Phone number must be 8 digits.";
        } else {
            $phone = $conn->real_escape_string(trim($_POST['phone']));
        }

        if (empty(trim($_POST['password']))) {
            $password_err = "Please enter password.";
        } else {
            $password = trim($_POST['password']);
        }

        if (empty(trim($_POST['confirm-password']))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST['confirm-password']);
            if ($password != $confirm_password) {
                $confirm_password_err = "Passwords do not match.";
            }
        }

        if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err)) {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            // Insert customer into database
            $sql = "INSERT INTO customer (FirstName, LastName, Email, PhoneNo, Password) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password_hashed')";
            if ($conn->query($sql) === TRUE) {
                $customerID = $conn->insert_id;

                // Loop through pets and insert them into the database
                for ($i = 1; isset($_POST['pet-name-' . $i]); $i++) {
                    $petName = $conn->real_escape_string($_POST['pet-name-' . $i]);
                    $petAge = (int)$_POST['pet-age-' . $i];
                    $petBreed = $conn->real_escape_string($_POST['pet-breed-' . $i]);
                    $petWeight = (float)$_POST['pet-weight-' . $i];
                    $petCoatType = $conn->real_escape_string($_POST['pet-coat-' . $i]);
                    $petGender = $conn->real_escape_string($_POST['pet-gender-' . $i]);
                    $petBehaviour = $conn->real_escape_string($_POST['pet-behaviour-' . $i]);

                    $sql = "INSERT INTO pet (Name, Age, Breed, Weight, CoatType, Gender, Behaviour, CustomerID) VALUES ('$petName', '$petAge', '$petBreed', '$petWeight', '$petCoatType', '$petGender', '$petBehaviour', '$customerID')";
                    $conn->query($sql);
                }

                echo "<div style='background-color: #4CAF50; color: white; padding: 10px; margin-bottom: 20px;'>Registration successful!</div>";
            } else {
                echo "<div style='background-color: #f44336; color: white; padding: 10px; margin-bottom: 20px;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }
    }

    $conn->close();
    ?>

    <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
        <div class="section">
            <h2>Owner Info</h2>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
                <span class="error-message"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
                <span class="error-message"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="phone">Phone No.:</label>
                <input type="text" id="phone" name="phone" required>
                <span class="error-message"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <span class="error-message"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <span class="error-message"><?php echo $confirm_password_err; ?></span>
            </div>
        </div>

        <div class="section">
            <h2>Pet Info</h2>
            <div class="pet-info" id="pet-info-1">
                <div class="form-group">
                    <label for="pet-name-1">Pet Name:</label>
                    <input type="text" id="pet-name-1" name="pet-name-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-age-1">Age:</label>
                    <input type="text" id="pet-age-1" name="pet-age-1" pattern="[0-9]*" title="Age must be numeric" required>
                </div>
                <div class="form-group">
                    <label for="pet-breed-1">Breed:</label>
                    <input type="text" id="pet-breed-1" name="pet-breed-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-weight-1">Weight:</label>
                    <select id="pet-weight-1" name="pet-weight-1" required>
                        <option value="">Select Weight</option>
                        <option value="<5kg">&lt;5kg</option>
                        <option value="<10kg">&lt;10kg</option>
                        <option value="<15kg">&lt;15kg</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pet-coat-1">Coat Type:</label>
                    <input type="text" id="pet-coat-1" name="pet-coat-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-gender-1">Gender:</label>
                    <select id="pet-gender-1" name="pet-gender-1" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pet-behaviour-1">Behaviour:</label>
                    <textarea id="pet-behaviour-1" name="pet-behaviour-1" rows="3" required></textarea>
                </div>
            </div>
        </div>

        <div class="buttons">
            <button type="button" onclick="addPetInfo()">Add Another Pet</button>
        </div>

        <div class="buttons">
            <button type="submit">Sign Up</button>
            <a href="login.php">Already have an account? Sign In</a>
        </div>
    </form>
</div>

<script>
    var petCount = 1;

    function addPetInfo() {
        petCount++;
        var petInfo = document.createElement('div');
        petInfo.className = 'pet-info';
        petInfo.id = 'pet-info-' + petCount;
        petInfo.innerHTML = `
            <div class="form-group">
                <label for="pet-name-${petCount}">Pet Name:</label>
                <input type="text" id="pet-name-${petCount}" name="pet-name-${petCount}" required>
            </div>
            <div class="form-group">
                <label for="pet-age-${petCount}">Age:</label>
                <input type="text" id="pet-age-${petCount}" name="pet-age-${petCount}" pattern="[0-9]*" title="Age must be numeric" required>
            </div>
            <div class="form-group">
                <label for="pet-breed-${petCount}">Breed:</label>
                <input type="text" id="pet-breed-${petCount}" name="pet-breed-${petCount}" required>
            </div>
            <div class="form-group">
                <label for="pet-weight-${petCount}">Weight:</label>
                <select id="pet-weight-${petCount}" name="pet-weight-${petCount}" required>
                    <option value="">Select Weight</option>
                    <option value="<5kg">&lt;5kg</option>
                    <option value="<10kg">&lt;10kg</option>
                    <option value="<15kg">&lt;15kg</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-coat-${petCount}">Coat Type:</label>
                <input type="text" id="pet-coat-${petCount}" name="pet-coat-${petCount}" required>
            </div>
            <div class="form-group">
                <label for="pet-gender-${petCount}">Gender:</label>
                <select id="pet-gender-${petCount}" name="pet-gender-${petCount}" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-behaviour-${petCount}">Behaviour:</label>
                <textarea id="pet-behaviour-${petCount}" name="pet-behaviour-${petCount}" rows="3" required></textarea>
            </div>
        `;
        document.getElementById('signup-form').appendChild(petInfo);
    }

    function validateForm() {
        var isValid = true;

        // Validate Phone No.
        var phoneInput = document.getElementById('phone');
        if (!/^[0-9]{8}$/.test(phoneInput.value)) {
            displayError(phoneInput, "Phone number must be 8 digits.");
            isValid = false;
        } else {
            clearError(phoneInput);
        }

        // Validate Email
        var emailInput = document.getElementById('email');
        if (!/^\S+@\S+\.\S+$/.test(emailInput.value)) {
            displayError(emailInput, "Invalid email format.");
            isValid = false;
        } else {
            clearError(emailInput);
        }

        // Validate Password
        var passwordInput = document.getElementById('password');
        var confirmPasswordInput = document.getElementById('confirm-password');
        if (passwordInput.value !== confirmPasswordInput.value) {
            displayError(confirmPasswordInput, "Passwords do not match.");
            isValid = false;
        } else {
            clearError(confirmPasswordInput);
        }


        // Validate Pet Ages
        var petAgeInputs = document.querySelectorAll('[id^="pet-age-"]');
        petAgeInputs.forEach(function(input) {
            if (!/^\d+$/.test(input.value)) {
                displayError(input, "Age must be numeric.");
                isValid = false;
            } else {
                clearError(input);
            }
        });
    
        // Validate Pet Weight (at least one pet must have a weight selected)
        var petWeightInputs = document.querySelectorAll('[id^="pet-weight-"]');
        var hasValidPetWeight = Array.from(petWeightInputs).some(function(input) {
            return input.value !== "";
        });

        if (!hasValidPetWeight) {
            var firstPetWeightInput = document.getElementById('pet-weight-1');
            displayError(firstPetWeightInput, "Please select pet weight.");
            isValid = false;
        } else {
            Array.from(petWeightInputs).forEach(function(input) {
                clearError(input);
            });
        }

        return isValid;
    }

    function displayError(element, message) {
        var errorMessageElement = document.createElement('span');
        errorMessageElement.className = 'error-message';
        errorMessageElement.textContent = message;
        element.parentNode.appendChild(errorMessageElement);
    }

    function clearError(element) {
        var errorMessageElement = element.parentNode.querySelector('.error-message');
        if (errorMessageElement) {
            errorMessageElement.remove();
        }
    }
</script>

</body>
</html>
