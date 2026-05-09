<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Fetch all users except the current one to list them
$stmt = $pdo->query("SELECT username FROM account");
$all_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>SocialNet - Home</title>
</head>
<body>
    <?php include('../includes/menubar.php'); ?>

    <div class="container">
        <h1>SocialNet Server is LIVE</h1>
        <p>User: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        
        <hr style="border: 0.5px solid #444; margin: 20px 0;">
        
        <h3>Other Users</h3>
        <ul style="list-style: none; padding: 0; text-align: left;">
            <?php foreach ($all_users as $u): ?>
                <li style="margin: 10px 0; padding: 10px; background: #2d2d2d; border-radius: 6px;">
                    <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>" style="color: #007bff; text-decoration: none;">
                        View <?php echo htmlspecialchars($u['username']); ?>'s Profile
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
