<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // API Gateway endpoint
    $apiGatewayUrl = 'https://nwa8jatpb5.execute-api.us-east-1.amazonaws.com/Prod/';

    // File details
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];

    // Read the file content and encode it in base64
    $fileContent = base64_encode(file_get_contents($fileTmpName));

    // Prepare the payload
    $payload = json_encode([
        'body' => json_encode(['test' => 'data']),
        'headers' => [
            'file-name' => $fileName
        ]
    ]);

    // Initialize cURL
    $ch = curl_init($apiGatewayUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);

    // Enable error reporting
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing purposes

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4'>cURL error: $error_msg</div>";
    } else {
        // Handle the response
        if ($httpCode == 200) {
            $message = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4'>File uploaded successfully.</div>";
        } else {
            $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4'>Failed to upload file. HTTP Status Code: $httpCode</div>";
        }
    }

    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload to S3</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .custom-file-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<!-- Fixed navigation bar at the top -->
<nav class="fixed top-0 left-0 right-0 bg-white shadow-lg z-10">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="mainPage.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300">Back to Inventory</a>
            <h1 class="text-2xl font-bold text-gray-800">Upload to S3</h1>
            <a href="index.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition duration-300">Logout</a>
        </div>
    </div>
</nav>

<!-- Main content with padding-top to account for fixed nav -->
<div class="pt-20 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-8">
            <?php if (isset($message)) echo $message; ?>

            <form action="uploadfiles.php" method="post" enctype="multipart/form-data" class="space-y-6">
                <div class="space-y-4">
                    <div id="dropZone" class="file-input-wrapper w-full">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-300">
                            <span class="text-gray-600">Drag and drop your file here or</span>
                            <label class="ml-1 text-blue-500 hover:text-blue-600 cursor-pointer">browse
                                <input type="file" name="file" required
                                       class="hidden"
                                       onchange="updateFileName(this)"
                                />
                            </label>
                            <p id="selectedFileName" class="mt-2 text-sm text-gray-500">No file selected</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-white py-3 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                    Upload to S3
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // File name update function
    function updateFileName(input) {
        const fileName = input.files[0]?.name;
        document.getElementById('selectedFileName').textContent = fileName || 'No file selected';
    }

    // Drag and drop functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = dropZone.querySelector('input[type="file"]');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Handle drop
    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            updateFileName(fileInput);
        }
    });

    // Add visual feedback
    dropZone.addEventListener('dragenter', function() {
        dropZone.querySelector('div').classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function() {
        dropZone.querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function() {
        dropZone.querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
    });
</script>
</body>
</html>