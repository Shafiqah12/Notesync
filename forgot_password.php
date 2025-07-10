<?php
// forgot_password.php
// This file handles the request to reset a user's password.

session_start();

require_once 'includes/db_connect.php'; // Include your database connection

$email_err = "";
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);

        // Check if email exists in the database
        $sql = "SELECT id, username FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    // Email exists, generate a unique token and send email
                    $stmt->bind_result($user_id, $username);
                    $stmt->fetch();

                    // Generate a unique token (e.g., a random string)
                    $token = bin2hex(random_bytes(32)); // 64 character hex string
                    $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

                    // In a real application, you would store this token and its expiry
                    // in a dedicated 'password_resets' table in your database.
                    // Example table structure:
                    // CREATE TABLE password_resets (
                    //     id INT AUTO_INCREMENT PRIMARY KEY,
                    //     user_id INT NOT NULL,
                    //     token VARCHAR(255) NOT NULL UNIQUE,
                    //     expires_at DATETIME NOT NULL,
                    //     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    // );
                    //
                    // For now, we'll just generate it for the link.
                    //
                    // Example of how you would insert it (uncomment and adapt if you create the table):
                    // $insert_sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)";
                    // if ($insert_stmt = $conn->prepare($insert_sql)) {
                    //     $insert_stmt->bind_param("iss", $user_id, $token, $expires);
                    //     $insert_stmt->execute();
                    //     $insert_stmt->close();
                    // } else {
                    //     $error_message = "Error preparing token insert statement.";
                    // }


                    // Construct the reset link
                    // IMPORTANT: Replace 'http://localhost/NOTECYNC/' with your actual base URL
                    $reset_link = "http://localhost/NOTECYNC/reset_password.php?token=" . $token . "&email=" . urlencode($email);

                    // Email content
                    $subject = "NoteSync Password Reset Request";
                    $message = "Hi " . htmlspecialchars($username) . ",\n\n";
                    $message .= "You have requested to reset your password for your NoteSync account.\n";
                    $message .= "Please click on the following link to reset your password:\n";
                    $message .= $reset_link . "\n\n";
                    $message .= "This link will expire in 1 hour.\n";
                    $message .= "If you did not request a password reset, please ignore this email.\n\n";
                    $message .= "Thanks,\nNoteSync Team";

                    // Headers for the email
                    $headers = "From: no-reply@notesync.com\r\n";
                    $headers .= "Reply-To: no-reply@notesync.com\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion();

                    // Send the email
                    // NOTE: The mail() function requires proper SMTP configuration on your server (e.g., XAMPP/WAMP)
                    // to actually send emails. Without it, this function will run but no email will be delivered.
                    if (mail($email, $subject, $message, $headers)) {
                        $success_message = "If your email address is registered with us, a password reset link has been sent to your email.";
                    } else {
                        $error_message = "Failed to send password reset email. Please try again later. (Check your mail server configuration)";
                    }

                } else {
                    // Email does not exist, but provide a generic message for security
                    $success_message = "If your email address is registered with us, a password reset link has been sent to your email.";
                }
            } else {
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="auth-container">
    <h2>Forgot Password</h2>
    <p>Please enter your email address to receive a password reset link.</p>

    <?php
    if (!empty($success_message)) {
        echo '<div class="success-message">' . $success_message . '</div>';
    }
    if (!empty($error_message)) {
        echo '<div class="help-block">' . $error_message . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send Reset Link">
        </div>
        <p>Remember your password? <a href="login.php">Login here</a>.</p>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
