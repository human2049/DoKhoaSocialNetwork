<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// 1. Determine the owner (from URL or Session)
$owner_username = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];

// 2. Fetch that specific user's data (Added 'id' to the SELECT query)
$sql = "SELECT id, username, fullname, description FROM account WHERE username = '" . $owner_username . "'";
$profile_owner = $pdo->query($sql)->fetch();
if (!$profile_owner) {
    die("User profile not found.");
}

// 3. Friendship and Authorization Logic
$current_user_id = $_SESSION['user_id'];
$profile_owner_id = $profile_owner['id'];

$is_owner = ($owner_username === $_SESSION['username']);
$is_friend = false;

if (!$is_owner) {
    // Check if a friendship exists in the database using PDO
    $stmt_friend = $pdo->prepare("SELECT id FROM friendships WHERE ((user_id1 = :u1 AND user_id2 = :u2) OR (user_id1 = :u3 AND user_id2 = :u4)) AND status = 'accepted'");
    $stmt_friend->execute([
        'u1' => $current_user_id,
        'u2' => $profile_owner_id,
        'u3' => $profile_owner_id,
        'u4' => $current_user_id
    ]);
    
    if ($stmt_friend->fetch()) {
        $is_friend = true;
    }
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
                    <?php echo nl2br($profile_owner['description']); ?>
                </div>
            </div>
            
        <?php else: ?>
            <div style="text-align: center; margin-top: 40px; padding: 20px; background: #2d2d2d; border-radius: 6px;">
                <h3>🔒 This profile is private</h3>
                <p style="color: #bbb;">You must be friends with <?php echo htmlspecialchars($profile_owner['username']); ?> to view their details.</p>
                
                <form action="add_friend.php" method="POST" style="margin-top: 20px;">
                    <input type="hidden" name="target_id" value="<?php echo $profile_owner_id; ?>">
                    <input type="hidden" name="target_username" value="<?php echo htmlspecialchars($profile_owner['username']); ?>">
                    <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Add Friend
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
