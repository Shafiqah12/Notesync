<?php
// login.php
// This file handles user login and session management.

// Start a PHP session to manage user login state.
session_start();

// Check if the user is already logged in. If so, redirect them to their respective dashboard.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if ($_SESSION["user_role"] === "admin") {
        header("location: admin/dashboard.php");
    } else {
        header("location: dashboard.php");
    }
    exit; // Stop further script execution.
}

// Include the database connection file.
require_once 'includes/db_connect.php';

// Initialize variables to store error messages.
$email_username_err = $password_err = "";

// Check if the form has been submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validate Email/Username
    // Check if email or username field is empty.
    if (empty(trim($_POST["email_username"]))) {
        $email_username_err = "Please enter your email or username.";
    }

    // 2. Validate Password
    // Check if password field is empty.
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    }

    // If there are no input errors, attempt to authenticate the user.
    if (empty($email_username_err) && empty($password_err)) {
        $email_username = trim($_POST["email_username"]);
        $password = trim($_POST["password"]);

        // Prepare a SELECT statement to retrieve user data by email OR username.
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters (s = string).
            $stmt->bind_param("ss", $param_email_username, $param_email_username);

            // Set parameters.
            $param_email_username = $email_username;

            // Attempt to execute the prepared statement.
            if ($stmt->execute()) {
                // Store result.
                $stmt->store_result();

                // Check if a user with the given email/username exists.
                if ($stmt->num_rows == 1) {
                    // Bind result variables.
                    $stmt->bind_result($id, $username, $email, $hashed_password, $role);

                    // Fetch the result.
                    if ($stmt->fetch()) {
                        // Verify the password using password_verify().
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session.
                            session_start();

                            // Store data in session variables.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["user_email"] = $email;
                            $_SESSION["user_role"] = $role; // Store the user's role

                            // Redirect user to their respective dashboard based on role.
                            if ($role === "admin") {
                                header("location: admin/dashboard.php");
                            } else {
                                header("location: dashboard.php");
                            }
                            exit; // Stop further script execution.
                        } else {
                            // Password is not valid.
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // No user found with that email/username.
                    $email_username_err = "No account found with that username or email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement.
            $stmt->close();
        }
    }

    // Close connection.
    $conn->close();
}
?>

<?php
// Include the header file.
require_once 'includes/header.php';
?>

<div class="auth-container">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($email_username_err)) ? 'has-error' : ''; ?>">
            <label>Email or Username</label>
            <input type="text" name="email_username" class="form-control" value="<?php echo isset($_POST['email_username']) ? htmlspecialchars($_POST['email_username']) : ''; ?>">
            <span class="help-block"><?php echo $email_username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        <p><a href="forgot_password.php">Forgot your password?</a></p> <!-- Added Forgot Password link -->
        <!-- Optional: Google Sign-in button placeholder -->
        <div class="google-signin-btn">
            <p>Or sign in with Google:</p>
            <!-- You'll need to integrate Google Sign-In API here -->
            <button type="button" class="btn btn-google">Sign in with Google</button>
        </div>
    </form>
</div>

<?php
// Include the footer file.
require_once 'includes/footer.php';
?>
