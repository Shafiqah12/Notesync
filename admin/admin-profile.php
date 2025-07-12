<?php
// admin/admin-profile.php
// This is the profile page for administrators.

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

require_once '../includes/header.php';

// Define a default profile picture path if none is set or found
// CORRECTED: Path should be relative to the $basePath
$profilePicture = isset($_SESSION["profile_picture"]) && !empty($_SESSION["profile_picture"])
                  ? $_SESSION["profile_picture"]
                  : 'img/admin.jpg'; // Path from the base project directory (e.g., NOTESYNC/img/admin.jpg)

// Ensure the path is correct from the perspective of the browser
// This assumes your project is directly under /NOTESYNC/ in your web root
$basePath = '/NOTESYNC/';
$fullProfilePicturePath = $basePath . $profilePicture;
?>

    <h2>Admin Profile</h2>

    <div class="profile-header" style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo htmlspecialchars($fullProfilePicturePath); ?>" alt="Admin Profile Picture" class="profile-avatar">
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>!</p>
        <p>Your Role: <strong><?php echo htmlspecialchars($_SESSION["user_role"]); ?></strong></p>
    </div>

    <div class="profile-details">
        <h3>Profile Information</h3>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
        <p><strong>Email:</strong> <?php // Fetch and display email from DB or session if stored ?></p>
        <p><strong>Last Login:</strong> <?php // Fetch and display last login timestamp from DB or session if stored ?></p>
    </div>

    <div class="profile-actions">
        <h3>Manage Profile</h3>
        <a href="edit-admin-profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="change-password.php" class="btn btn-primary">Change Password</a>
    </div>

    <p style="margin-top: 30px;">This section allows administrators to view and manage their profile details.</p>

<?php
require_once '../includes/footer.php';
?>