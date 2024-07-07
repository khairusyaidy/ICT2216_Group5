<?php
ob_start();
// Include database connection
include_once "dbconntest.php";
include_once "send_otp_email.php"; // Include the email function

// Initialize session
session_start();

// Regenerate session ID for security
session_regenerate_id(true);

$email = $email_err = $otp_err = $otp_success = $password_err = "";
$otp = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["send_otp"])) {
        // Validate and sanitize email input
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

        // Check if email is empty or invalid
        if (empty($email)) {
            $email_err = "Please enter email.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        } else {
            // Check if email exists in customer or staff table
            $sql = "SELECT id FROM customer WHERE Email = ? UNION SELECT id FROM staff WHERE Email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $param_email, $param_email);
                $param_email = $email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    $stmt->store_result();

                    // Check if email exists
                    if ($stmt->num_rows == 1) {
                        // Generate and send OTP
                        $otp = rand(100000, 999999); // Generate a 6-digit OTP
                        $_SESSION["otp"] = $otp;
                        $_SESSION["email"] = $email;

                        // Send the OTP to the user's email
                        if (send_otp_email($email, $otp)) {
                            $otp_success = "OTP has been sent to your email. Please enter it below to verify.";
                        } else {
                            $email_err = "Failed to send OTP. Please try again later.";
                        }
                    } else {
                        $email_err = "Email not found.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    } elseif (isset($_POST["verify_otp"])) {
        // Validate and sanitize OTP input
        $entered_otp = trim($_POST["otp"]);

        // Check if OTP is empty
        if (empty($entered_otp)) {
            $otp_err = "Please enter the OTP.";
        } elseif ($entered_otp != $_SESSION["otp"]) {
            $otp_err = "Invalid OTP entered.";
        } else {
            // OTP is correct, show password reset form
            $_SESSION["otp_verified"] = true;
        }
    } elseif (isset($_POST["reset_password"])) {
        // Validate and sanitize password inputs
        $new_password = trim($_POST["new_password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if (empty($new_password) || empty($confirm_password)) {
            $password_err = "Please fill out all password fields.";
        } elseif ($new_password !== $confirm_password) {
            $password_err = "Passwords do not match.";
        } else {
            // Validate password strength
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $new_password)) {
                $password_err = "Password must contain at least 6 characters including at least one uppercase letter, one lowercase letter, and one number.";
            } else {
                // Update the password in the database
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $email = $_SESSION["email"];

                // Update password in customer table
                $sql_customer = "UPDATE customer SET Password = ? WHERE Email = ?";
                // Update password in staff table
                $sql_staff = "UPDATE staff SET Password = ? WHERE Email = ?";

                if ($stmt_customer = $conn->prepare($sql_customer)) {
                    $stmt_customer->bind_param("ss", $hashed_password, $email);

                    if ($stmt_customer->execute()) {
                        $password_updated_customer = true;
                    } else {
                        $password_updated_customer = false;
                    }

                    $stmt_customer->close();
                }

                if ($stmt_staff = $conn->prepare($sql_staff)) {
                    $stmt_staff->bind_param("ss", $hashed_password, $email);

                    if ($stmt_staff->execute()) {
                        $password_updated_staff = true;
                    } else {
                        $password_updated_staff = false;
                    }

                    $stmt_staff->close();
                }

                if ($password_updated_customer || $password_updated_staff) {
                    // Password updated successfully
                    $_SESSION['password_change_success'] = true;
                    $_SESSION['password_change_message'] = "Password has been successfully updated.";
                    header("location: login.php"); // Redirect to login page
                    exit;
                } else {
                    $password_err = "Failed to update password. Please try again later.";
                }
            }
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
    <title>Forgot Password - Fur Season Hotel</title>
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
        .error-message {
            color: red;
            font-size: 0.9em;
        }
        .success-message {
            color: green;
            font-size: 0.9em;
        }
    </style>
    <script>
        function showOtpField() {
            document.getElementById('otp-group').style.display = 'block';
        }
        function hideEmailAndOtpFields() {
            document.getElementById('email-group').style.display = 'none';
            document.getElementById('otp-group').style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Forgot Password</h1>
        </div>

        <form id="forgot-password-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div id="email-group" class="form-group" style="display: <?php echo (isset($_SESSION["otp_verified"]) && $_SESSION["otp_verified"]) ? 'none' : 'block'; ?>;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error-message"><?php echo $email_err; ?></span>
            </div>
            <?php if (!empty($otp_success)) : ?>
                <div class="success-message"><?php echo $otp_success; ?></div>
                <script>showOtpField();</script>
            <?php endif; ?>

            <div id="otp-group" class="form-group" style="display: <?php echo (!empty($otp_success) || isset($_SESSION["otp_verified"])) ? 'block' : 'none'; ?>;">
                <label for="otp">OTP:</label>
                <input type="text" id="otp" name="otp">
                <span class="error-message"><?php echo $otp_err; ?></span>
            </div>

            <div id="password-reset-group" class="form-group" style="display: <?php echo (isset($_SESSION["otp_verified"]) && $_SESSION["otp_verified"]) ? 'block' : 'none'; ?>;">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error-message"><?php echo $password_err; ?></span>
            </div>

            <div class="buttons">
                <?php if (empty($otp_success) && !isset($_SESSION["otp_verified"])) : ?>
                    <button type="submit" name="send_otp">Send OTP</button>
                <?php elseif (isset($_SESSION["otp_verified"]) && $_SESSION["otp_verified"]) : ?>
                    <script>hideEmailAndOtpFields();</script>
                    <button type="submit" name="reset_password">Reset Password</button>
                <?php else : ?>
                    <button type="submit" name="verify_otp">Verify OTP</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>
