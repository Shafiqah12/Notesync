<?php
// admin/delete-user.php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

require_once '../includes/db_connect.php';

// Get the user ID from the URL parameter
$user_id = $_GET['id'] ?? null;

if ($user_id === null) {
    $_SESSION['error_message'] = "No user ID specified for deletion.";
    header("location: manage-users.php");
    exit;
}

// Prevent admin from deleting their own account (optional but recommended)
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You cannot delete your own admin account.";
    header("location: manage-users.php");
    exit;
}

// --- Delete User's Profile Picture (if exists) ---
$profile_picture_to_delete = null;
$sql_fetch_pic = "SELECT profile_picture FROM users WHERE id = ?";
if ($stmt_fetch_pic = $conn->prepare($sql_fetch_pic)) {
    $stmt_fetch_pic->bind_param("i", $user_id);
    if ($stmt_fetch_pic->execute()) {
        $stmt_fetch_pic->bind_result($profile_picture_to_delete);
        $stmt_fetch_pic->fetch();
    }
    $stmt_fetch_pic->close();
}

// Delete user from the database
$sql_delete_user = "DELETE FROM users WHERE id = ?";
if ($stmt_delete = $conn->prepare($sql_delete_user)) {
    $stmt_delete->bind_param("i", $user_id);
    if ($stmt_delete->execute()) {
        // If user deleted from DB, delete their profile picture file
        if ($profile_picture_to_delete && file_exists($_SERVER['DOCUMENT_ROOT'] . $profile_picture_to_delete) &&
            strpos($profile_picture_to_delete, '/NOTESYNC/img/') === false) { // Don't delete default images
            unlink($_SERVER['DOCUMENT_ROOT'] . $profile_picture_to_delete);
        }

        $_SESSION['success_message'] = "User deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting user: " . $stmt_delete->error;
        error_log("ADMIN DELETE USER: Error executing delete query for user " . $user_id . ": " . $stmt_delete->error);
    }
    $stmt_delete->close();
} else {
    $_SESSION['error_message'] = "Error preparing delete user query: " . $conn->error;
    error_log("ADMIN DELETE USER: Error preparing delete query: " . $conn->error);
}

$conn->close();
header("location: manage-users.php");
exit;
?>