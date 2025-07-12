<?php
// admin/manage-users.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/db_connect.php'; // Path relative to manage-users.php

// Access control: Ensure only logged-in admins can access
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_role"] !== "admin") {
    header("location: ../login.php");
    exit;
}

require_once '../includes/header.php'; // Path relative to manage-users.php
?>

<div class="container mt-4">
    <h2>Manage Users</h2>
    <p>Here you can view, edit, or delete user accounts.</p>

    <div class="table-responsive">
        <?php
        // Fetch users from the database using prepared statements for better security
        $sql_users = "SELECT id, username, email, role FROM users ORDER BY username ASC";
        if ($stmt = $conn->prepare($sql_users)) {
            $stmt->execute();
            $result_users = $stmt->get_result();

            if ($result_users && $result_users->num_rows > 0) {
                // CHANGE THIS LINE: Apply the 'notes-table' class
                echo "<table class='notes-table'>";
                echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($user = $result_users->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                    echo "<td>";
                    // Apply htmlspecialchars to IDs in URLs too
                    // Use 'btn-info' and 'btn-danger' as you have styles for them in the combined CSS
                    echo "<a href='edit-user.php?id=" . htmlspecialchars($user['id']) . "' class='btn btn-info'>Edit</a> ";
                    echo "<a href='delete-user.php?id=" . htmlspecialchars($user['id']) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No users found.</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='alert alert-danger'>Error preparing statement: " . $conn->error . "</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>