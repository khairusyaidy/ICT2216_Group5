<?php
session_start();

if (!isset($_SESSION['temp_id']) || !isset($_SESSION['google_secret'])) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$auth_code = "";
$auth_code_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['otp_attempts']) && $_SESSION['otp_attempts'] >= 3) {
        $_SESSION['otp_attempts'] = 0;  // Reset the attempts
        echo '<script>alert("Invalid authentication code. Redirecting to login page."); window.location.href = "login.php";</script>';
        exit;
    } else {
        $auth_code = trim($_POST["auth_code"]);

        // Validate that the code is exactly 6 digits
        if (empty($auth_code)) {
            $auth_code_err = "Please enter the authentication code.";
        } elseif (!preg_match('/^\d{6}$/', $auth_code)) {
            $auth_code_err = "The authentication code must be exactly 6 digits.";
        } else {
            $g = new GoogleAuthenticator();
            $secret = $_SESSION['google_secret'];

            if ($g->checkCode($secret, $auth_code)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $_SESSION['temp_id'];
                $_SESSION["FirstName"] = $_SESSION['temp_first_name'];
                $_SESSION["LastName"] = $_SESSION['temp_last_name'];

                unset($_SESSION['temp_id']);
                unset($_SESSION['temp_first_name']);
                unset($_SESSION['temp_last_name']);
                unset($_SESSION['google_secret']);
                unset($_SESSION['otp_attempts']);

                header("Location: index.php");
                exit;
            } else {
                $auth_code_err = "Invalid authentication code.";
                if (!isset($_SESSION['otp_attempts'])) {
                    $_SESSION['otp_attempts'] = 0;
                }
                $_SESSION['otp_attempts'] += 1;

                if ($_SESSION['otp_attempts'] >= 3) {
                    echo '<script>alert("Invalid authentication code. Redirecting to login page."); window.location.href = "login.php";</script>';
                    exit;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - Fur Season Hotel</title>
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
        .error-message { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>2FA Verification</h1>
        </div>
        <form id="2fa-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="auth_code">Authentication Code:</label>
                <input type="text" id="auth_code" name="auth_code" maxlength="6" pattern="\d{6}" required>
                <span class="error-message"><?php echo $auth_code_err; ?></span>
            </div>
            <div class="buttons">
                <button type="submit">Verify</button>
            </div>
        </form>
    </div>
</body>
</html>
