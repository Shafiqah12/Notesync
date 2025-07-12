<?php
// admin/edit-user.php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

require_once '../includes/db_connect.php';

$user_id = $_GET['id'] ?? null; // Get the user ID from the URL parameter
$username = '';
$email = '';
$role = ''; // To display and potentially edit user's role
$profile_picture_path = '/NOTESYNC/img/user.jpg'; // Default user avatar

if ($user_id === null) {
    // If no ID is provided, redirect to manage users page
    header("location: manage-users.php");
    exit;
}

// Fetch user's current details from the database
$sql = "SELECT username, email, role, profile_picture FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($db_username, $db_email, $db_role, $db_profile_picture);
            $stmt->fetch();
            $username = $db_username;
            $email = $db_email;
            $role = $db_role;
            if (!empty($db_profile_picture)) {
                $profile_picture_path = htmlspecialchars($db_profile_picture);
            }
        } else {
            // User not found
            $_SESSION['error_message'] = "User not found.";
            header("location: manage-users.php");
            exit;
        }
    }
    $stmt->close();
} else {
    error_log("ADMIN EDIT USER: Error preparing select query: " . $conn->error);
}
$conn->close();

require_once '../includes/header.php';
?>

<div class="container content">
    <h2>Edit User: <?php echo htmlspecialchars($username); ?></h2>
    <p>Here you can update this user's profile information, role, and picture.</p>

    <form action="process-user-edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

        <div class="profile-avatar-section" style="text-align: center; margin-bottom: 20px;">
            <img src="<?php echo $profile_picture_path; ?>" alt="User Profile Picture" class="profile-avatar-large">
            <p>Current Profile Picture</p>
        </div>

        <div class="form-group">
            <label for="profile_picture">Upload New Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="form-control-file">
            <small class="form-text text-muted">Max file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF.</small>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control" readonly>
            <small>Username cannot be changed directly here. If needed, you'd implement a separate logic.</small>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control">
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" class="form-control">
                <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="manage-users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>