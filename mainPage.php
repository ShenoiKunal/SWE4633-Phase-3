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

// Insert user
if (isset($_POST['newUserButton'])) {
    $UserId = $_POST['user_id'];
    $userPassword = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (userId, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $UserId, $userPassword);

    if ($stmt->execute()) {
        echo "<script>alert('New user created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Insert item
if (isset($_POST['newItemButton'])) {
    $itemId = $_POST['item_id'];
    $itemQuantity = $_POST['item_quantity'];
    $itemCost = $_POST['item_cost'];
    $itemName = $_POST['item_name'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Items (item_id, item_name, item_price, item_qty) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $itemId, $itemName, $itemCost, $itemQuantity);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('New item created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Increase Item Quantity
if (isset($_POST['itemIncreaseButton'])) {
    $itemId = $_POST['increase_item_id'];
    $itemQuantity = $_POST['amount_increased'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE Items SET item_qty = item_qty + ? WHERE item_id = ?");
    $stmt->bind_param("ii", $itemQuantity, $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Item $itemId quantity has been increased by $itemQuantity');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Decrease Item Quantity
if (isset($_POST['itemDecreaseButton'])) {
    $itemId = $_POST['decrease_item_id'];
    $itemQuantity = $_POST['amount_decreased'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE Items SET item_qty = item_qty - ? WHERE item_id = ?");
    $stmt->bind_param("ii", $itemQuantity, $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Item $itemId quantity has been decreased by $itemQuantity');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
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
    <title>Inventory Management</title>
    <!-- Include Tailwind CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS for any additional styles -->
    <style>
        input[type="text"] {
            @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm;
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-lg mb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <h1 class="text-2xl font-bold text-gray-800">Inventory Management</h1>
            <a href="index.php"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-300">Logout</a>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Add New Item Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Item</h2>
            <form action="" method="post" class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                    <input type="text" name="item_name"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="item_id"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Cost</label>
                    <input type="text" name="item_cost"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Quantity</label>
                    <input type="text" name="item_quantity"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" name="newItemButton"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Item
                </button>
            </form>
        </div>

        <!-- Increase Quantity Form -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Increase Quantity</h2>
            <form action="" method="post" class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="increase_item_id"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Amount to Increase</label>
                    <input type="text" name="amount_increased"
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
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Decrease Quantity</h2>
            <form action="" method="post" class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Item ID</label>
                    <input type="text" name="decrease_item_id"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Amount to Decrease</label>
                    <input type="text" name="amount_decreased"
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                        Quantity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                        Cost
                    </th>
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