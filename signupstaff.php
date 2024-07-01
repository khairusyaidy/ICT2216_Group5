<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Sign Up</title>
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
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: calc(100% - 22px);
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
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Staff Sign Up</h1>
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

    $first_name = $last_name = $email = $phone = $password = "";
    $first_name_err = $last_name_err = $email_err = $phone_err = $password_err = "";

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

        if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($phone_err) && empty($password_err)) {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            // Insert staff into database
            $sql = "INSERT INTO staff (FirstName, LastName, Email, PhoneNo, Password) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password_hashed')";
            if ($conn->query($sql) === TRUE) {
                echo "<div style='background-color: #4CAF50; color: white; padding: 10px; margin-bottom: 20px;'>Registration successful!</div>";
            } else {
                echo "<div style='background-color: #f44336; color: white; padding: 10px; margin-bottom: 20px;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }
    }

    $conn->close();
    ?>

    <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
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
        <div class="buttons">
            <button type="submit">Sign Up</button>
            <a href="login.php">Already have an account? Sign In</a>
        </div>
    </form>
</div>

<script>
    function validateForm() {
        var isValid = true;

        // Example client-side validation (can be enhanced)
        var phoneInput = document.getElementById('phone');
        if (!/^[0-9]{8}$/.test(phoneInput.value)) {
            displayError(phoneInput, "Phone number must be 8 digits.");
            isValid = false;
        } else {
            clearError(phoneInput);
        }

        // Example email validation (can be enhanced)
        var emailInput = document.getElementById('email');
        if (!/^\S+@\S+\.\S+$/.test(emailInput.value)) {
            displayError(emailInput, "Invalid email format.");
            isValid = false;
        } else {
            clearError(emailInput);
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
