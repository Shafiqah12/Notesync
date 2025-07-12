<?php
// admin/process-user-edit.php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

require_once '../includes/db_connect.php';

$user_id = $_POST['user_id'] ?? null;
$new_email = '';
$new_role = '';
$email_err = '';
$profile_picture_err = '';
$upload_success = false;

if ($user_id === null) {
    $_SESSION['error_message'] = "User ID not provided for editing.";
    header("location: manage-users.php");
    exit;
}

// Path to store uploaded profile pictures relative to the NOTESYNC root
$target_dir_relative = '/NOTESYNC/uploads/profile_pictures/';
// Absolute path for file system operations
$target_dir_absolute = $_SERVER['DOCUMENT_ROOT'] . $target_dir_relative;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Handle Email Update ---
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter the user's email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $new_email = trim($_POST["email"]);
        // Check if email is already taken by another user (excluding the current user being edited)
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $new_email, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $email_err = "This email is already taken by another user.";
            }
            $stmt->close();
        } else {
            error_log("ADMIN PROCESS USER EDIT: Error preparing email check query: " . $conn->error);
        }
    }

    // --- Handle Role Update ---
    $new_role = trim($_POST["role"]);
    if (!in_array($new_role, ['user', 'admin'])) {
        $new_role = 'user'; // Default to user if invalid role provided
    }

    // --- Handle Profile Picture Upload ---
    $current_profile_picture = null;

    // Fetch current profile picture path from DB for the user being edited
    $sql_fetch_pic = "SELECT profile_picture FROM users WHERE id = ?";
    if ($stmt_fetch_pic = $conn->prepare($sql_fetch_pic)) {
        $stmt_fetch_pic->bind_param("i", $user_id);
        if ($stmt_fetch_pic->execute()) {
            $stmt_fetch_pic->bind_result($current_profile_picture);
            $stmt_fetch_pic->fetch();
        }
        $stmt_fetch_pic->close();
    }

    $new_profile_picture_path = null;
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == UPLOAD_ERR_OK) {
        $file_name = basename($_FILES["profile_picture"]["name"]);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_filename = uniqid('profile_', true) . '.' . $file_type;
        $target_file = $target_dir_absolute . $unique_filename;

        if (!is_dir($target_dir_absolute)) {
            mkdir($target_dir_absolute, 0777, true);
        }

        if ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
            $profile_picture_err = "Sorry, the file is too large. Max 2MB.";
        }

        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($file_type, $allowed_types)) {
            $profile_picture_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (empty($profile_picture_err)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $new_profile_picture_path = $target_dir_relative . $unique_filename;
                $upload_success = true;

                // Delete old profile picture if it exists and is not a default image
                if ($current_profile_picture && file_exists($_SERVER['DOCUMENT_ROOT'] . $current_profile_picture) &&
                    strpos($current_profile_picture, '/NOTESYNC/img/') === false) { // Prevents deleting default images
                    unlink($_SERVER['DOCUMENT_ROOT'] . $current_profile_picture);
                }
            } else {
                $profile_picture_err = "Sorry, there was an error uploading the file.";
                error_log("ADMIN USER PROFILE UPLOAD: Error moving uploaded file for user " . $user_id . ": " . $_FILES["profile_picture"]["error"]);
            }
        }
    } else if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $profile_picture_err = "File upload error: " . $_FILES["profile_picture"]["error"];
        error_log("ADMIN USER PROFILE UPLOAD: PHP File Upload Error for user " . $user_id . ": " . $_FILES["profile_picture"]["error"]);
    }

    // --- Update Database ---
    if (empty($email_err) && empty($profile_picture_err)) {
        $update_fields = [];
        $bind_types = "";
        $bind_params = [];

        $update_fields[] = "email = ?";
        $bind_types .= "s";
        $bind_params[] = $new_email;

        $update_fields[] = "role = ?";
        $bind_types .= "s";
        $bind_params[] = $new_role;

        if ($upload_success) {
            $update_fields[] = "profile_picture = ?";
            $bind_types .= "s";
            $bind_params[] = $new_profile_picture_path;
        }

        $sql_update = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $bind_types .= "i";
        $bind_params[] = $user_id;

        if ($stmt = $conn->prepare($sql_update)) {
            $stmt->bind_param($bind_types, ...$bind_params);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "User details updated successfully.";
                header("location: manage-users.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Something went wrong updating user details. Please try again later.";
                error_log("ADMIN PROCESS USER EDIT: Error executing update query for user " . $user_id . ": " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("ADMIN PROCESS USER EDIT: Error preparing update query for user " . $user_id . ": " . $conn->error);
        }
    }
}
$conn->close();

// If there were errors, redirect back to the edit-user.php page
$redirect_url = "edit-user.php?id=" . $user_id;
$params = [];
if (!empty($email_err)) $params[] = "email_err=" . urlencode($email_err);
if (!empty($profile_picture_err)) $params[] = "profile_picture_err=" . urlencode($profile_picture_err);

if (!empty($params)) {
    $redirect_url .= "&" . implode("&", $params);
}
header("location: " . $redirect_url);
exit;
?>