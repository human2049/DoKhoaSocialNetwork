<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// 1. Determine the owner (from URL or Session)
$owner_username = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];

// 2. Fetch that specific user's data
$stmt = $pdo->prepare("SELECT username, fullname, description FROM account WHERE username = :username");
$stmt->execute(['username' => $owner_username]);
$profile_owner = $stmt->fetch();

if (!$profile_owner) {
    die("User profile not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title><?php echo htmlspecialchars($profile_owner['username']); ?>'s Profile</title>
</head>
<body>
    <?php include('../includes/menubar.php'); ?>

    <div class="container">
        <h1><?php echo htmlspecialchars($profile_owner['username']); ?>'s Profile</h1>
        
        <div style="text-align: left; margin-top: 20px;">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($profile_owner['fullname']); ?></p>
            <p><strong>Description:</strong></p>
            <div style="background: #2d2d2d; padding: 15px; border-radius: 6px; border-left: 4px solid #007bff;">
                <?php echo nl2br(htmlspecialchars($profile_owner['description'])); ?>
            </div>
        </div>
    </div>
</body>
</html>
