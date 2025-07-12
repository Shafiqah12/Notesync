<?php
// dashboard.php
// This is the dashboard for regular users (students).
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a PHP session to access session variables.
session_start();

// Check if the user is NOT logged in, or if they are logged in but are an admin.
// If either condition is true, redirect them to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_role"] === "admin") {
    header("location: login.php");
    exit; // Stop further script execution.
}

// Include the header file for consistent site layout.
require_once 'includes/header.php';
?>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p>This is your user dashboard. Here you can browse and purchase notes.</p>
    <p>Your role: <?php echo htmlspecialchars($_SESSION["user_role"]); ?></p>

    <!-- Placeholder for user-specific content -->
    <div class="dashboard-content">
        <h3>Available Notes</h3>
        <p>List of notes will appear here for purchase.</p>
        <p>You can start adding logic to fetch notes from the database here.</p>
        <button class="btn btn-primary">Browse Notes</button>
    </div>
</div>

<?php
// Include the footer file for consistent site layout.
require_once 'includes/footer.php';
?>
