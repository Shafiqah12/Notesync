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

<h2>Manage Notes</h2>
<p>Here you can view, edit, or delete user accounts.</p>

<div class="user-list">
    <?php
    // Fetch users from the database
    $sql_users = "SELECT id, title, description, price,file_path,uploaded_by, created_at FROM notes ORDER BY created_at ASC";
    $result_users = $conn->query($sql_users);

    if ($result_users && $result_users->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Price</th><th>File path</th><th>Uploaded by</th><th>Created At</th></tr></thead>";
        echo "<tbody>";
        while ($notes = $result_users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($notes['id']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['title']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['description']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['price']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['file_path']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['uploaded_by']) . "</td>";
            echo "<td>" . htmlspecialchars($notes['created_at']) . "</td>";
            echo "<td>";
            echo "<a href='edit-notes.php?id=" . $notes['id'] . "' class='btn btn-small btn-edit'>Edit</a> ";
            echo "<a href='delete-notes.php?id=" . $notes['id'] . "' class='btn btn-small btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
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