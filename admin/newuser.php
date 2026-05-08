<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../includes/db.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $full = $_POST['fullname'];
    $desc = $_POST['description'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO account (username, fullname, password, description) 
                VALUES (:username, :fullname, :password, :description)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username'    => $user,
            'fullname'    => $full,
            'password'    => $pass,
            'description' => $desc
        ]);

        $message = "<p style='color: green;'>User created successfully!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Create New User</title>
</head>
<body>
    <h1>Create New User</h1>
    
    <?php echo $message; ?>

    <form action="newuser.php" method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br> 

        <label>Full Name:</label><br>
        <input type="text" name="fullname" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br> 

        <label>Description (Profile Content):</label><br>
        <textarea name="description"></textarea><br><br> 

        <button type="submit">Create User</button>
    </form>
</body>
</html>
