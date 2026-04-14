<?php
// logout.php - Logout shared untuk admin dan user
require_once 'config/koneksi.php';

// Hapus semua session data
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redirect ke login
redirect('index.php');
?>

