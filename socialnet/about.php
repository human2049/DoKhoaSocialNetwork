<?php
// Start the session to keep the user logged in
session_start();

// Security check: only logged-in users should see the menu and about content
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SocialNet - About</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include('../includes/menubar.php'); ?>

    <div class="container">
        <h1>About the Developer</h1>
        
        <div style="text-align: left; margin-top: 20px; padding: 20px; background: #2d2d2d; border-radius: 8px;">
            <p style="font-size: 1.1rem; margin-bottom: 10px;">
                <strong>Student Name:</strong> Đỗ Đăng Khoa
            </p>
            <p style="font-size: 1.1rem;">
                <strong>Student Number:</strong> 1694487
            </p>
        </div>

        <p style="margin-top: 30px; color: #888; font-size: 0.9rem;">
            SocialNet Project - Web Programming Assignment
        </p>
    </div>
</body>
</html>
