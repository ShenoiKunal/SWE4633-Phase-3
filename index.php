<?php
session_start();
//<editor-fold desc="Database Credentials">
$servername = "database-1.cn80gk2k0elm.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "07072001";
$dbname = "Project_Phase3";
//</editor-fold>

//<editor-fold desc="Create and check connection">
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//</editor-fold>

//<editor-fold desc="Login Functionality">
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password, isAdmin FROM AuthorizedUsers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($inputPassword, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['isAdmin'] = $row['isAdmin'];

            echo "<script>alert('Login Successful');</script>";

            // Redirect based on admin status
            if ($row['isAdmin']) {
                header('Location: http://ec2-3-134-110-37.us-east-2.compute.amazonaws.com/adminMainPage.php');
            } else {
                header('Location: http://ec2-3-134-110-37.us-east-2.compute.amazonaws.com/mainPage.php');
            }
            exit;
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }

    // Close statement
    $stmt->close();
}
//</editor-fold>

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 2px;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 280px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Inventory Management Portal</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
