<?php
ob_start();
include_once "dbconntest.php";
session_start();

require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$email = $email_err = $otp_err = $password_err = "";
$new_password = $confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify_email'])) {
        $email = trim($_POST["email"]);

        if (empty($email)) {
            $email_err = "Please enter email.";
        } else {
            $sql = "SELECT id, GoogleAuthenticatorSecret FROM customer WHERE Email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $param_email);
                $param_email = $email;

                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $google_secret);
                        if ($stmt->fetch()) {
                            $_SESSION['temp_id'] = $id;
                            $_SESSION['email'] = $email;
                            $_SESSION['google_secret'] = $google_secret;
                            $_SESSION['otp_attempts'] = 0;
                            $_SESSION["email_verified"] = true;
                        }
                    } else {
                        $email_err = "No account found with that email.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    } elseif (isset($_POST['verify_otp'])) {
        $otp = trim($_POST["auth_code"]);
        $google_secret = $_SESSION['google_secret'];

        $g = new GoogleAuthenticator();

        if ($g->checkCode($google_secret, $otp)) {
            $_SESSION["otp_verified"] = true;
        } else {
            $otp_err = "Invalid OTP entered.";
            $_SESSION['otp_attempts'] += 1;

            if ($_SESSION['otp_attempts'] >= 3) {
                $_SESSION['otp_attempts'] = 0;  // Reset the attempts
                echo '<script>alert("Invalid authentication code. Redirecting to login page."); window.location.href = "login.php";</script>';
                exit;
            }
        }
    } elseif (isset($_POST['reset_password'])) {
        $new_password = trim($_POST["new_password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if (empty($new_password) || empty($confirm_password)) {
            $password_err = "Please fill out both password fields.";
        } elseif ($new_password !== $confirm_password) {
            $password_err = "Passwords do not match.";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,16}$/', $new_password)) {
            $password_err = "Password must be 8-16 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } else {
            $id = $_SESSION['temp_id'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE customer SET Password = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $param_password, $param_id);
                $param_password = $hashed_password;
                $param_id = $id;

                if ($stmt->execute()) {
                    session_unset();
                    session_destroy();
                    echo '<script>alert("Password successfully changed. Redirecting to login page."); window.location.href = "login.php";</script>';
                    exit;
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }
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
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 100%; max-width: 500px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 1.2em; margin-bottom: 5px; }
        .form-group input { width: calc(100% - 22px); padding: 10px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px; }
        .buttons { text-align: center; margin-top: 20px; }
        .buttons button { padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px; }
        .buttons a { font-size: 1em; color: #4CAF50; text-decoration: none; margin-left: 10px; }
        .signup-link { display: block; text-align: center; margin-top: 10px; }
        .signup-link a { color: #4CAF50; text-decoration: none; }
        .error-message { color: red; font-size: 0.9em; }
        .password-strength-bar { height: 5px; background-color: #ddd; border-radius: 5px; overflow: hidden; margin-top: 5px; }
        .password-strength-bar-inner { height: 100%; width: 0; transition: width 0.5s; }
        .password-strength-label { font-weight: bold; color: white; padding: 3px 5px; border-radius: 5px; display: inline-block; margin-top: 5px; }
    </style>
    <script>
        function updatePasswordStrengthBar(password) {
            var strength = calculatePasswordStrength(password);
            var bar = document.getElementById('password-strength-bar-inner');
            var label = document.getElementById('password-strength-label');
            bar.style.width = strength.percent + '%';
            bar.style.backgroundColor = strength.color;
            label.textContent = strength.label;
            label.style.backgroundColor = strength.color;
        }
        function calculatePasswordStrength(password) {
            var strength = {percent: 0, color: 'red', label: 'Weak'};
            var length = password.length;

            if (length >= 8 && length <= 16) {
                var regexes = [
                    /[A-Z]/, // Uppercase letter
                    /[a-z]/, // Lowercase letter
                    /\d/,    // Number
                    /[!@#$%^&*]/ // Special character
                ];
                var passed = 0;
                for (var i = 0; i < regexes.length; i++) {
                    if (regexes[i].test(password)) {
                        passed++;
                    }
                }
                if (passed === 4) {
                    strength.percent = 100;
                    strength.color = 'green';
                    strength.label = 'Strong';
                } else if (passed >= 3) {
                    strength.percent = 75;
                    strength.color = 'orange';
                    strength.label = 'Moderate';
                } else if (passed >= 2) {
                    strength.percent = 50;
                    strength.color = 'orange';
                    strength.label = 'Moderate';
                } else if (passed >= 1) {
                    strength.percent = 25;
                    strength.color = 'red';
                    strength.label = 'Weak';
                } else {
                    strength.percent = 0;
                    strength.color = 'red';
                    strength.label = 'Weak';
                }
            } else {
                strength.label = 'Weak';
            }
            return strength;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Forgot Password</h1>
        </div>

        <form id="forgot-password-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <?php if (!isset($_SESSION["email_verified"])) : ?>
                <div id="email-group" class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <span class="error-message"><?php echo $email_err; ?></span>
                </div>
                <div class="buttons">
                    <button type="submit" name="verify_email">Verify Email</button>
                </div>
            <?php elseif (!isset($_SESSION["otp_verified"])) : ?>
                <div id="otp-group" class="form-group">
                    <label for="auth_code">Authentication Code:</label>
                    <input type="text" id="auth_code" name="auth_code" maxlength="6" pattern="\d{6}" required>
                    <span class="error-message"><?php echo $otp_err; ?></span>
                </div>
                <div class="buttons">
                    <button type="submit" name="verify_otp">Verify OTP</button>
                </div>
            <?php else : ?>
                <div id="password-reset-group" class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" oninput="updatePasswordStrengthBar(this.value)" required>
                    <div class="password-strength-label" id="password-strength-label">Weak</div>
                    <div class="password-strength-bar">
                        <div id="password-strength-bar-inner"></div>
                    </div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="error-message"><?php echo $password_err; ?></span>
                </div>
                <div class="buttons">
                    <button type="submit" name="reset_password">Reset Password</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
