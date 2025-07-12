<?php
// admin/dashboard.php
// This is the dashboard for administrators.

// Start a PHP session to access session variables.
session_start();

// Check if the user is NOT logged in, or if they are logged in but are NOT an admin.
// If either condition is true, redirect them to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_role"] !== "admin") {
    // If not logged in or not an admin, redirect to login page.
    header("location: ../login.php"); // Note the "../" to go up one directory level
    exit; // Stop further script execution.
}

// Include the header file for consistent site layout.
// Note the "../" to go up one directory level to find the includes folder.
require_once '../includes/header.php';
?>

    <h2>Welcome, Admin <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p>This is your administrator dashboard. Here you can manage notes and users.</p>
    <p>Your role: <strong><?php echo htmlspecialchars($_SESSION["user_role"]); ?></strong></p>

    <div class="dashboard-content">
        <h3>Admin Actions</h3>
        <div class="admin-actions-buttons">
            <a href="upload-note.php" class="btn btn-primary">Upload New Note</a>
            <a href="manage-existing-notes.php" class="btn btn-primary">Manage Existing Notes</a>
            <a href="manage-users.php" class="btn btn-primary">Manage Users</a>
        </div>
        <p>You can start adding logic for admin functionalities here.</p>
    </div>

<?php
// Include the footer file for consistent site layout.
// Note the "../" to go up one directory level to find the includes folder.
require_once '../includes/footer.php';
?>