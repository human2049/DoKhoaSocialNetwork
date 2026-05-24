<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$current_user = $_SESSION['user_id'];
$target_user = isset($_POST['target_id']) ? intval($_POST['target_id']) : 0;
$target_username = isset($_POST['target_username']) ? $_POST['target_username'] : '';

if ($target_user > 0 && $current_user != $target_user) {
    // Insert friendship (simplified to auto-accept)
    $stmt = $pdo->prepare("INSERT INTO friendships (user_id1, user_id2, status) VALUES (:u1, :u2, 'accepted')");
    $stmt->execute(['u1' => $current_user, 'u2' => $target_user]);
}

// Redirect back to their profile using the username
header("Location: profile.php?owner=" . urlencode($target_username));
exit();
?>
