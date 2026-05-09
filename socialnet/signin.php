<?php
// 1. Start the session - MUST be the very first thing
session_start();

// 2. Include database connection
require_once('../includes/db.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 3. Fetch the user by username
    $sql = "SELECT id, username, password FROM account WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $user]);
    $account = $stmt->fetch();

    // 4. Verify password against the hashed version in DB
    if ($account && password_verify($pass, $account['password'])) {
        // Success! Save user info to the session
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['username'] = $account['username'];

        // Redirect to the home page
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SocialNet - Sign In</title>
    <style>
        body { background-color: #121212; color: #e0e0e0; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #1e1e1e; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); width: 320px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #2d2d2d; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box;}
        button { width: 100%; padding: 10px; background-color: #28a745; border: none; color: white; font-weight: bold; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error { color: #ff6b6b; font-size: 0.9rem; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>SocialNet</h2>
        <p>Please sign in</p>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="signin.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
