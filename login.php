<?php
ob_start();
include_once "dbconntest.php";
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $email_err = "Please enter email.";
    }
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    if (empty($email_err) && empty($password_err)) {
        $sql_customer = "SELECT id, FirstName, LastName, Password, GoogleAuthenticatorSecret FROM customer WHERE Email = ?";
        if ($stmt_customer = $conn->prepare($sql_customer)) {
            $stmt_customer->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt_customer->execute()) {
                $stmt_customer->store_result();

                if ($stmt_customer->num_rows == 1) {
                    $stmt_customer->bind_result($id, $FirstName, $LastName, $hashed_password, $google_secret);
                    if ($stmt_customer->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            $_SESSION['temp_id'] = $id;
                            $_SESSION['temp_first_name'] = $FirstName;
                            $_SESSION['temp_last_name'] = $LastName;
                            $_SESSION['google_secret'] = $google_secret;
                            $_SESSION['otp_attempts'] = 0;
                            $_SESSION['Role'] = 'customer';

                            header("Location: 2fa_verify.php");
                            exit;
                        } else {
                            $login_err = "Invalid email/password entered.";
                        }
                    }
                } else {
                    $login_err = "Invalid email/password entered.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt_customer->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        $sql_staff = "SELECT id, FirstName, LastName, Password, Role FROM staff WHERE Email = ?";
        if ($stmt_staff = $conn->prepare($sql_staff)) {
            $stmt_staff->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt_staff->execute()) {
                $stmt_staff->store_result();

                if ($stmt_staff->num_rows == 1) {
                    $stmt_staff->bind_result($id, $FirstName, $LastName, $hashed_password, $role);
                    if ($stmt_staff->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["FirstName"] = $FirstName;
                            $_SESSION["LastName"] = $LastName;
                            $_SESSION["Role"] = $role;

                            if ($_SESSION["Role"] == 'admin') {
                                header("location: staffaccount.php");
                            } else {
                                header("location: staffhomepage.php");
                            }
                            exit;
                        } else {
                            $login_err = "Invalid email/password entered.";
                        }
                    }
                } else {
                    $login_err = "Invalid email/password entered.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt_staff->close();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
    <title>Log In - Fur Season Hotel</title>
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
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <div class="signup-link">
                <p>Don't have an account yet? <a href="signup.php">Sign Up Here</a></p>
            </div>
        </form>
        <?php if (!empty($login_err)) : ?>
            <p class="error-message"><?php echo $login_err; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
