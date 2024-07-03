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
                    $petWeight = $conn->real_escape_string($_POST['pet-weight-' . $i]);
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
                <span class="error-message" id="password-err"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <span class="error-message"><?php echo $confirm_password_err; ?></span>
            </div>
        </div>

        <div class="section" id="pet-info-section">
            <h2>Pet Info</h2>
            <div id="pet-info-1" class="pet-info">
                <div class="form-group">
                    <label for="pet-name-1">Pet Name:</label>
                    <input type="text" id="pet-name-1" name="pet-name-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-age-1">Pet Age:</label>
                    <input type="number" id="pet-age-1" name="pet-age-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-breed-1">Pet Breed:</label>
                    <input type="text" id="pet-breed-1" name="pet-breed-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-weight-1">Pet Weight:</label>
                    <select id="pet-weight-1" name="pet-weight-1" required>
                        <option value="<5kg"><5kg</option>
                        <option value="<10kg"><10kg</option>
                        <option value="<15kg"><15kg</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pet-coat-1">Coat Type:</label>
                    <input type="text" id="pet-coat-1" name="pet-coat-1" required>
                </div>
                <div class="form-group">
                    <label for="pet-gender-1">Pet Gender:</label>
                    <select id="pet-gender-1" name="pet-gender-1" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pet-behaviour-1">Pet Behaviour:</label>
                    <textarea id="pet-behaviour-1" name="pet-behaviour-1" required></textarea>
                </div>
            </div>
        </div>

        <div class="buttons">
            <button type="button" onclick="addPetInfo()">Add Another Pet</button>
        </div>

        <div class="buttons">
            <button type="submit">Sign Up</button>
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </form>
</div>

<script>
    let petCounter = 1;

    function addPetInfo() {
        petCounter++;
        const petInfoSection = document.getElementById('pet-info-section');
        const newPetInfo = document.createElement('div');
        newPetInfo.className = 'pet-info';
        newPetInfo.id = 'pet-info-' + petCounter;
        newPetInfo.innerHTML = `
            <h3>Pet ${petCounter} Info</h3>
            <div class="form-group">
                <label for="pet-name-${petCounter}">Pet Name:</label>
                <input type="text" id="pet-name-${petCounter}" name="pet-name-${petCounter}" required>
            </div>
            <div class="form-group">
                <label for="pet-age-${petCounter}">Pet Age:</label>
                <input type="number" id="pet-age-${petCounter}" name="pet-age-${petCounter}" required>
            </div>
            <div class="form-group">
                <label for="pet-breed-${petCounter}">Pet Breed:</label>
                <input type="text" id="pet-breed-${petCounter}" name="pet-breed-${petCounter}" required>
            </div>
            <div class="form-group">
                <label for="pet-weight-${petCounter}">Pet Weight:</label>
                <select id="pet-weight-${petCounter}" name="pet-weight-${petCounter}" required>
                    <option value="<5kg"><5kg</option>
                    <option value="<10kg"><10kg</option>
                    <option value="<15kg"><15kg</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-coat-${petCounter}">Coat Type:</label>
                <input type="text" id="pet-coat-${petCounter}" name="pet-coat-${petCounter}" required>
            </div>
            <div class="form-group">
                <label for="pet-gender-${petCounter}">Pet Gender:</label>
                <select id="pet-gender-${petCounter}" name="pet-gender-${petCounter}" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-behaviour-${petCounter}">Pet Behaviour:</label>
                <textarea id="pet-behaviour-${petCounter}" name="pet-behaviour-${petCounter}" required></textarea>
            </div>
        `;
        petInfoSection.appendChild(newPetInfo);
    }

    function validateForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const passwordErr = document.getElementById('password-err');
        
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        
        if (!passwordRegex.test(password)) {
            passwordErr.textContent = "Password must be at least 6 characters, with at least one uppercase and one lowercase letter.";
            return false;
        } else {
            passwordErr.textContent = "";
        }
        
        if (password !== confirmPassword) {
            passwordErr.textContent = "Passwords do not match.";
            return false;
        }
        
        return true;
    }
</script>

</body>
</html>
