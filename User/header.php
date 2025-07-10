<?php
session_start();
$_SESSION['user_name'] = 'izatul';
$_SESSION['user_email'] = 'izatul@gmail.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NOTESYNC User Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <aside class="sidebar">
    <div class="logo"># NOTESYNC</div>
    <div class="user-profile">
      <img src="images/avatar (3).png" alt="User">
      <h3><?= $_SESSION['user_name'] ?></h3>
      <p><?= $_SESSION['user_email'] ?></p>
    </div>
    <ul class="menu">
      <li><a href="dashboard.php">📊 Dashboard</a></li>
      <li><a href="mynotes.php">📝 My Notes</a></li>
      <li><a href="upload.php">📤 Upload Notes</a></li>
      <li><a href="profile.php">👤 Profile</a></li>
    </ul>
  </aside>
  <main class="main">
