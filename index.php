<?php
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

// Check credentials
if (isset($_POST['login'])) {
    $UserId = $_POST['userId'];
    $inputPassword = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE userId = ?");
    $stmt->bind_param("s", $UserId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($inputPassword, $row['password'])) {
            echo "<script>alert('Login Successful');</script>";
            // Redirect after successful login
            header('Location: http://98.82.186.117/mainPage.php');
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
        <input type="text" name="userId" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <br>
    <a href="mainPage.php">Temporary Login</a>
</div>
</body>
</html>