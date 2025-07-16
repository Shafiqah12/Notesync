<?php
// google-auth.php
// Initiates the Google OAuth 2.0 login process

session_start();

require_once __DIR__ . '/vendor/autoload.php'; // Path to Composer's autoloader

// Your Google API Client ID and Client Secret
define('GOOGLE_CLIENT_ID', '633285870360-92c5p6j3p321dh2u0ia6vr3nuuo1igh8.apps.googleusercontent.com'); // Replace with your Client ID
define('GOOGLE_CLIENT_SECRET', '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'); // Replace with your Client Secret
define('GOOGLE_REDIRECT_URI', 'https://7b6a5d48e60e.ngrok-free.app/NOTESYNC/google-callback.php');

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email'); // Request email scope
$client->addScope('profile'); // Request profile scope

// If the user has already granted access and we have an access token in session
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

// Redirect to Google's OAuth 2.0 server if no code is present
if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
}
// This part is mostly handled by google-callback.php, but for robustness
// If we reach here, it means we got a code but it wasn't handled in google-callback.php for some reason
header('Location: login.php?google_auth_failed=true'); // Redirect to login with error
exit;
?>