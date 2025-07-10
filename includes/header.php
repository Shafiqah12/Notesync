<?php
// header.php
// This file will contain the opening HTML tags, head section, and navigation bar.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteSync</title>
    <!-- Link your CSS files here -->
    <link rel="stylesheet" href="css/style.css">
    <!-- You might also include Bootstrap or Tailwind CSS here if you plan to use them -->
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="index.php">NoteSync</a>
            </div>
            <ul class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="admin/admin-profile.php">Admin Profile</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Main content will go here -->
