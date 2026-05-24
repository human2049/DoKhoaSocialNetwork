<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// Handle Accepting a Friend Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'accept') {
    $friendship_id = intval($_POST['friendship_id']);
    $update_stmt = $pdo->prepare("UPDATE friendships SET status = 'accepted' WHERE id = :f_id AND user_id2 = :current_user AND status = 'pending'");
    $update_stmt->execute(['f_id' => $friendship_id, 'current_user' => $current_user_id]);
    header("Location: friends.php");
    exit();
}

// Fetch Pending Requests (where current user is user_id2)
$req_stmt = $pdo->prepare("
    SELECT f.id as friendship_id, a.username 
    FROM friendships f 
    JOIN account a ON f.user_id1 = a.id 
    WHERE f.user_id2 = :current_id AND f.status = 'pending'
");
$req_stmt->execute(['current_id' => $current_user_id]);
$pending_requests = $req_stmt->fetchAll();

// Fetch Accepted Friends
$friends_stmt = $pdo->prepare("
    SELECT a.username 
    FROM friendships f 
    JOIN account a ON (f.user_id1 = a.id OR f.user_id2 = a.id)
    WHERE (f.user_id1 = :current_id1 OR f.user_id2 = :current_id2) 
    AND a.id != :current_id3 
    AND f.status = 'accepted'
");
$friends_stmt->execute([
    'current_id1' => $current_user_id,
    'current_id2' => $current_user_id,
    'current_id3' => $current_user_id
]);
$friends_list = $friends_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Friends List</title>
</head>
<body>
    <?php include('../includes/menubar.php'); ?>

    <div class="container">
        <h1>Friends</h1>

        <div style="background: #2d2d2d; padding: 20px; border-radius: 6px; margin-bottom: 20px; text-align: left;">
            <h3 style="margin-top: 0; color: #17a2b8;">Friend Requests</h3>
            <?php if ($pending_requests): ?>
                <?php foreach ($pending_requests as $req): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #3d3d3d; border-radius: 4px; margin-bottom: 5px;">
                        <span><?php echo htmlspecialchars($req['username']); ?> wants to be friends.</span>
                        <form action="friends.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="action" value="accept">
                            <input type="hidden" name="friendship_id" value="<?php echo $req['friendship_id']; ?>">
                            <button type="submit" style="padding: 5px 15px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">Accept</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #bbb;">No pending requests.</p>
            <?php endif; ?>
        </div>

        <div style="background: #2d2d2d; padding: 20px; border-radius: 6px; text-align: left;">
            <h3 style="margin-top: 0; color: #28a745;">My Friends</h3>
            <?php if ($friends_list): ?>
                <ul style="list-style-type: none; padding: 0;">
                    <?php foreach ($friends_list as $friend): ?>
                        <li style="padding: 10px; border-bottom: 1px solid #444;">
                            <a href="profile.php?owner=<?php echo urlencode($friend['username']); ?>" style="color: #007bff; text-decoration: none; font-weight: bold;">
                                <?php echo htmlspecialchars($friend['username']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #bbb;">You haven't added any friends yet.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
