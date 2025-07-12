<?php
// admin/process-profile-edit.php
// This file handles the submission of the admin profile edit form.

session_start();

// Include database connection
require_once '../includes/db_connect.php'; // Adjust path as needed

// Check if the user is NOT logged in or NOT an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] !== "admin")) {
    header("location: ../login.php");
    exit;
}

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user ID from the session (crucial for updating the correct record)
    $user_id = $_SESSION["user_id"];

    // Initialize variables to hold form data
    $email = trim($_POST["email"]); // Get email from the form

    // Basic validation (you should add more robust validation)
    if (empty($email)) {
        // Handle error: Email cannot be empty
        $_SESSION['error_message'] = "Email cannot be empty.";
        header("location: edit-admin-profile.php"); // Redirect back to edit page
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle error: Invalid email format
        $_SESSION['error_message'] = "Invalid email format.";
        header("location: edit-admin-profile.php");
        exit();
    }

    // Prepare an UPDATE statement
    // Assuming 'users' table and 'id' and 'email' columns
    $sql = "UPDATE users SET email = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: "si" for string (email) and integer (user_id)
        $stmt->bind_param("si", $param_email, $param_id);

        // Set parameters
        $param_email = $email;
        $param_id = $user_id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Update successful, optionally update session email as well
            // $_SESSION["email"] = $email; // Uncomment if you store email in session

            // Set success message
            $_SESSION['success_message'] = "Profile updated successfully.";
            header("location: admin-profile.php"); // Redirect back to admin profile page
            exit();
        } else {
            // Handle execution error
            $_SESSION['error_message'] = "Error updating profile: " . $stmt->error;
            header("location: edit-admin-profile.php");
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        // Handle prepare error
        $_SESSION['error_message'] = "Database error: Could not prepare statement.";
        header("location: edit-admin-profile.php");
        exit();
    }

    // Close connection
    $conn->close();

} else {
    // If accessed directly without POST request, redirect
    header("location: admin-profile.php");
    exit;
}
?>