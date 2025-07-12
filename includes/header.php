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
            <a href="/NOTESYNC/index.php">NoteSync</a>
        </div>

        <ul class="nav-links">
            <?php if (!empty($_SESSION['loggedin'])): ?>
                <li><a href="/NOTESYNC/dashboard.php">Dashboard</a></li>
                <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="/NOTESYNC/admin/dashboard.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="/NOTESYNC/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/NOTESYNC/login.php">Login</a></li>
                <li><a href="/NOTESYNC/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
