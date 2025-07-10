<?php include 'header.php'; ?>
  <h2>📤 Upload New Note</h2>
  <form method="post" enctype="multipart/form-data">
    <label>Subject:</label><br>
    <input type="text" name="subject" required><br><br>
    <label>Select File:</label><br>
    <input type="file" name="note" required><br><br>
    <button type="submit">Upload</button>
  </form>
<?php include 'footer.php'; ?>
