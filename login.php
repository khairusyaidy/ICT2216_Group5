<?php
// Include database connection
include_once "db_connect.php";

// Initialize session
session_start();

// Check if user is already logged in, redirect to index.php if true
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

$email = $password = "";
$email_err = $password_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if email is empty
    if (empty($email)) {
        $email_err = "Please enter email.";
    }

    // Check if password is empty
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    // If no errors, proceed with authentication
    if (empty($email_err) && empty($password_err)) {
        // Prepare SQL statement
        $sql = "SELECT id, FirstName, LastName, Password FROM customer WHERE Email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $stmt->store_result();

                // Check if email exists
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $FirstName, $LastName, $hashed_password);
                    if ($stmt->fetch()) {
                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["FirstName"] = $FirstName;
                            $_SESSION["LastName"] = $LastName;

                            // Redirect user to index page
                            header("location: index.php");
                            exit;
                        } else {
                            // Display an error message if password is incorrect
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Fur Season Hotel</title>
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
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 1.2em;
            margin-bottom: 5px;
        }
        .form-group input {
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
        .signup-link {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        .signup-link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Log In</h1>
    </div>

    <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <span class="error-message"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error-message"><?php echo $password_err; ?></span>
        </div>

        <div class="buttons">
            <button type="submit">Log In</button>
            <a href="forgot_password.html">Forgot Password?</a>
        </div>

        <div class="signup-link">
            <p>Don't have an account yet? <a href="signup.php">Sign Up Here</a></p>
        </div>
    </form>
</div>

</body>
</html>
