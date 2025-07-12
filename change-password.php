<?php
// change-password.php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /NOTESYNC/login.php");
    exit;
}

// Include the header file
require_once __DIR__ . '/includes/header.php';
?>

<div class="container content">
    <h2>Change Password</h2>
    <p>Please fill out this form to change your password.</p>

    <form action="/NOTESYNC/process-change-password.php" method="post">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-secondary" href="<?php echo (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin") ? '/NOTESYNC/admin/admin-profile.php' : '/NOTESYNC/profile.php'; ?>">Cancel</a>
        </div>
    </form>
</div>

<?php
// Include the footer file
require_once __DIR__ . '/includes/footer.php';
?>