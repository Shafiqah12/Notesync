<?php
// process-change-password.php
session_start();

// Check if the user is logged in. If not, redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /NOTESYNC/login.php");
    exit;
}

// Include your database connection file
require_once __DIR__ . '/includes/db_connect.php';

$user_id = $_SESSION["user_id"];
$current_password = $new_password = $confirm_password = "";
$current_password_err = $new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate current password
    if (empty(trim($_POST["current_password"]))) {
        $current_password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the new password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "New password and confirmation password do not match.";
        }
    }

    // Check input errors before updating the password
    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare a select statement to get the hashed password
        $sql = "SELECT password FROM users WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($hashed_password);
                    $stmt->fetch();

                    // Verify current password
                    if (password_verify($current_password, $hashed_password)) {
                        // Current password is correct, now update new password
                        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                        $sql_update = "UPDATE users SET password = ? WHERE id = ?";
                        if ($stmt_update = $conn->prepare($sql_update)) {
                            $stmt_update->bind_param("si", $new_hashed_password, $user_id);
                            if ($stmt_update->execute()) {
                                // Password updated successfully
                                $_SESSION['password_updated'] = true;
                                header("location: " . (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin" ? "/NOTESYNC/admin/admin-profile.php" : "/NOTESYNC/profile.php"));
                                exit;
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                                error_log("PROCESS CHANGE PASSWORD: Error executing password update: " . $stmt_update->error);
                            }
                            $stmt_update->close();
                        } else {
                            error_log("PROCESS CHANGE PASSWORD: Error preparing password update: " . $conn->error);
                        }
                    } else {
                        $current_password_err = "The password you entered was not valid.";
                    }
                } else {
                    // User not found, though logged in - should not happen
                    error_log("PROCESS CHANGE PASSWORD: User ID " . $user_id . " not found in database.");
                }
            } else {
                error_log("PROCESS CHANGE PASSWORD: Error executing current password fetch: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("PROCESS CHANGE PASSWORD: Error preparing current password fetch: " . $conn->error);
        }
    }
}
$conn->close();

// If there are errors, redirect back to the change password page with errors
$redirect_url = "/NOTESYNC/change-password.php";
$params = [];
if (!empty($current_password_err)) $params[] = "current_password_err=" . urlencode($current_password_err);
if (!empty($new_password_err)) $params[] = "new_password_err=" . urlencode($new_password_err);
if (!empty($confirm_password_err)) $params[] = "confirm_password_err=" . urlencode($confirm_password_err);

if (!empty($params)) {
    $redirect_url .= "?" . implode("&", $params);
}
header("location: " . $redirect_url);
exit;
?>