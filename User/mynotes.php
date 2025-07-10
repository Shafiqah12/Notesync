<?php include 'header.php'; ?>
<h2>📝 My Notes</h2>

<?php
$folder = "notes/";


if (isset($_GET['delete'])) {
  $fileToDelete = basename($_GET['delete']); 
  $path = $folder . $fileToDelete;

  if (file_exists($path)) {
    unlink($path);
    echo "<p style='color:green;'>✅ File <strong>$fileToDelete</strong> has been deleted.</p>";
  } else {
    echo "<p style='color:red;'>❌ File not found.</p>";
  }
}

$files = array_diff(scandir($folder), array('.', '..'));
?>

<ul style="background: #fff; padding: 20px; border-radius: 8px; list-style: none;">
<?php
if (count($files) > 0) {
  foreach ($files as $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $icon = "📄";
    if ($ext == 'pdf') $icon = "📕";
    elseif ($ext == 'docx') $icon = "📘";
    elseif ($ext == 'txt') $icon = "📄";

    echo "<li style='margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;'>
            <div>
              $icon <a href='$folder$file' target='_blank' style='color: #007bff;'>$file</a>
            </div>
            <div>
              <a href='mynotes.php?delete=$file' onclick=\"return confirm('Are you sure you want to delete this file?')\" style='color:red;'>🗑️ Delete</a>
            </div>
          </li>";
  }
} else {
  echo "<li>No notes uploaded yet.</li>";
}
?>
</ul>

<?php include 'footer.php'; ?>
