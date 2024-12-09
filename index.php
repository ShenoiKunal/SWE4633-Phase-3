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
    <button type="submit" name="login" onclick="window.location.href='https://us-east-1felamqw8n.auth.us-east-1.amazoncognito.com/login/continue?client_id=6lq1c74ttht2nis6v6iq8u1f2a&redirect_uri=https%3A%2F%2Fproject.appainthecloud.com%2FmainPage.php&response_type=code&scope=email+openid+phone'">
        Login
    </button>
</div>
</body>
</html>