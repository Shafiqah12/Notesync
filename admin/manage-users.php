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

<h2>Manage Users</h2>
<p>Here you can view, edit, or delete user accounts.</p>

<div class="user-list">
    <?php
    // Fetch users from the database
    $sql_users = "SELECT id, username, email, role FROM users ORDER BY username ASC";
    $result_users = $conn->query($sql_users);

    if ($result_users && $result_users->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>";
        echo "<tbody>";
        while ($user = $result_users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td>";
            echo "<a href='edit-user.php?id=" . $user['id'] . "' class='btn btn-small btn-edit'>Edit</a> ";
            echo "<a href='delete-user.php?id=" . $user['id'] . "' class='btn btn-small btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
    $conn->close();
    ?>
</div>

<?php require_once '../includes/footer.php'; ?>