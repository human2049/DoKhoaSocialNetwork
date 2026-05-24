<?php
session_start();
require_once('../includes/db.php');

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $password = $_POST['password']; // Depending on your lab setup, you might need to hash this (e.g., md5 or password_hash)
    $description = trim($_POST['description']);

    if (empty($username) || empty($password)) {
        $error_msg = "Error: Username and Password are required.";
    } else {
        // 1. Check if the username already exists
        $check_stmt = $pdo->prepare("SELECT id FROM account WHERE username = :username");
        $check_stmt->execute(['username' => $username]);

        if ($check_stmt->fetch()) {
            // Username exists - set the error message
            $error_msg = "Error: That username is already taken. Please choose another.";
        } else {
            // 2. Username is unique - proceed with the INSERT
            try {
                $insert_stmt = $pdo->prepare("INSERT INTO account (username, fullname, password, description) VALUES (:username, :fullname, :password, :description)");
                $insert_stmt->execute([
                    'username' => $username,
                    'fullname' => $fullname,
                    'password' => $password,
                    'description' => $description
                ]);
                
                $success_msg = "User '$username' created successfully!";
            } catch (PDOException $e) {
                // Catch any other unexpected database errors
                $error_msg = "Database Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Create New User</title>
</head>
<body>
    <?php include('../includes/menubar.php'); ?>

    <div class="container" style="max-width: 500px; background: #222; padding: 30px; border-radius: 8px; margin: 40px auto; text-align: left;">
        
        <h2 style="color: white; margin-bottom: 20px;">Create New User</h2>
        
        <?php if (!empty($error_msg)): ?>
            <p style="color: #ff4444; margin-bottom: 20px; font-weight: bold;">
                <?php echo htmlspecialchars($error_msg); ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            <p style="color: #00C851; margin-bottom: 20px; font-weight: bold;">
                <?php echo htmlspecialchars($success_msg); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="newuser.php">
            <div style="margin-bottom: 15px;">
                <input type="text" name="username" placeholder="Username" required 
                       style="width: 100%; padding: 10px; background: #333; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <input type="text" name="fullname" placeholder="Full Name" 
                       style="width: 100%; padding: 10px; background: #333; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <input type="password" name="password" placeholder="Password" required 
                       style="width: 100%; padding: 10px; background: #333; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <textarea name="description" placeholder="Description" rows="4" 
                          style="width: 100%; padding: 10px; background: #333; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box; resize: vertical;"></textarea>
            </div>
            
            <button type="submit" 
                    style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Create User
            </button>
        </form>
    </div>
</body>
</html>
