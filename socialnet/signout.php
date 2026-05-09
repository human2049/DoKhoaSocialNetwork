<?php
// 1. Start the session so we can access it
session_start();

// 2. Unset all session variables
$_SESSION = array();

// 3. Destroy the actual session on the server
session_destroy();

// 4. Redirect the user back to the Sign-In page
header("Location: signin.php");
exit();
?>
