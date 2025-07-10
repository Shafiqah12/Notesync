<?php include 'header.php'; ?>
  <h2>Welcome, <?= $_SESSION['user_name'] ?>! This is your Dashboard.</h2>
  <div class="cards">
    <div class="card">
      <p>Total Uploaded Subject Notes</p>
      <h3>3</h3>
      <a href="mynotes.php">View Detail</a>
    </div>
    <div class="card">
      <p>Total Uploaded Notes File</p>
      <h3>10</h3>
      <a href="mynotes.php">View Detail</a>
    </div>
  </div>
<?php include 'footer.php'; ?>
