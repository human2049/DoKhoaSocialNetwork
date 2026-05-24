<?php
session_start();
require_once('../includes/db.php');

// 1. Force Login
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 2. THIS IS THE MISSING PART: Handle the Update Request
$owner = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_fullname = $_POST['fullname'];
    $new_desc     = $_POST['description'];
    $sql = "UPDATE account SET fullname='" . $new_fullname . "', description='" . $new_desc . "' WHERE username='" . $owner . "'";
    $pdo->query($sql);
}
// Also update the SELECT to use $owner instead of $user_id
// 3. Fetch data AFTER the update so the form shows the NEW info
$stmt = $pdo->prepare("SELECT fullname, description FROM account WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>SocialNet - Settings</title>
</head>
<body>
    <?php include('../includes/menubar.php'); ?>
    <div class="container">
        <h1>Edit Settings</h1>
        <?php if ($message) echo $message; ?>
        <form action="setting.php" method="POST">
            <div style="text-align: left;">
                <label>Full Name:</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                <label>Bio / Description:</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($user['description']); ?></textarea>
            </div>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
