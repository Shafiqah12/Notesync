<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NoteSync</title>

    <link rel="stylesheet" href="/NOTESYNC/css/style.css" />
    </head>
<body>
<header>
    <nav>
      <div class="logo">
    <a href="/NOTESYNC/index.php"> <img src="/NOTESYNC/img/notesyncs-logo.jpg" alt="NoteSync Logo" class="header-logo">
    </a>
</div>

    <ul class="nav-links">
        <li><a href="/NOTESYNC/dashboard.php">Dashboard</a></li>
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["user_role"] !== "admin"): ?>
            <li><a href="/NOTESYNC/mynotes.php">My Notes</a></li> <?php endif; ?>
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["user_role"] === "admin"): ?>
            <li><a href="/NOTESYNC/admin/admin-profile.php">Admin Profile</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <li><a href="/NOTESYNC/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="/NOTESYNC/login.php">Login</a></li>
            <li><a href="/NOTESYNC/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
    </nav>
</header>
<main>