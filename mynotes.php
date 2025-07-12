<?php
// my-notes.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_role"] === "admin" || !isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$status_message = '';

// Check for and display any purchase status messages from buy-note.php
if (isset($_SESSION['purchase_status'])) {
    $status_message = $_SESSION['purchase_status'];
    unset($_SESSION['purchase_status']); // Clear the message after displaying
}

$purchased_notes = [];
if ($conn) {
    // Select notes that the current user has purchased
    $sql_fetch_purchased_notes = "SELECT n.id, n.title, n.description, n.price, n.file_path, u.username AS uploaded_by_username, n.created_at
                                  FROM notes n
                                  JOIN purchases p ON n.id = p.note_id
                                  JOIN users u ON n.uploaded_by = u.id
                                  WHERE p.user_id = ?
                                  ORDER BY n.created_at DESC";

    if ($stmt_purchased = $conn->prepare($sql_fetch_purchased_notes)) {
        $stmt_purchased->bind_param("i", $user_id);
        $stmt_purchased->execute();
        $result_purchased = $stmt_purchased->get_result();

        if ($result_purchased->num_rows > 0) {
            while ($row = $result_purchased->fetch_assoc()) {
                $purchased_notes[] = $row;
            }
        }
        $stmt_purchased->close();
    } else {
        error_log("Error preparing purchased notes fetch statement: " . $conn->error);
    }
} else {
    error_log("Database connection not established in my-notes.php");
}

require_once 'includes/header.php';
?>

<div class="dashboard-container">
    <h2>My Purchased Notes</h2>
    <?php echo $status_message; // Display purchase status message here ?>
    <p>Here are the notes you have purchased.</p>

    <div class="notes-grid">
        <?php if (!empty($purchased_notes)): ?>
            <?php foreach ($purchased_notes as $note): ?>
                <div class="note-card">
                    <h4><?php echo htmlspecialchars($note['title']); ?></h4>
                    <p class="description"><?php echo htmlspecialchars($note['description']); ?></p>
                    <p class="price">Price: RM<?php echo htmlspecialchars(number_format($note['price'], 2)); ?></p>
                    <p class="uploaded-info">
                        Uploaded by: <?php echo htmlspecialchars($note['uploaded_by_username']); ?> on: <?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($note['created_at']))); ?>
                    </p>
                    <?php
                    // Correct the file path for download
                    $correct_file_path = str_replace('../uploads/', '/NOTESYNC/uploads/', $note['file_path']);
                    ?>
                    <a href="<?php echo htmlspecialchars($correct_file_path); ?>" class="btn btn-primary" download>Download Note</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not purchased any notes yet. Browse the <a href="dashboard.php">dashboard</a> to find notes.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
require_once 'includes/footer.php';
?>