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
    <title>Admin - Create User</title>
    <style>
        body {
            background-color: #121212; /* Dark background */
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #1e1e1e;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #2d2d2d;
            border: 1px solid #444;
            color: white;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New User</h2>
        <?php echo $message; ?>
        <form action="newuser.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <textarea name="description" placeholder="Description" rows="3"></textarea>
            <button type="submit">Create User</button>
        </form>
    </div>
</body>
</html>
