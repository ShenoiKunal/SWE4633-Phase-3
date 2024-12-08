<?php
// Database credentials
$servername = "database-1.cn80gk2k0elm.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "07072001";
$dbname = "Project_Phase3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hash the password
$hashedPassword = password_hash('password123', PASSWORD_BCRYPT);

// Insert admin user
$stmt = $conn->prepare("INSERT INTO AuthorizedUsers (username, password, isAdmin) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $username, $hashedPassword, $isAdmin);

$username = 'admin';
$isAdmin = 1;

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
