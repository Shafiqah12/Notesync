<?php
session_start();
require_once __DIR__.'/vendor/autoload.php';
require_once 'includes/db_connect.php';

define('GOOGLE_CLIENT_ID',     '633285870360-92c5p6j3p321dh2u0ia6vr3nuuo1igh8.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'https://7b6a5d48e60e.ngrok-free.app/NOTESYNC/google-callback.php');
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope(['email','profile']);

/* ---------- 1. ensure we have a code ---------- */
if (empty($_GET['code'])) {
    $_SESSION['error_message'] = 'Google auth failed.';
    header('Location: login.php'); exit;
}

/* ---------- 2. exchange code ---------- */
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    $_SESSION['error_message'] = 'Google token error: '.$token['error'];
    header('Location: login.php'); exit;
}
$client->setAccessToken($token);

/* ---------- 3. fetch user info ---------- */
$oauth2 = new Google_Service_Oauth2($client);
$gUser  = $oauth2->userinfo->get();

$email = $gUser->email;
$name  = $gUser->name;
$gid   = $gUser->id;
$pic   = $gUser->picture ?? null;

/* ---------- 4. upsert user ---------- */
$stmt = $conn->prepare("SELECT id, username, role, profile_picture FROM users WHERE email=? OR google_id=?");
$stmt->bind_param('ss',$email,$gid);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows) {                /* existing user */
    $u = $res->fetch_assoc();
    $id   = $u['id'];
    $role = $u['role'];

    /* update last_login */
    $upd = $conn->prepare("UPDATE users SET last_login=NOW(), profile_picture = IF(?, ?, profile_picture) WHERE id=?");
    $upd->bind_param('ssi',$pic,$pic,$id);
    $upd->execute();
} else {                              /* new user */
    $role = 'user';
    $pwd  = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    $ins  = $conn->prepare(
        "INSERT INTO users (username,email,password,google_id,role,profile_picture,last_login)
         VALUES (?,?,?,?,?,?,NOW())"
    );
    $ins->bind_param('ssssss',$name,$email,$pwd,$gid,$role,$pic);
    $ins->execute();
    $id = $ins->insert_id;
}

/* ---------- 5. set session and redirect ---------- */
session_regenerate_id(true);
$_SESSION['loggedin']        = true;
$_SESSION['user_id']         = $id;
$_SESSION['username']        = $name;
$_SESSION['user_email']      = $email;
$_SESSION['user_role']       = $role;
$_SESSION['profile_picture'] = $pic ?: '../img/admin.jpg';

header('Location: '.($role==='admin' ? 'admin/dashboard.php' : 'dashboard.php'));
exit;
