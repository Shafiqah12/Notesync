<?php
// buy-note.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db_connect.php';

echo "DEBUG: Script started. Session ID: " . session_id() . "<br>"; // DEBUG 1

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_role"] === "admin" || !isset($_SESSION["user_id"])) {
    echo "DEBUG: Access denied or user_id missing. Redirecting to login.<br>"; // DEBUG 2
    $_SESSION['purchase_status'] = "<div class='help-block'>You must be logged in to purchase notes.</div>";
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
echo "DEBUG: User ID: " . $user_id . "<br>"; // DEBUG 3
$purchase_status_message = '';

if (isset($_GET['note_id']) && !empty(trim($_GET['note_id']))) {
    $note_id = trim($_GET['note_id']);
    echo "DEBUG: Note ID from GET: " . $note_id . "<br>"; // DEBUG 4

    if (!filter_var($note_id, FILTER_VALIDATE_INT)) {
        echo "DEBUG: Invalid note ID. Redirecting to dashboard.<br>"; // DEBUG 5
        $_SESSION['purchase_status'] = "<div class='help-block'>Invalid note ID provided.</div>";
        header("location: dashboard.php");
        exit;
    }

    // Check if the note actually exists
    $sql_check_note = "SELECT id FROM notes WHERE id = ?";
    if ($stmt_check = $conn->prepare($sql_check_note)) {
        $stmt_check->bind_param("i", $note_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows == 0) {
            echo "DEBUG: Note not found in DB. Redirecting to dashboard.<br>"; // DEBUG 6
            $_SESSION['purchase_status'] = "<div class='help-block'>Note not found.</div>";
            header("location: dashboard.php");
            exit;
        }
        $stmt_check->close();
    } else {
        echo "DEBUG: DB error checking note existence. Redirecting.<br>"; // DEBUG 7
        $_SESSION['purchase_status'] = "<div class='help-block'>Database error checking note existence.</div>";
        header("location: dashboard.php");
        exit;
    }

    // Check if user has already purchased this note
    $sql_check_purchase = "SELECT id FROM purchases WHERE user_id = ? AND note_id = ?";
    if ($stmt_check_purchase = $conn->prepare($sql_check_purchase)) {
        $stmt_check_purchase->bind_param("ii", $user_id, $note_id);
        $stmt_check_purchase->execute();
        $stmt_check_purchase->store_result();
        if ($stmt_check_purchase->num_rows > 0) {
            echo "DEBUG: Note already purchased. Redirecting to dashboard.<br>"; // DEBUG 8
            $_SESSION['purchase_status'] = "<div class='help-block'>You have already purchased this note.</div>";
            header("location: dashboard.php");
            exit;
        }
        $stmt_check_purchase->close();
    } else {
        echo "DEBUG: DB error checking previous purchase. Redirecting.<br>"; // DEBUG 9
        $_SESSION['purchase_status'] = "<div class='help-block'>Database error checking previous purchase.</div>";
        header("location: dashboard.php");
        exit;
    }

    // Insert the purchase record
    $sql_insert_purchase = "INSERT INTO purchases (user_id, note_id) VALUES (?, ?)";
    if ($stmt_insert = $conn->prepare($sql_insert_purchase)) {
        $stmt_insert->bind_param("ii", $user_id, $note_id);

        echo "DEBUG: Attempting to execute insert statement.<br>"; // DEBUG 10
        if ($stmt_insert->execute()) {
            echo "DEBUG: Insert successful! Redirecting to my-notes.php.<br>"; // DEBUG 11
            $_SESSION['purchase_status'] = "<div class='success-message'>Note purchased successfully!</div>";
            header("location: mynotes.php");
            exit;
        } else {
            echo "DEBUG: Error purchasing note: " . $stmt_insert->error . "<br>"; // DEBUG 12
            $_SESSION['purchase_status'] = "<div class='help-block'>Error purchasing note: " . $stmt_insert->error . "</div>";
            header("location: dashboard.php");
            exit;
        }
        $stmt_insert->close();
    } else {
        echo "DEBUG: Database error preparing purchase statement. Redirecting.<br>"; // DEBUG 13
        $_SESSION['purchase_status'] = "<div class='help-block'>Database error preparing purchase.</div>";
        header("location: dashboard.php");
        exit;
    }
} else {
    echo "DEBUG: No note ID provided. Redirecting to dashboard.<br>"; // DEBUG 14
    $_SESSION['purchase_status'] = "<div class='help-block'>No note ID provided for purchase.</div>";
    header("location: dashboard.php");
    exit;
}

$conn->close();
echo "DEBUG: Script finished without explicit redirect (shouldn't happen).<br>"; // DEBUG 15
?>