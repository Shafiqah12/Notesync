<?php
// reset_password.php
// This file handles the password reset process.

session_start();

require_once 'includes/db_connect.php'; // Include your database connection

$token = $email = "";
$password_err = $confirm_password_err = "";
$success_message = "";
$error_message = "";

// Check if token and email are present in the URL
if (isset($_GET["token"]) && isset($_GET["email"])) {
    $token = $_GET["token"];
    $email = $_GET["email"];

    // In a real application, you would verify this token against your 'password_resets' table:
    // 1. Check if the token exists for the given email/user_id.
    // 2. Check if the token has not expired.
    // 3. Check if the token has not been used already.
    // If any of these checks fail, display an error and do not show the password reset form.

    // For this example, we'll assume the token is valid for demonstration purposes.
    // However, for security, you MUST implement proper token validation against a database.

} else {
    // If token or email are missing, redirect to forgot password page or show error
    header("location: forgot_password.php");
    exit;
}

// Process password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    }

    // Validate Confirm Password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm new password.";
    } else {
        if (trim($_POST["password"]) != trim($_POST["confirm_password"])) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // If no errors, update the password
    if (empty($password_err) && empty($confirm_password_err)) {
        $new_password = trim($_POST["password"]);
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user's password in the database
        $sql = "UPDATE users SET password = ? WHERE email = ?"; // Using email for simplicity, but user_id is safer with tokens
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                // Password updated successfully
                $success_message = "Your password has been reset successfully. You can now log in with your new password.";

                // In a real application, you would also invalidate/delete the token from the 'password_resets' table here.
                // Example:
                // $delete_sql = "DELETE FROM password_resets WHERE token = ?";
                // if ($delete_stmt = $conn->prepare($delete_sql)) {
                //     $delete_stmt->bind_param("s", $token);
                //     $delete_stmt->execute();
                //     $delete_stmt->close();
                // }

                // Redirect to login page after a short delay or display success message
                header("Refresh: 3; url=login.php"); // Redirect after 3 seconds
            } else {
                $error_message = "Error updating password. Please try again.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="auth-container">
    <h2>Reset Password</h2>
    <?php
    if (!empty($success_message)) {
        echo '<div class="success-message">' . $success_message . '</div>';
    }
    if (!empty($error_message)) {
        echo '<div class="help-block">' . $error_message . '</div>';
    }
    ?>

    <?php if (empty($success_message)): // Only show form if password hasn't been reset successfully ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=" . urlencode($token) . "&email=" . urlencode($email); ?>" method="post">
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>New Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Reset Password">
        </div>
    </form>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
