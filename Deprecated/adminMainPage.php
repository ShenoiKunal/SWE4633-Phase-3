<?php
session_start();
// Ensure only admins can access the page
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: index.php');
    exit();
}

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

// Add new user
if (isset($_POST['addUserButton'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO AuthorizedUsers (username, password, isAdmin) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $hashedPassword, $isAdmin);

        if ($stmt->execute()) {
            echo "<script>alert('New user added successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

// Insert item
if (isset($_POST['newItemButton'])) {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $itemCost = $_POST['item_cost'];
    $itemQuantity = $_POST['item_quantity'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Items (item_id, item_name, item_price, item_qty) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $itemId, $itemName, $itemCost, $itemQuantity);

    if ($stmt->execute()) {
        echo "<script>alert('New item created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Remove item
if (isset($_POST['removeItemButton'])) {
    $itemId = $_POST['item_id'];

    $stmt = $conn->prepare("DELETE FROM Items WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Item removed successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Increase item quantity
if (isset($_POST['itemIncreaseButton'])) {
    $itemId = $_POST['increase_item_id'];
    $itemQuantity = $_POST['amount_increased'];

    $stmt = $conn->prepare("UPDATE Items SET item_qty = item_qty + ? WHERE item_id = ?");
    $stmt->bind_param("ii", $itemQuantity, $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Item $itemId quantity has been increased by $itemQuantity');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Decrease item quantity
if (isset($_POST['itemDecreaseButton'])) {
    $itemId = $_POST['decrease_item_id'];
    $itemQuantity = $_POST['amount_decreased'];

    $stmt = $conn->prepare("UPDATE Items SET item_qty = item_qty - ? WHERE item_id = ?");
    $stmt->bind_param("ii", $itemQuantity, $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Item $itemId quantity has been decreased by $itemQuantity');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// View inventory
$sql = "SELECT * FROM Items";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-lg mb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <h1 class="text-2xl font-bold text-gray-800">Admin Inventory Management</h1>
            <a href="../index.php"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-300">Logout</a>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Add New User Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New User</h2>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" name="confirmPassword" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="isAdmin" class="form-checkbox text-gray-600">
                        <span class="ml-2">Admin Privileges</span>
                    </label>
                </div>
                <button type="submit" name="addUserButton"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add User
                </button>
            </form>
        </div>

        <!-- Add New Item Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Item</h2>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="item_id" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                    <input type="text" name="item_name" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Cost</label>
                    <input type="text" name="item_cost" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Quantity</label>
                    <input type="text" name="item_quantity" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" name="newItemButton"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Item
                </button>
            </form>
        </div>

        <!-- Remove Item Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Remove Item</h2>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="item_id" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" name="removeItemButton"
                        class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Remove Item
                </button>
            </form>
        </div>

        <!-- Increase Quantity Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Increase Item Quantity</h2>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="increase_item_id" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Amount to Increase</label>
                    <input type="text" name="amount_increased" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" name="itemIncreaseButton"
                        class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Increase
                </button>
            </form>
        </div>

        <!-- Decrease Quantity Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Decrease Item Quantity</h2>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="decrease_item_id" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Amount to Decrease</label>
                    <input type="text" name="amount_decreased" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" name="itemDecreaseButton"
                        class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Decrease
                </button>
            </form>
        </div>

    </div>

    <!-- Current Inventory Table -->
    <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Current Inventory</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Cost</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='hover:bg-gray-50'>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row["item_id"]) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row["item_name"]) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row["item_qty"]) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . htmlspecialchars($row["item_price"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center'>No records found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
