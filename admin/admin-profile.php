<?php
// admin/admin-profile.php
// This is the profile page for administrators.

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

// Include your database connection file
// Make sure the path is correct: It goes up one directory (..)
// then into the 'includes' folder, and finds 'db_connect.php'.
require_once '../includes/db_connect.php'; // <--- THIS IS THE KEY LINE

require_once '../includes/header.php';

// Define a default profile picture path if none is set or found
$profilePicture = isset($_SESSION["profile_picture"]) && !empty($_SESSION["profile_picture"])
                  ? $_SESSION["profile_picture"]
                  : 'img/admin.jpg'; // Path from the base project directory (e.g., NOTESYNC/img/admin.jpg)

// Ensure the path is correct from the perspective of the browser
$basePath = '/NOTESYNC/';
$fullProfilePicturePath = $basePath . $profilePicture;

// Initialize variables for email and last login
$adminEmail = "N/A"; // Default value if not found
$lastLogin = "N/A"; // Default value if not found

// Fetch email and last login from the database
// We use $_SESSION["user_id"] to get the current logged-in user's data
if (isset($_SESSION["user_id"])) {
    // Prepare a SELECT statement to get email and last_login for the specific user
    // Make sure your table name is 'users' and columns are 'id', 'email', 'last_login'
    $sql = "SELECT email, last_login FROM users WHERE id = ?";

    // Using prepared statements for security (prevents SQL injection)
    if ($stmt = $conn->prepare($sql)) { // Use $conn (from db_connect.php) for preparing
        // Bind parameters: "i" for integer (user_id)
        $stmt->bind_param("i", $_SESSION["user_id"]);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Get the result set
            $stmt->store_result();

            // If a row is found (user exists)
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($email, $db_last_login);

                // Fetch the values
                if ($stmt->fetch()) {
                    $adminEmail = $email;
                    // Format last login if it's a timestamp or datetime string
                    $lastLogin = ($db_last_login !== null) ? date("Y-m-d H:i:s", strtotime($db_last_login)) : "Never";
                }
            }
        } else {
            echo "Oops! Something went wrong with the database query: " . $stmt->error;
        }
        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// It's good practice to close the database connection when done
$conn->close(); // <--- THIS IS THE KEY LINE TO CLOSE CONNECTION
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
        <p><strong>Email:</strong> <?php echo htmlspecialchars($adminEmail); ?></p>
        <p><strong>Last Login:</strong> <?php echo htmlspecialchars($lastLogin); ?></p>
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