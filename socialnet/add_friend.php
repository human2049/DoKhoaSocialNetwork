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
    // 1. Check if a relationship (pending or accepted) already exists
    $check_stmt = $pdo->prepare("SELECT id FROM friendships WHERE (user_id1 = :u1 AND user_id2 = :u2) OR (user_id1 = :u3 AND user_id2 = :u4)");
    $check_stmt->execute([
        'u1' => $current_user, 'u2' => $target_user,
        'u3' => $target_user,  'u4' => $current_user
    ]);

    // 2. Only insert if no existing record was found
    if (!$check_stmt->fetch()) {
        $insert_stmt = $pdo->prepare("INSERT INTO friendships (user_id1, user_id2, status) VALUES (:i1, :i2, 'pending')");
        $insert_stmt->execute(['i1' => $current_user, 'i2' => $target_user]);
    }
}

// Redirect back
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : "profile.php?owner=" . urlencode($target_username);
header("Location: " . $redirect);
exit();
?>
