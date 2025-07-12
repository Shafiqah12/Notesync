<?php
// admin/edit-admin-profile.php
// This page allows administrators to edit their profile details.

session_start();

// Check if the user is NOT logged in, or if they are logged in but are NOT an admin.
// Redirect to login if not authorized.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php"); // Go up one level to find login.php
    exit;
}

// Include the header file for consistent site layout.
require_once '../includes/header.php'; // Go up one level to find the includes folder
?>

    <h2>Edit Admin Profile</h2>
    <p>Here you can update your profile information.</p>

    <div class="profile-edit-form">
        <form action="process-profile-edit.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" class="form-control" readonly>
                <small>Username cannot be changed.</small>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php // Fetch current email from DB or session ?>" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="admin-profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php
// Include the footer file.
require_once '../includes/footer.php'; // Go up one level to find the includes folder
?>