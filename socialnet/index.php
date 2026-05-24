<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];

// Fetch all users except the current one, making sure to grab their ID too
$stmt = $pdo->prepare("SELECT id, username FROM account WHERE id != :current_id");
$stmt->execute(['current_id' => $current_user_id]);
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
        <p>User: <strong><?php echo htmlspecialchars($current_username); ?></strong></p>
        
        <hr style="border: 0.5px solid #444; margin: 20px 0;">
        
        <h3>Other Users</h3>
        <ul style="list-style: none; padding: 0; text-align: left;">
            <?php foreach ($all_users as $u): 
                $target_id = $u['id'];
                
                // Check relationship status
                $status_stmt = $pdo->prepare("SELECT status, user_id1 FROM friendships WHERE (user_id1 = :u1 AND user_id2 = :u2) OR (user_id1 = :u3 AND user_id2 = :u4)");
                $status_stmt->execute([
                    'u1' => $current_user_id, 'u2' => $target_id,
                    'u3' => $target_id,       'u4' => $current_user_id
                ]);
                $relationship = $status_stmt->fetch();
            ?>
                <li style="margin: 10px 0; padding: 10px; background: #2d2d2d; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>" style="color: #007bff; text-decoration: none;">
                        View <?php echo htmlspecialchars($u['username']); ?>'s Profile
                    </a>
                    
                    <div>
                        <?php if (!$relationship): ?>
                            <form action="add_friend.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="target_id" value="<?php echo $target_id; ?>">
                                <input type="hidden" name="target_username" value="<?php echo htmlspecialchars($u['username']); ?>">
                                <input type="hidden" name="redirect" value="index.php">
                                <button type="submit" style="padding: 5px 10px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">Add Friend</button>
                            </form>
                        <?php elseif ($relationship['status'] === 'pending'): ?>
                            <?php if ($relationship['user_id1'] == $current_user_id): ?>
                                <span style="color: #ffc107; font-size: 0.9em;">Request Sent</span>
                            <?php else: ?>
                                <span style="color: #17a2b8; font-size: 0.9em;">Pending Request!</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: #28a745; font-size: 0.9em;">✓ Friends</span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            
            <?php if (empty($all_users)): ?>
                <li style="margin: 10px 0; padding: 10px; background: #2d2d2d; border-radius: 6px; color: #bbb;">
                    No other users found on the server.
                </li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
